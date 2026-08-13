<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService $cartService) {}

    public function index()
    {
        try {
            $cart = $this->cartService->getCart();
            $summary = $this->cartService->getSummary();
            
            // Format data for AlpineJS
            $items = $cart->items->map(function($item) {
                return [
                    'id' => $item->id,
                    'variant_id' => $item->product_variant_id,
                    'quantity' => $item->quantity,
                    'price' => $item->variant->effective_price ?? $item->price_at_time,
                    'product_name' => $item->variant->product->name,
                    'product_slug' => $item->variant->product->slug,
                    'size' => $item->variant->size,
                    'color' => $item->variant->color,
                    'image' => $item->variant->product->primary_image->url ?? null
                ];
            });

            return response()->json([
                'success' => true,
                'items' => $items,
                'summary' => $summary
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function add(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            $this->cartService->addItem($request->variant_id, $request->quantity);
            return $this->index(); // Return updated cart
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:cart_items,id',
            'quantity' => 'required|integer|min:0'
        ]);

        try {
            $this->cartService->updateQuantity($request->item_id, $request->quantity);
            if ($request->expectsJson() || $request->ajax()) {
                return $this->index();
            }
            return back()->with('success', 'Cart updated.');
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function remove(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:cart_items,id'
        ]);

        try {
            $this->cartService->removeItem($request->item_id);
            if ($request->expectsJson() || $request->ajax()) {
                return $this->index();
            }
            return back()->with('success', 'Item removed from cart.');
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function moveToWishlist(Request $request, $id)
    {
        $cartItem = \App\Models\CartItem::findOrFail($id);
        
        // Add to wishlist
        if (\Illuminate\Support\Facades\Auth::check()) {
            \Illuminate\Support\Facades\Auth::user()->wishlists()->firstOrCreate([
                'product_id' => $cartItem->variant->product_id
            ]);
        }

        // Remove from cart
        $this->cartService->removeItem($cartItem->id);

        return back()->with('success', 'Item moved to wishlist.');
    }

    public function moveFromWishlist(Request $request, $id)
    {
        if (!\Illuminate\Support\Facades\Auth::check()) {
            return back()->with('error', 'Please login to manage wishlist.');
        }

        $wishlistItem = \Illuminate\Support\Facades\Auth::user()->wishlists()->findOrFail($id);
        $product = $wishlistItem->product;
        
        // We need a variant to add to cart. Let's get the default or first variant.
        $variant = $product->variants->first();

        if ($variant) {
            try {
                $this->cartService->addItem($variant->id, 1);
                $wishlistItem->delete(); // Remove from wishlist
                return back()->with('success', 'Item moved to cart.');
            } catch (\Exception $e) {
                return back()->with('error', $e->getMessage());
            }
        }

        return back()->with('error', 'Product is currently unavailable.');
    }
}
