<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderCancelledMail;
use App\Mail\OrderConfirmedMail;
use App\Mail\OrderDeliveredMail;
use App\Mail\OrderShippedMail;
use App\Models\Order;
use App\Models\OrderTracking;
use App\Models\PaymentTransaction;
use App\Notifications\OrderStatusNotification;
use App\Services\PaymentService;
use App\Services\ShiprocketService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
        private ShiprocketService $shiprocketService
    ) {}

    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.variant.product.images', 'payment'])->latest();

        // 1. Text Search (Order ID, Customer Name, Email, Phone)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('shipping_name', 'like', "%{$search}%")
                  ->orWhere('shipping_email', 'like', "%{$search}%")
                  ->orWhere('shipping_phone', 'like', "%{$search}%")
                  ->orWhere('tracking_number', 'like', "%{$search}%")
                  ->orWhere('shiprocket_awb_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Fulfillment Status Filter
        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'active') {
                $query->whereIn('status', ['pending', 'processing', 'shipped']);
            } else {
                $query->where('status', $request->status);
            }
        }

        // 3. Payment Status Filter
        if ($request->filled('payment_status') && $request->payment_status !== 'all') {
            $query->where('payment_status', $request->payment_status);
        }

        // 4. Payment Method Filter
        if ($request->filled('payment_method') && $request->payment_method !== 'all') {
            $query->where('payment_method', $request->payment_method);
        }

        // 5. Custom Date Range & Presets
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('preset')) {
            if ($request->preset === 'today') {
                $query->whereDate('created_at', now()->today());
            } elseif ($request->preset === '7_days') {
                $query->where('created_at', '>=', now()->subDays(7));
            } elseif ($request->preset === '30_days') {
                $query->where('created_at', '>=', now()->subDays(30));
            }
        }

        // 6. Analytics KPI Stats
        $stats = [
            'total'               => Order::count(),
            'total_revenue'       => Order::where('status', '!=', 'cancelled')->sum('total'),
            'pending'             => Order::where('status', 'pending')->count(),
            'processing'          => Order::where('status', 'processing')->count(),
            'shipped'             => Order::where('status', 'shipped')->count(),
            'processing_shipped'  => Order::whereIn('status', ['processing', 'shipped'])->count(),
            'delivered'           => Order::where('status', 'delivered')->count(),
            'cancelled'           => Order::where('status', 'cancelled')->count(),
            'today'               => Order::whereDate('created_at', now()->today())->count(),
            'paid_count'          => Order::where('payment_status', 'paid')->count(),
            'cod_count'           => Order::where('payment_method', 'cod')->count(),
        ];

        $orders = $query->paginate(15)->withQueryString();
        return view('admin.pages.orders.index', compact('orders', 'stats'));
    }

    public function show($id)
    {
        $order = Order::with([
            'items.variant.product.images',
            'user',
            'address',
            'payment',
            'coupon',
            'trackings' => fn($q) => $q->orderBy('event_time', 'desc')->orderBy('id', 'desc'),
        ])->findOrFail($id);

        $shiprocketConfigured = $this->shiprocketService->isConfigured();

        return view('admin.pages.orders.show', compact('order', 'shiprocketConfigured'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status'          => 'required|in:pending,processing,shipped,delivered,cancelled',
            'payment_status'  => 'required|in:pending,paid,failed,refunded',
            'courier_name'    => 'nullable|string|max:100',
            'tracking_number' => 'nullable|string|max:100',
            'tracking_url'    => 'nullable|url|max:255',
        ]);

        $order = Order::with(['items.variant', 'payment', 'user', 'address'])->findOrFail($id);

        $oldStatus = $order->status;
        $newStatus = $request->status;
        $isCancelling = $newStatus === 'cancelled' && $oldStatus !== 'cancelled';
        $isDelivering = $newStatus === 'delivered' && $oldStatus !== 'delivered';
        $isShipping   = $newStatus === 'shipped' && $oldStatus !== 'shipped';

        DB::transaction(function () use ($order, $request, $isCancelling, $isDelivering, $isShipping, $oldStatus, $newStatus) {
            // ── 1. Restore stock & Auto-Refund when cancelling ──────────────
            if ($isCancelling) {
                foreach ($order->items as $item) {
                    if ($item->variant) {
                        $item->variant->increment('stock', $item->quantity);
                    }
                }
                Log::info("Stock restored for cancelled order #{$order->order_number}");

                // Auto-refund via Razorpay if order was paid online
                if ($order->payment && $order->payment->isRefundable()) {
                    try {
                        $refundId = $this->paymentService->initiateRefund($order->payment);
                        $order->payment->update([
                            'status'        => 'refunded',
                            'refund_id'     => $refundId,
                            'refund_status' => 'initiated',
                        ]);
                        $request->merge(['payment_status' => 'refunded']);

                        PaymentTransaction::log([
                            'order_id'       => $order->id,
                            'user_id'        => $order->user_id,
                            'payment_id'     => $order->payment->id,
                            'transaction_id' => $refundId,
                            'gateway'        => 'razorpay',
                            'event'          => 'refund_initiated',
                            'amount'         => $order->total,
                            'status'         => 'refunded',
                            'payload'        => ['reason' => 'Auto-refund on order cancellation', 'payment_id' => $order->payment->razorpay_payment_id],
                        ]);

                        Log::info("Auto-refund initiated for order #{$order->order_number} (Refund ID: {$refundId})");
                    } catch (\Exception $refEx) {
                        Log::error("Auto-refund failed for order #{$order->order_number}: " . $refEx->getMessage());
                    }
                }
            }

            // ── 2. Build update payload ─────────────────────────────────────
            $updateData = [
                'status'          => $newStatus,
                'payment_status'  => $request->payment_status,
                'courier_name'    => $request->courier_name ?? $order->courier_name,
                'tracking_number' => $request->tracking_number ?? $order->tracking_number,
                'tracking_url'    => $request->tracking_url ?? $order->tracking_url,
            ];

            if ($isDelivering) {
                $updateData['delivered_at'] = now();
                if ($order->payment_method === 'cod') {
                    $updateData['payment_status'] = 'paid';
                }
            }

            if ($isShipping && !$order->shipped_at) {
                $updateData['shipped_at'] = now();
            }

            $order->update($updateData);

            // ── 3. Add auto tracking log if status changed ──────────────────
            if ($oldStatus !== $newStatus) {
                $statusTitles = [
                    'pending'    => 'Order Placed (Pending Confirmation)',
                    'processing' => 'Order Confirmed & Processing',
                    'shipped'    => 'Order Dispatched & Shipped',
                    'delivered'  => 'Order Successfully Delivered to Customer',
                    'cancelled'  => 'Order Cancelled',
                ];

                $order->trackings()->create([
                    'status'          => $newStatus,
                    'title'           => $statusTitles[$newStatus] ?? ucfirst($newStatus),
                    'location'        => $newStatus === 'delivered' ? ($order->address?->city ?? 'Customer Doorstep') : 'ThreadAX Warehouse',
                    'activity'        => "Order status updated to " . ucfirst($newStatus) . " by Admin.",
                    'courier_name'    => $order->effective_courier,
                    'tracking_number' => $order->effective_awb,
                    'event_time'      => now(),
                ]);
            }

            // ── 4. Trigger Razorpay Refund on cancellation ──────────────────
            if ($isCancelling && $order->payment && $order->payment->isRefundable()) {
                try {
                    $refundId = $this->paymentService->initiateRefund($order->payment);
                    $order->payment->update([
                        'refund_id'     => $refundId,
                        'refund_status' => 'initiated',
                    ]);
                    session()->flash('info', 'Order cancelled. Razorpay refund initiated.');
                } catch (\Exception $e) {
                    Log::error("Razorpay auto-refund failed for order #{$order->order_number}: " . $e->getMessage());
                    session()->flash('warning', 'Order cancelled but auto-refund failed: ' . $e->getMessage());
                }
            }
        });

        // ── 5. Trigger Multi-Channel Customer Notification (Database In-App + Email) ──
        if ($oldStatus !== $newStatus) {
            $this->sendCustomerOrderStatusNotification($order, $newStatus);
        }

        if (!session()->has('info') && !session()->has('warning')) {
            session()->flash('success', 'Order details & status updated successfully. Customer notification dispatched.');
        }

        return redirect()->route('admin.orders.show', $order->id);
    }

    /**
     * Add manual tracking checkpoint / milestone
     */
    public function addTracking(Request $request, $id)
    {
        $request->validate([
            'status'          => 'required|string|in:order_placed,processing,packed,shipped,in_transit,out_for_delivery,delivered,cancelled,rto',
            'title'           => 'required|string|max:255',
            'location'        => 'nullable|string|max:255',
            'activity'        => 'nullable|string|max:1000',
            'courier_name'    => 'nullable|string|max:100',
            'tracking_number' => 'nullable|string|max:100',
            'event_time'      => 'nullable|date',
            'sync_order_status' => 'nullable|boolean',
        ]);

        $order = Order::findOrFail($id);

        $tracking = $order->trackings()->create([
            'status'          => $request->status,
            'title'           => $request->title,
            'location'        => $request->location,
            'activity'        => $request->activity,
            'courier_name'    => $request->courier_name ?: $order->effective_courier,
            'tracking_number' => $request->tracking_number ?: $order->effective_awb,
            'event_time'      => $request->filled('event_time') ? $request->event_time : now(),
        ]);

        // If requested or if status is delivered/shipped, sync main order fields
        if ($request->boolean('sync_order_status')) {
            $mappedOrderStatus = match($request->status) {
                'order_placed', 'processing', 'packed' => 'processing',
                'shipped', 'in_transit', 'out_for_delivery' => 'shipped',
                'delivered' => 'delivered',
                'cancelled' => 'cancelled',
                default => $order->status,
            };

            $updateData = ['status' => $mappedOrderStatus];
            if ($request->filled('courier_name')) {
                $updateData['courier_name'] = $request->courier_name;
            }
            if ($request->filled('tracking_number')) {
                $updateData['tracking_number'] = $request->tracking_number;
            }
            if ($mappedOrderStatus === 'delivered') {
                $updateData['delivered_at'] = now();
                if ($order->payment_method === 'cod') {
                    $updateData['payment_status'] = 'paid';
                }
            }

            $oldStatus = $order->status;
            $order->update($updateData);

            if ($oldStatus !== $mappedOrderStatus) {
                $this->sendCustomerOrderStatusNotification($order, $mappedOrderStatus);
            }
        }

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'New tracking checkpoint added and customer notified.');
    }

    /**
     * 1-Click Push Order to Shiprocket
     */
    public function pushToShiprocket($id)
    {
        $order = Order::findOrFail($id);

        if (!$this->shiprocketService->isConfigured()) {
            return redirect()->route('admin.settings.index')
                ->with('error', 'Shiprocket is not configured. Please enter your Shiprocket API Email & Password in Settings > Logistics.');
        }

        $result = $this->shiprocketService->createOrder($order);

        if ($result['success']) {
            return redirect()->route('admin.orders.show', $order->id)
                ->with('success', $result['message']);
        }

        return redirect()->route('admin.orders.show', $order->id)
            ->with('error', $result['message']);
    }

    /**
     * Generate / Assign AWB from Shiprocket
     */
    public function generateShiprocketAwb($id)
    {
        $order = Order::findOrFail($id);

        if (!$this->shiprocketService->isConfigured()) {
            return redirect()->route('admin.settings.index')
                ->with('error', 'Shiprocket is not configured. Please enter your Shiprocket API Email & Password in Settings > Logistics.');
        }

        $result = $this->shiprocketService->generateAwb($order);

        if ($result['success']) {
            // If order transitioned to shipped, notify customer
            if ($order->fresh()->status === 'shipped') {
                $this->sendCustomerOrderStatusNotification($order->fresh(), 'shipped');
            }
            return redirect()->route('admin.orders.show', $order->id)
                ->with('success', $result['message']);
        }

        return redirect()->route('admin.orders.show', $order->id)
            ->with('error', $result['message']);
    }

    /**
     * Sync tracking from Shiprocket API
     */
    public function syncShiprocketTracking($id)
    {
        $order = Order::findOrFail($id);

        if (!$this->shiprocketService->isConfigured()) {
            return redirect()->route('admin.settings.index')
                ->with('error', 'Shiprocket is not configured. Please enter your Shiprocket API Email & Password in Settings > Logistics.');
        }

        $oldStatus = $order->status;
        $result = $this->shiprocketService->trackOrder($order);

        if ($result['success']) {
            $freshOrder = $order->fresh();
            if ($oldStatus !== $freshOrder->status) {
                $this->sendCustomerOrderStatusNotification($freshOrder, $freshOrder->status);
            }
            return redirect()->route('admin.orders.show', $order->id)
                ->with('success', $result['message']);
        }

        return redirect()->route('admin.orders.show', $order->id)
            ->with('error', $result['message']);
    }

    /**
     * Printable 4x6 / A4 Shipping Label View
     */
    public function shippingLabel($id)
    {
        $order = Order::with(['items.variant.product', 'user', 'address'])->findOrFail($id);
        return view('admin.pages.orders.shipping-label', compact('order'));
    }

    /**
     * Download Tax Invoice PDF
     */
    public function downloadInvoice($id)
    {
        $order = Order::with(['items.variant.product', 'user', 'address', 'payment', 'coupon'])->findOrFail($id);
        $pdf   = Pdf::loadView('admin.pages.orders.invoice', compact('order'));
        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }

    /**
     * 1-Click Issue Razorpay Full or Partial Refund from Admin Panel
     */
    public function issueRefund(Request $request, $id)
    {
        $order = Order::with(['payment', 'user', 'items'])->findOrFail($id);

        $request->validate([
            'amount' => 'nullable|numeric|min:1|max:' . $order->total,
            'reason' => 'nullable|string|max:255',
        ]);

        if (!$order->payment || !$order->payment->isRefundable()) {
            return redirect()->route('admin.orders.show', $order->id)
                ->with('error', 'This order is not eligible for automatic Razorpay refund. Payment status must be paid via Razorpay.');
        }

        try {
            $refundAmountPaise = $request->filled('amount') ? (int) ($request->amount * 100) : null;
            $refundedRupees = $request->filled('amount') ? $request->amount : $order->total;

            $refundId = $this->paymentService->initiateRefund($order->payment, $refundAmountPaise);

            $order->payment->update([
                'status'        => 'refunded',
                'refund_id'     => $refundId,
                'refund_status' => 'initiated',
            ]);

            $order->update([
                'payment_status' => 'refunded',
            ]);

            // Audit Log: Admin Manual Refund
            PaymentTransaction::log([
                'order_id'          => $order->id,
                'user_id'           => $order->user_id,
                'payment_id'        => $order->payment->id,
                'transaction_id'    => $refundId,
                'gateway'           => 'razorpay',
                'event'             => 'refund_initiated',
                'amount'            => $refundedRupees,
                'status'            => 'refunded',
                'error_description' => $request->reason,
                'payload'           => [
                    'reason'             => $request->reason ?: 'Admin manual refund',
                    'refund_amount'      => $refundedRupees,
                    'payment_id'         => $order->payment->razorpay_payment_id,
                    'admin_user'         => auth('admin')->user()?->name ?? 'Admin',
                ],
            ]);

            // Add tracking entry
            OrderTracking::create([
                'order_id'    => $order->id,
                'status'      => $order->status,
                'title'       => 'Razorpay Refund Initiated',
                'description' => "Refund of ₹" . number_format($refundedRupees, 2) . " initiated via Razorpay (Refund ID: {$refundId}). Reason: " . ($request->reason ?: 'Customer requested / Admin initiated'),
                'location'    => 'ThreadAX Accounts',
                'event_time'  => now(),
            ]);

            // Dispatch customer cancellation / refund update
            $this->sendCustomerOrderStatusNotification($order, 'cancelled');

            return redirect()->route('admin.orders.show', $order->id)
                ->with('success', "Razorpay refund of ₹" . number_format($refundedRupees, 2) . " initiated successfully! (Refund ID: {$refundId})");
        } catch (\Exception $e) {
            Log::error("Manual Razorpay refund failed for order #{$order->order_number}: " . $e->getMessage());
            return redirect()->route('admin.orders.show', $order->id)
                ->with('error', 'Refund failed: ' . $e->getMessage());
        }
    }

    /**
     * Dispatch Multi-Channel Customer Notification (In-App DB Notification + Branded Email)
     */
    private function sendCustomerOrderStatusNotification(Order $order, string $status): void
    {
        try {
            $order->loadMissing(['user', 'items.variant.product', 'address', 'payment']);

            // 1. Send In-App Database Notification to registered user
            if ($order->user) {
                $notif = new OrderStatusNotification($order);
                $order->user->notify($notif);
                Log::info("Database in-app notification dispatched to user #{$order->user_id} for order #{$order->order_number} ({$status})");

                // 2. Send Mobile Lockscreen Web Push via Firebase FCM
                try {
                    app(\App\Services\FirebasePushService::class)->sendToUser(
                        $order->user,
                        $notif->title,
                        $notif->message,
                        $notif->link
                    );
                } catch (\Exception $fcmEx) {
                    Log::warning("FCM push to user failed: " . $fcmEx->getMessage());
                }
            }

            // 3. Send Branded Email to customer
            $recipientEmail = $order->shipping_email ?? $order->user?->email;
            if ($recipientEmail) {
                switch ($status) {
                    case 'processing':
                        Mail::to($recipientEmail)->send(new OrderConfirmedMail($order));
                        break;
                    case 'shipped':
                        Mail::to($recipientEmail)->send(new OrderShippedMail($order));
                        break;
                    case 'delivered':
                        Mail::to($recipientEmail)->send(new OrderDeliveredMail($order));
                        break;
                    case 'cancelled':
                        Mail::to($recipientEmail)->send(new OrderCancelledMail($order));
                        break;
                }
                Log::info("Order status [{$status}] email dispatched to {$recipientEmail} for order #{$order->order_number}");
            }
        } catch (\Exception $e) {
            Log::error("Failed to dispatch order status notification for order #{$order->order_number}: " . $e->getMessage());
        }
    }
}
