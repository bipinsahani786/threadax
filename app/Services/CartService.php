<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    /**
     * Get the current cart for the user or guest session
     */
    public function getCart()
    {
        $userId = Auth::id();
        $sessionId = Session::getId();

        $query = Cart::with(['items.variant.product.images']);

        if ($userId) {
            $cart = $query->where('user_id', $userId)->first();
            
            // If user has a session cart and just logged in, merge it
            $sessionCart = Cart::where('session_id', $sessionId)->whereNull('user_id')->first();
            
            if ($sessionCart) {
                if ($cart) {
                    // Merge session cart items into user cart
                    foreach ($sessionCart->items as $item) {
                        $this->addItemToCart($cart, $item->product_variant_id, $item->quantity);
                    }
                    $sessionCart->delete();
                } else {
                    // Assign session cart to user
                    $sessionCart->update(['user_id' => $userId]);
                    $cart = $sessionCart;
                }
            }

            if (!$cart) {
                $cart = Cart::create(['user_id' => $userId]);
            }
        } else {
            $cart = $query->firstOrCreate(
                ['session_id' => $sessionId],
                ['user_id' => null]
            );
        }

        return $cart;
    }

    /**
     * Add an item to the cart
     */
    public function addItem($variantId, $quantity = 1)
    {
        $cart = $this->getCart();
        return $this->addItemToCart($cart, $variantId, $quantity);
    }

    private function addItemToCart(Cart $cart, $variantId, $quantity)
    {
        $variant = ProductVariant::findOrFail($variantId);
        
        $item = $cart->items()->where('product_variant_id', $variantId)->first();

        if ($item) {
            $newQuantity = $item->quantity + $quantity;
            // Check stock
            if ($newQuantity > $variant->stock) {
                throw new \Exception("Not enough stock available.");
            }
            $item->update([
                'quantity' => $newQuantity,
                'price_at_time' => $variant->effective_price // update price to latest
            ]);
        } else {
            if ($quantity > $variant->stock) {
                throw new \Exception("Not enough stock available.");
            }
            $cart->items()->create([
                'product_variant_id' => $variantId,
                'quantity' => $quantity,
                'price_at_time' => $variant->effective_price
            ]);
        }

        return $cart->fresh(['items.variant.product.images']);
    }

    /**
     * Update cart item quantity
     */
    public function updateQuantity($itemId, $quantity)
    {
        $cart = $this->getCart();
        $item = $cart->items()->where('id', $itemId)->firstOrFail();
        
        if ($quantity <= 0) {
            $item->delete();
        } else {
            if ($quantity > $item->variant->stock) {
                throw new \Exception("Not enough stock available.");
            }
            $item->update(['quantity' => $quantity]);
        }

        return $cart->fresh(['items.variant.product.images']);
    }

    /**
     * Remove item from cart
     */
    public function removeItem($itemId)
    {
        $cart = $this->getCart();
        $cart->items()->where('id', $itemId)->delete();
        
        return $cart->fresh(['items.variant.product.images']);
    }

    /**
     * Get cart summary (totals)
     */
    public function getSummary(): array
    {
        $cart = $this->getCart();

        $subtotal = 0;
        foreach ($cart->items as $item) {
            $subtotal += $item->quantity * ($item->variant->effective_price ?? $item->price_at_time);
        }

        // Free shipping on orders above ₹999
        $shipping = $subtotal >= 999 ? 0 : 50;
        $total    = $subtotal + $shipping;

        // Note: GST is included in the listed price (not added on top)

        return [
            'item_count' => $cart->items->sum('quantity'),
            'subtotal'   => round($subtotal, 2),
            'shipping'   => $shipping,
            'total'      => round($total, 2),
        ];
    }
}
