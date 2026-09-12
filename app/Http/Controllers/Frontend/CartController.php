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
            $items = $cart->items
                ->filter(fn($item) => $item->variant && $item->variant->product)
                ->values()
                ->map(function($item) {
                    $variant = $item->variant;
                    $product = $variant->product;
                    $img = $product->primaryImage ?? $product->images->first();

                    return [
                        'id'           => $item->id,
                        'variant_id'   => $item->product_variant_id,
                        'quantity'     => $item->quantity,
                        'price'        => $variant->effective_price ?? $item->price_at_time ?? 0,
                        'product_name' => $product->name,
                        'product_slug' => $product->slug,
                        'size'         => $variant->size,
                        'color'        => $variant->color,
                        'image'        => $img?->url ?? null,
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
            if ($request->expectsJson() || $request->ajax() || $request->isJson()) {
                return $this->index();
            }
            return back()->with('success', 'Cart updated.');
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax() || $request->isJson()) {
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
            if ($request->expectsJson() || $request->ajax() || $request->isJson()) {
                return $this->index();
            }
            return back()->with('success', 'Item removed from cart.');
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax() || $request->isJson()) {
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
            if ($request->expectsJson() || $request->isJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Please login to manage your wishlist.'], 401);
            }
            return back()->with('error', 'Please login to manage wishlist.');
        }

        $wishlistItem = \Illuminate\Support\Facades\Auth::user()->wishlists()->findOrFail($id);
        $product = $wishlistItem->product;

        if (!$product) {
            $wishlistItem->delete();
            if ($request->expectsJson() || $request->isJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'This product is no longer available.'], 404);
            }
            return back()->with('error', 'This product is no longer available.');
        }
        
        // Check if a specific variant was selected, otherwise pick first in-stock or default variant
        $variantId = $request->input('variant_id');
        $variant = null;
        if ($variantId) {
            $variant = $product->variants()->where('id', $variantId)->first();
        }
        if (!$variant) {
            $variant = $product->variants()->where('stock', '>', 0)->first() ?? $product->variants()->first();
        }

        if ($variant) {
            try {
                $this->cartService->addItem($variant->id, 1);
                $wishlistItem->delete(); // Remove from wishlist

                if ($request->expectsJson() || $request->isJson() || $request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Item successfully moved to your bag!',
                        'wishlist_count' => \Illuminate\Support\Facades\Auth::user()->wishlists()->count()
                    ]);
                }
                return back()->with('success', 'Item moved to bag.');
            } catch (\Exception $e) {
                if ($request->expectsJson() || $request->isJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
                }
                return back()->with('error', $e->getMessage());
            }
        }

        if ($request->expectsJson() || $request->isJson() || $request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Product is currently unavailable.'], 404);
        }
        return back()->with('error', 'Product is currently unavailable.');
    }
}
