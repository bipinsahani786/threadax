<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ReturnStatusMail;
use App\Models\OrderReturn;
use App\Models\OrderReturnItem;
use App\Models\PaymentTransaction;
use App\Notifications\ReturnStatusNotification;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ReturnController extends Controller
{
    public function __construct(
        private PaymentService $paymentService
    ) {}

    /**
     * Display all return & exchange requests with status filters and KPI stats.
     */
    public function index(Request $request)
    {
        $query = OrderReturn::with(['order.address', 'user', 'items.product', 'items.originalVariant', 'items.exchangeVariant'])->latest();

        // 1. Text Search (Return #, Order #, Customer Name, Email, Phone, AWB)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('return_number', 'like', "%{$search}%")
                  ->orWhere('pickup_awb', 'like', "%{$search}%")
                  ->orWhere('upi_id', 'like', "%{$search}%")
                  ->orWhereHas('order', function ($o) use ($search) {
                      $o->where('order_number', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Status Filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // 3. Type Filter (return / exchange)
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // 4. KPI Stats
        $stats = [
            'total'             => OrderReturn::count(),
            'requested'         => OrderReturn::where('status', 'requested')->count(),
            'approved'          => OrderReturn::where('status', 'approved')->count(),
            'pickup_scheduled'  => OrderReturn::whereIn('status', ['pickup_scheduled', 'picked_up'])->count(),
            'received_at_hub'   => OrderReturn::where('status', 'received_at_hub')->count(),
            'refunded'          => OrderReturn::where('status', 'refunded')->count(),
            'exchanged'         => OrderReturn::where('status', 'exchanged')->count(),
            'rejected'          => OrderReturn::where('status', 'rejected')->count(),
            'total_refund_val'  => OrderReturn::where('status', 'refunded')->sum('refund_amount'),
        ];

        $returns = $query->paginate(15)->withQueryString();

        return view('admin.pages.returns.index', compact('returns', 'stats'));
    }

    /**
     * View detailed return request and processing actions.
     */
    public function show($id)
    {
        $returnRequest = OrderReturn::with([
            'order.address',
            'order.payment',
            'order.trackings',
            'user',
            'items.product.images',
            'items.originalVariant',
            'items.exchangeVariant',
            'images',
        ])->findOrFail($id);

        return view('admin.pages.returns.show', compact('returnRequest'));
    }

    /**
     * Update return status, reverse pickup assignment, or rejection.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status'                => 'required|in:requested,approved,rejected,pickup_scheduled,picked_up,received_at_hub,refunded,exchanged,completed,cancelled',
            'pickup_courier'        => 'nullable|string|max:100',
            'pickup_awb'            => 'nullable|string|max:100',
            'pickup_scheduled_date' => 'nullable|date',
            'admin_notes'           => 'nullable|string|max:1000',
            'rejection_reason'      => 'nullable|string|max:1000',
            'restock_items'         => 'nullable|boolean',
        ]);

        $returnRequest = OrderReturn::with(['items.originalVariant', 'order', 'user'])->findOrFail($id);
        $oldStatus = $returnRequest->status;
        $newStatus = $request->status;

        DB::transaction(function () use ($returnRequest, $request, $oldStatus, $newStatus) {
            $updateData = [
                'status'                => $newStatus,
                'pickup_courier'        => $request->pickup_courier ?: $returnRequest->pickup_courier,
                'pickup_awb'            => $request->pickup_awb ?: $returnRequest->pickup_awb,
                'pickup_scheduled_date' => $request->pickup_scheduled_date ?: $returnRequest->pickup_scheduled_date,
                'admin_notes'           => $request->admin_notes ?: $returnRequest->admin_notes,
                'rejection_reason'      => $request->rejection_reason ?: $returnRequest->rejection_reason,
            ];

            // Set milestone timestamps
            if ($newStatus === 'approved' && !$returnRequest->approved_at) {
                $updateData['approved_at'] = now();
            } elseif ($newStatus === 'picked_up' && !$returnRequest->picked_up_at) {
                $updateData['picked_up_at'] = now();
            } elseif ($newStatus === 'received_at_hub' && !$returnRequest->received_at) {
                $updateData['received_at'] = now();
            } elseif ($newStatus === 'completed' && !$returnRequest->completed_at) {
                $updateData['completed_at'] = now();
            } elseif ($newStatus === 'rejected' && !$returnRequest->rejected_at) {
                $updateData['rejected_at'] = now();
            }

            // Optional auto restock
            if ($request->boolean('restock_items')) {
                foreach ($returnRequest->items as $item) {
                    if (!$item->is_restocked && $item->originalVariant) {
                        $item->originalVariant->increment('stock', $item->quantity);
                        $item->update(['is_restocked' => true]);
                    }
                }
                Log::info("Restocked items for return #{$returnRequest->return_number}");
            }

            $returnRequest->update($updateData);

            // Add Order Tracking Entry
            if ($oldStatus !== $newStatus) {
                $typeLabel = $returnRequest->isExchange() ? 'Exchange' : 'Return';
                $statusTitles = [
                    'approved'         => "{$typeLabel} Request #{$returnRequest->return_number} Approved",
                    'pickup_scheduled' => "Reverse Pickup Scheduled ({$returnRequest->pickup_courier})",
                    'picked_up'        => "Return Parcel Picked Up by Courier",
                    'received_at_hub'  => "Return Parcel Received at Warehouse (QC Passed)",
                    'refunded'         => "Refund of ₹" . number_format($returnRequest->refund_amount, 2) . " Processed",
                    'exchanged'        => "Exchange Replacement Item Dispatched",
                    'completed'        => "{$typeLabel} Request Successfully Completed",
                    'rejected'         => "{$typeLabel} Request Rejected",
                ];

                $returnRequest->order->trackings()->create([
                    'status'          => 'rto',
                    'title'           => $statusTitles[$newStatus] ?? "{$typeLabel} Status: " . ucfirst($newStatus),
                    'location'        => 'ThreadAX Fulfillment Hub',
                    'activity'        => "Return request #{$returnRequest->return_number} status updated to " . ucfirst(str_replace('_', ' ', $newStatus)) . " by Admin.",
                    'courier_name'    => $returnRequest->pickup_courier,
                    'tracking_number' => $returnRequest->pickup_awb,
                    'event_time'      => now(),
                ]);
            }
        });

        // Dispatch Customer Notifications if status changed
        if ($oldStatus !== $newStatus) {
            $this->notifyCustomer($returnRequest->fresh());
        }

        return redirect()->route('admin.returns.show', $returnRequest->id)
            ->with('success', "Return #{$returnRequest->return_number} updated to " . ucfirst(str_replace('_', ' ', $newStatus)) . ".");
    }

    /**
     * 1-Click Inventory Restock for returned items.
     */
    public function restockItems($id)
    {
        $returnRequest = OrderReturn::with('items.originalVariant')->findOrFail($id);
        $count = 0;

        foreach ($returnRequest->items as $item) {
            if (!$item->is_restocked && $item->originalVariant) {
                $item->originalVariant->increment('stock', $item->quantity);
                $item->update(['is_restocked' => true]);
                $count++;
            }
        }

        return redirect()->route('admin.returns.show', $returnRequest->id)
            ->with('success', "{$count} " . \Illuminate\Support\Str::plural('item', $count) . " restocked back to inventory.");
    }

    /**
     * Process Refund (1-click Razorpay or manual COD payout recording).
     */
    public function processRefund(Request $request, $id)
    {
        $returnRequest = OrderReturn::with(['order.payment', 'user', 'items'])->findOrFail($id);

        $request->validate([
            'refund_amount'  => 'required|numeric|min:1',
            'transaction_id' => 'nullable|string|max:100', // For manual/COD UTR
            'admin_notes'    => 'nullable|string|max:500',
        ]);

        $refundAmount = (float) $request->refund_amount;
        $order = $returnRequest->order;
        $transactionRef = $request->transaction_id;

        try {
            // If original order was paid online via Razorpay and no manual reference is provided:
            if ($order->payment_method === 'razorpay' && $order->payment && $order->payment->isRefundable()) {
                $amountPaise = (int) ($refundAmount * 100);
                $transactionRef = $this->paymentService->initiateRefund($order->payment, $amountPaise);
            }

            DB::transaction(function () use ($returnRequest, $order, $refundAmount, $transactionRef, $request) {
                // Update return request status
                $returnRequest->update([
                    'status'                => 'refunded',
                    'refund_amount'         => $refundAmount,
                    'refund_transaction_id' => $transactionRef ?: ('MANUAL-REF-' . strtoupper(\Illuminate\Support\Str::random(6))),
                    'refunded_at'           => now(),
                    'admin_notes'           => $request->admin_notes ?: $returnRequest->admin_notes,
                ]);

                // Update order payment status if fully refunded
                $order->update(['payment_status' => 'refunded']);

                // Log Payment Transaction Ledger
                PaymentTransaction::log([
                    'order_id'       => $order->id,
                    'user_id'        => $returnRequest->user_id,
                    'payment_id'     => $order->payment?->id,
                    'transaction_id' => $returnRequest->refund_transaction_id,
                    'gateway'        => $order->payment_method === 'razorpay' ? 'razorpay' : 'manual_transfer',
                    'event'          => 'refund_processed',
                    'amount'         => $refundAmount,
                    'status'         => 'refunded',
                    'payload'        => [
                        'return_number' => $returnRequest->return_number,
                        'refund_mode'   => $returnRequest->refund_mode,
                        'upi_id'        => $returnRequest->upi_id,
                        'bank_account'  => $returnRequest->bank_account_number,
                        'admin_user'    => auth('admin')->user()?->name ?? 'Admin',
                    ],
                ]);

                // Add Order Tracking Milestone
                $order->trackings()->create([
                    'status'          => 'rto',
                    'title'           => "Refund of ₹" . number_format($refundAmount, 2) . " Released",
                    'location'        => 'ThreadAX Accounts',
                    'activity'        => "Refund released for Return #{$returnRequest->return_number}. Ref/UTR: {$returnRequest->refund_transaction_id}.",
                    'event_time'      => now(),
                ]);
            });

            // Send notification
            $this->notifyCustomer($returnRequest->fresh());

            return redirect()->route('admin.returns.show', $returnRequest->id)
                ->with('success', "Refund of ₹" . number_format($refundAmount, 2) . " processed successfully! Ref: {$returnRequest->refund_transaction_id}");
        } catch (\Exception $e) {
            Log::error("Return refund processing failed for #{$returnRequest->return_number}: " . $e->getMessage());
            return redirect()->route('admin.returns.show', $returnRequest->id)
                ->with('error', "Refund failed: " . $e->getMessage());
        }
    }

    /**
     * Multi-channel notification dispatcher for returns.
     */
    private function notifyCustomer(OrderReturn $returnRequest): void
    {
        try {
            $returnRequest->loadMissing(['order', 'user']);

            // 1. In-App Notification
            if ($returnRequest->user) {
                $notif = new ReturnStatusNotification($returnRequest);
                $returnRequest->user->notify($notif);

                // 2. Mobile Lockscreen Web Push via Firebase FCM
                try {
                    app(\App\Services\FirebasePushService::class)->sendToUser(
                        $returnRequest->user,
                        $notif->title,
                        $notif->message,
                        $notif->link
                    );
                } catch (\Exception $fcmEx) {
                    Log::warning("FCM return push failed: " . $fcmEx->getMessage());
                }
            }

            // 3. Branded Email
            $recipientEmail = $returnRequest->order?->shipping_email ?? $returnRequest->user?->email;
            if ($recipientEmail) {
                Mail::to($recipientEmail)->send(new ReturnStatusMail($returnRequest));
                Log::info("Return notification email sent to {$recipientEmail} for #{$returnRequest->return_number}");
            }
        } catch (\Exception $e) {
            Log::error("Failed to notify customer for return #{$returnRequest->return_number}: " . $e->getMessage());
        }
    }
}
