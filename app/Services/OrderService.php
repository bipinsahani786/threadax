<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\CartService;

class OrderService
{
    public function __construct(private CartService $cartService) {}

    public function createOrder($userId, $addressId, $paymentMethod)
    {
        $cart = $this->cartService->getCart();
        $summary = $this->cartService->getSummary();

        if ($cart->items->isEmpty()) {
            throw new \Exception("Cart is empty");
        }

        return DB::transaction(function () use ($cart, $summary, $userId, $addressId, $paymentMethod) {
            
            // Create Order
            $order = Order::create([
                'user_id' => $userId,
                'order_number' => $this->generateOrderNumber(),
                'address_id' => $addressId,
                'subtotal' => $summary['subtotal'],
                'shipping' => $summary['shipping'],
                'tax' => 0, // Tax included in price for now
                'total' => $summary['total'],
                'status' => 'pending',
                'payment_method' => $paymentMethod,
                'payment_status' => 'pending'
            ]);

            // Create Order Items and Deduct Stock
            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_variant_id' => $item->product_variant_id,
                    'quantity' => $item->quantity,
                    'price' => $item->variant->price ?? $item->price_at_time,
                    'total' => $item->quantity * ($item->variant->price ?? $item->price_at_time)
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
