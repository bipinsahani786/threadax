<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;
use App\Models\Product;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Auth::user()->wishlists()->with(['product.images', 'product.variants'])->latest()->paginate(12);
        return view('frontend.pages.account.wishlist', compact('wishlists'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $user = Auth::user();
        $productId = $request->product_id;

        $wishlist = $user->wishlists()->where('product_id', $productId)->first();

        if ($wishlist) {
            $wishlist->delete();
            $status = 'removed';
            $message = 'Product removed from wishlist.';
        } else {
            $user->wishlists()->create([
                'product_id' => $productId
            ]);
            $status = 'added';
            $message = 'Product added to wishlist.';
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => $status,
                'message' => $message,
                'count' => $user->wishlists()->count()
            ]);
        }

        return back()->with('success', $message);
    }
}
