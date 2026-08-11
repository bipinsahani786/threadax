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

            // Create Order Items and Deduct Stock
            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_variant_id' => $item->product_variant_id,
                    'quantity'           => $item->quantity,
                    'price'              => $item->variant->price ?? $item->price_at_time,
                    'total'              => $item->quantity * ($item->variant->price ?? $item->price_at_time),
                ]);

                // Deduct stock
                if ($item->variant) {
                    if ($item->variant->stock < $item->quantity) {
                        throw new \Exception("Not enough stock for {$item->variant->product->name}");
                    }
                    $item->variant->decrement('stock', $item->quantity);
                }
            }

            // Clear Cart
            $cart->items()->delete();
            $cart->delete();

            return $order;
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
