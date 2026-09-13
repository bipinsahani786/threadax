<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CartService
{
    const GUEST_COOKIE_NAME = 'threadax_guest_cart_token';

    /**
     * Get the current cart for the user or guest session
     */
    public function getCart()
    {
        $userId = Auth::id();
        $sessionId = Session::getId();
        $cookieToken = request()->cookie(self::GUEST_COOKIE_NAME);

        if ($userId) {
            // Merge any guest cart that was created before login
            $cart = $this->mergeGuestCart($userId, $sessionId, $cookieToken);
            return $this->cleanOrphanedItems($cart);
        }

        // ── GUEST CART HANDLING ──
        $cart = null;

        // 1. Try finding by current session_id
        if ($sessionId) {
            $cart = Cart::where('session_id', $sessionId)->whereNull('user_id')->first();
        }

        // 2. Try finding by persistent guest cookie token if session_id changed
        if (!$cart && $cookieToken) {
            $cart = Cart::where('session_id', $cookieToken)->whereNull('user_id')->first();
            if ($cart && $sessionId) {
                // Re-link to current session_id
                $cart->update(['session_id' => $sessionId]);
            }
        }

        // 3. Create guest cart if not exists
        if (!$cart) {
            $cart = Cart::create([
                'session_id' => $sessionId,
                'user_id'    => null,
            ]);
        }

        // Persist guest session ID and cookie for seamless migration upon login
        if ($sessionId) {
            session(['guest_cart_session_id' => $sessionId]);
            Cookie::queue(self::GUEST_COOKIE_NAME, $sessionId, 60 * 24 * 30); // 30 days
        }

        return $this->cleanOrphanedItems($cart);
    }

    /**
     * Merge guest cart items into authenticated user's cart upon login.
     */
    public function mergeGuestCart(int $userId, ?string $guestSessionId = null, ?string $cookieToken = null): Cart
    {
        $userCart = Cart::firstOrCreate(['user_id' => $userId]);

        // Candidates for guest session IDs
        $sessionCandidates = array_filter(array_unique([
            $guestSessionId,
            session('guest_cart_session_id'),
            $cookieToken,
            request()->cookie(self::GUEST_COOKIE_NAME),
            Session::getId(),
        ]));

        if (!empty($sessionCandidates)) {
            // Find all unassigned guest carts matching candidate session IDs
            $guestCarts = Cart::whereIn('session_id', $sessionCandidates)
                ->whereNull('user_id')
                ->with(['items.variant.product'])
                ->get();

            foreach ($guestCarts as $guestCart) {
                foreach ($guestCart->items as $guestItem) {
                    if (!$guestItem->variant || !$guestItem->variant->product) {
                        try {
                            $guestItem->delete();
                        } catch (\Exception $e) {}
                        continue;
                    }

                    $existingItem = $userCart->items()
                        ->where('product_variant_id', $guestItem->product_variant_id)
                        ->first();

                    $maxStock = (int) ($guestItem->variant->stock ?? 0);
                    $effectivePrice = $guestItem->variant->effective_price ?? $guestItem->price_at_time ?? 0;

                    if ($existingItem) {
                        // Merge quantity, capped at available variant stock
                        $mergedQty = min($existingItem->quantity + $guestItem->quantity, max(1, $maxStock));
                        $existingItem->update([
                            'quantity'      => $mergedQty,
                            'price_at_time' => $effectivePrice,
                        ]);
                    } else {
                        // Reassign item or create in user cart
                        $userCart->items()->create([
                            'product_variant_id' => $guestItem->product_variant_id,
                            'quantity'           => min($guestItem->quantity, max(1, $maxStock)),
                            'price_at_time'      => $effectivePrice,
                        ]);
                    }
                }

                // Delete the merged guest cart & its items
                try {
                    $guestCart->items()->delete();
                    $guestCart->delete();
                } catch (\Exception $e) {
                    Log::warning("Could not delete merged guest cart ID {$guestCart->id}: " . $e->getMessage());
                }
            }
        }

        // Clear guest session memory & expire cookie
        session()->forget('guest_cart_session_id');
        Cookie::queue(Cookie::forget(self::GUEST_COOKIE_NAME));

        return $userCart->fresh(['items.variant.product.images']);
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
        $variant = ProductVariant::with('product')->findOrFail($variantId);
        
        $item = $cart->items()->where('product_variant_id', $variantId)->first();
        $effectivePrice = $variant->effective_price ?? $variant->price ?? 0;

        if ($item) {
            $newQuantity = $item->quantity + $quantity;
            // Check stock
            if ($newQuantity > $variant->stock) {
                throw new \Exception("Only {$variant->stock} items available in stock.");
            }
            $item->update([
                'quantity'      => $newQuantity,
                'price_at_time' => $effectivePrice // update price to latest
            ]);
        } else {
            if ($quantity > $variant->stock) {
                throw new \Exception("Only {$variant->stock} items available in stock.");
            }
            $cart->items()->create([
                'product_variant_id' => $variantId,
                'quantity'           => $quantity,
                'price_at_time'      => $effectivePrice
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
            $variantStock = $item->variant?->stock ?? 0;
            if ($variantStock > 0 && $quantity > $variantStock) {
                throw new \Exception("Only {$variantStock} items available in stock.");
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
            $unitPrice = $item->variant?->effective_price ?? $item->price_at_time ?? 0;
            $subtotal += $item->quantity * $unitPrice;
        }

        // Fetch shipping threshold & standard shipping charge dynamically from Settings
        $freeShippingThreshold  = (float) \App\Models\Setting::get('free_shipping_threshold', 999);
        $standardShippingCharge = (float) \App\Models\Setting::get('standard_shipping_charge', 50);

        // If cart is empty, shipping is 0; otherwise charge standard shipping below threshold
        $shipping = ($subtotal > 0 && $subtotal < $freeShippingThreshold) ? $standardShippingCharge : 0;
        $total    = $subtotal + $shipping;

        // Note: GST is included in the listed price (not added on top)

        return [
            'item_count'               => $cart->items->sum('quantity'),
            'subtotal'                 => round($subtotal, 2),
            'shipping'                 => round($shipping, 2),
            'free_shipping_threshold'  => $freeShippingThreshold,
            'standard_shipping_charge' => $standardShippingCharge,
            'total'                    => round($total, 2),
        ];
    }

    /**
     * Remove any cart items whose product variant or parent product has been deleted.
     */
    private function cleanOrphanedItems(Cart $cart): Cart
    {
        $cart->loadMissing(['items.variant.product.images']);

        $invalidItemIds = $cart->items->filter(function ($item) {
            return !$item->variant || !$item->variant->product;
        })->pluck('id');

        if ($invalidItemIds->isNotEmpty()) {
            $cart->items()->whereIn('id', $invalidItemIds)->delete();
            return $cart->fresh(['items.variant.product.images']);
        }

        return $cart;
    }

    /**
     * Clear all items and delete cart for given user or current session.
     */
    public function clearCart(?int $userId = null): void
    {
        $cart = null;
        if ($userId) {
            $cart = Cart::where('user_id', $userId)->first();
        } else {
            $cart = $this->getCart();
        }

        if ($cart) {
            $cart->items()->delete();
            $cart->delete();
        }
    }
}
