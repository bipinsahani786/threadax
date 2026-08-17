<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\ReturnStatusMail;
use App\Models\Order;
use App\Models\OrderReturn;
use App\Models\OrderReturnImage;
use App\Models\OrderReturnItem;
use App\Notifications\ReturnStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ReturnController extends Controller
{
    /**
     * List all return & exchange requests of the logged-in customer.
     */
    public function index()
    {
        $returns = Auth::user()->orders()
            ->with(['returns.items.product', 'returns.items.originalVariant', 'returns.items.exchangeVariant', 'returns.images'])
            ->has('returns')
            ->get()
            ->pluck('returns')
            ->flatten()
            ->sortByDesc('created_at');

        return view('frontend.pages.account.returns', compact('returns'));
    }

    /**
     * Show return / exchange creation form for a delivered order.
     */
    public function create($orderId)
    {
        $order = Auth::user()->orders()
            ->with(['items.variant.product.images', 'address', 'payment'])
            ->findOrFail($orderId);

        if (!$order->isDelivered()) {
            return redirect()->route('account.orders.show', $order->id)
                ->with('error', 'Returns can only be requested once the order has been delivered.');
        }

        if (!$order->isEligibleForReturn()) {
            return redirect()->route('account.orders.show', $order->id)
                ->with('error', 'This order is not eligible for return. The 7-day return window may have expired or an active return request is already under review.');
        }

        // Load other variants for each item's product to allow exchange selection
        foreach ($order->items as $item) {
            if ($item->variant && $item->variant->product) {
                $item->available_variants = $item->variant->product->variants()
                    ->where('stock', '>', 0)
                    ->where('id', '!=', $item->product_variant_id)
                    ->get();
            } else {
                $item->available_variants = collect();
            }
        }

        return view('frontend.pages.account.return-create', compact('order'));
    }

    /**
     * Store new Return / Exchange request.
     */
    public function store(Request $request, $orderId)
    {
        $order = Auth::user()->orders()
            ->with(['items.variant.product', 'address', 'payment'])
            ->findOrFail($orderId);

        if (!$order->isEligibleForReturn()) {
            return redirect()->route('account.orders.show', $order->id)
                ->with('error', 'This order is not eligible for return or exchange.');
        }

        $request->validate([
            'type'                   => 'required|in:return,exchange',
            'reason'                 => 'required|string|max:100',
            'customer_notes'         => 'nullable|string|max:1000',
            'selected_items'         => 'required|array|min:1',
            'selected_items.*'       => 'required|exists:order_items,id',
            'quantities'             => 'required|array',
            'exchange_variants'      => 'nullable|array',
            'refund_mode'            => 'nullable|string|in:original_source,upi,bank_transfer,store_credit',
            'upi_id'                 => 'nullable|string|max:100',
            'bank_name'              => 'nullable|string|max:100',
            'bank_account_number'    => 'nullable|string|max:50',
            'bank_ifsc'              => 'nullable|string|max:20',
            'bank_beneficiary_name'  => 'nullable|string|max:100',
            'images'                 => 'nullable|array|max:5',
            'images.*'               => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        // Specific validation for COD refund destination
        if ($request->type === 'return' && $order->payment_method === 'cod') {
            if ($request->refund_mode === 'upi' && empty($request->upi_id)) {
                return back()->withInput()->with('error', 'Please enter a valid UPI ID for your refund payout.');
            }
            if ($request->refund_mode === 'bank_transfer' && (empty($request->bank_account_number) || empty($request->bank_ifsc))) {
                return back()->withInput()->with('error', 'Please provide your complete bank account number and IFSC code.');
            }
        }

        $orderReturn = DB::transaction(function () use ($request, $order) {
            $totalRefund = 0;
            $itemsData = [];

            foreach ($request->selected_items as $orderItemId) {
                $orderItem = $order->items->firstWhere('id', (int) $orderItemId);
                if (!$orderItem) continue;

                $requestedQty = (int) ($request->quantities[$orderItemId] ?? 1);
                $requestedQty = max(1, min($requestedQty, $orderItem->quantity));

                $unitPrice = (float) $orderItem->price;
                $lineTotal = $unitPrice * $requestedQty;
                $totalRefund += $lineTotal;

                $exchangeVariantId = null;
                if ($request->type === 'exchange') {
                    $exchangeVariantId = $request->exchange_variants[$orderItemId] ?? null;
                }

                $itemsData[] = [
                    'order_item_id'       => $orderItem->id,
                    'product_id'          => $orderItem->variant?->product_id,
                    'product_variant_id'  => $orderItem->product_variant_id,
                    'exchange_variant_id' => $exchangeVariantId,
                    'quantity'            => $requestedQty,
                    'price'               => $unitPrice,
                    'total'               => $lineTotal,
                    'reason'              => $request->reason,
                ];
            }

            // Determine refund mode
            $refundMode = 'original_source';
            if ($order->payment_method === 'cod') {
                $refundMode = $request->refund_mode ?: 'upi';
            }

            // Create Return Request Record
            $orderReturn = OrderReturn::create([
                'order_id'              => $order->id,
                'user_id'               => Auth::id(),
                'return_number'         => OrderReturn::generateReturnNumber(),
                'type'                  => $request->type,
                'status'                => 'requested',
                'reason'                => $request->reason,
                'customer_notes'        => $request->customer_notes,
                'refund_mode'           => $refundMode,
                'refund_amount'         => $request->type === 'return' ? $totalRefund : 0,
                'upi_id'                => $request->upi_id,
                'bank_name'             => $request->bank_name,
                'bank_account_number'   => $request->bank_account_number,
                'bank_ifsc'             => $request->bank_ifsc,
                'bank_beneficiary_name' => $request->bank_beneficiary_name,
            ]);

            // Create Return Items
            foreach ($itemsData as $data) {
                $orderReturn->items()->create($data);
            }

            // Handle Image Uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('returns', 'public');
                    $orderReturn->images()->create(['image_path' => $path]);
                }
            }

            // Add Milestone to Order Tracking Timeline
            $typeLabel = $request->type === 'exchange' ? 'Exchange' : 'Return';
            $order->trackings()->create([
                'status'          => 'rto',
                'title'           => "{$typeLabel} Request #{$orderReturn->return_number} Submitted",
                'location'        => 'ThreadAX Customer Support',
                'activity'        => "Customer initiated a {$typeLabel} request for items in this order. Reason: {$orderReturn->reason_label}.",
                'event_time'      => now(),
            ]);

            return $orderReturn;
        });

        // Send In-App & Email Notification
        try {
            Auth::user()->notify(new ReturnStatusNotification($orderReturn));
            
            $recipientEmail = $order->shipping_email ?? Auth::user()->email;
            if ($recipientEmail) {
                Mail::to($recipientEmail)->send(new ReturnStatusMail($orderReturn));
            }
        } catch (\Exception $e) {
            Log::error("Failed to send return request notifications: " . $e->getMessage());
        }

        return redirect()->route('account.returns.show', $orderReturn->id)
            ->with('success', "Your {$orderReturn->type} request #{$orderReturn->return_number} has been submitted successfully! We will review it shortly.");
    }

    /**
     * Show return / exchange details and live progress tracker.
     */
    public function show($id)
    {
        $returnRequest = OrderReturn::with([
            'order.address',
            'order.payment',
            'items.product.images',
            'items.originalVariant',
            'items.exchangeVariant',
            'images',
        ])
        ->where('user_id', Auth::id())
        ->findOrFail($id);

        return view('frontend.pages.account.return-detail', compact('returnRequest'));
    }
}
