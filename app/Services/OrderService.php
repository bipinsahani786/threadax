<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Coupon;
use App\Services\CartService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    public function __construct(private CartService $cartService) {}

    public function createOrder($userId, $addressId, $paymentMethod, ?Coupon $coupon = null, float $discount = 0)
    {
        $cart    = $this->cartService->getCart();
        $summary = $this->cartService->getSummary();

        if ($cart->items->isEmpty()) {
            throw new \Exception("Cart is empty");
        }

        return DB::transaction(function () use ($cart, $summary, $userId, $addressId, $paymentMethod, $coupon, $discount) {
            
            $total = max(0, $summary['total'] - $discount);

            // Create Order
            $order = Order::create([
                'user_id'        => $userId,
                'order_number'   => $this->generateOrderNumber(),
                'address_id'     => $addressId,
                'coupon_id'      => $coupon?->id,
                'coupon_code'    => $coupon?->code,
                'subtotal'       => $summary['subtotal'],
                'discount'       => $discount,
                'shipping'       => $summary['shipping'],
                'tax'            => 0,
                'total'          => $total,
                'status'         => 'pending',
                'payment_method' => $paymentMethod,
                'payment_status' => 'pending',
            ]);

            // Create Order Items and Check Stock
            foreach ($cart->items as $item) {
                if (!$item->variant || !$item->variant->product) {
                    continue;
                }

                if ($item->variant->stock < $item->quantity) {
                    $prodName = $item->variant->product?->name ?? 'the selected item';
                    throw new \Exception("Not enough stock for {$prodName}");
                }

                $unitPrice = $item->variant?->effective_price ?? $item->price_at_time ?? 0;
                $order->items()->create([
                    'product_variant_id' => $item->product_variant_id,
                    'quantity'           => $item->quantity,
                    'price'              => $unitPrice,
                    'total'              => $item->quantity * $unitPrice,
                ]);

                // For COD, deduct stock immediately
                if ($paymentMethod === 'cod') {
                    $item->variant->decrement('stock', $item->quantity);
                }
            }

            // For COD: Order is placed immediately, clear the cart
            // For Online Payment (Razorpay): Keep cart intact until payment is verified successfully
            if ($paymentMethod === 'cod') {
                $order->update([
                    'status'         => 'processing',
                    'payment_status' => 'pending',
                ]);

                $cart->items()->delete();
                $cart->delete();
            }

            return $order;
        });
    }

    /**
     * Confirm an online payment order: deduct stock, clear cart, update status to paid/processing.
     */
    public function confirmOrder(Order $order): void
    {
        if ($order->payment_status === 'paid') {
            return; // Idempotent check
        }

        DB::transaction(function () use ($order) {
            // Deduct stock for order items
            foreach ($order->items as $orderItem) {
                if ($orderItem->variant) {
                    $orderItem->variant->decrement('stock', $orderItem->quantity);
                }
            }

            // Update order status
            $order->update([
                'payment_status' => 'paid',
                'status'         => 'processing',
            ]);

            // Clear the user's cart now that payment is confirmed
            $this->cartService->clearCart($order->user_id);
        });
    }

    /**
     * Cancel an uncompleted/failed pending order.
     */
    public function cancelOrder(Order $order, string $reason = 'Payment cancelled'): void
    {
        if ($order->payment_status === 'paid' || $order->status === 'delivered') {
            return;
        }

        DB::transaction(function () use ($order) {
            // If stock was already deducted (e.g. COD cancelled), restore stock
            if ($order->payment_method === 'cod' || $order->status === 'processing') {
                foreach ($order->items as $orderItem) {
                    if ($orderItem->variant) {
                        $orderItem->variant->increment('stock', $orderItem->quantity);
                    }
                }
            }

            $order->update([
                'status'         => 'cancelled',
                'payment_status' => 'failed',
            ]);
        });
    }

    private function generateOrderNumber()
    {
        do {
            $number = 'TX-' . strtoupper(Str::random(8));
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }
}
