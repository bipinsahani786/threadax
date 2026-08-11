<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title'  => 'nullable|string|max:255',
            'body'   => 'required|string|max:1000',
        ]);

        $user = Auth::user();

        // Check if user has already reviewed this product
        $existingReview = Review::where('user_id', $user->id)
                                ->where('product_id', $product->id)
                                ->first();

        if ($existingReview) {
            return back()->with('error', 'You have already reviewed this product.');
        }

        // Check if user has purchased this product and order is delivered
        $hasPurchased = Order::where('user_id', $user->id)
                             ->where('status', 'delivered')
                             ->whereHas('items', function ($query) use ($product) {
                                 $query->whereHas('variant', function ($q) use ($product) {
                                     $q->where('product_id', $product->id);
                                 });
                             })->exists();

        if (! $hasPurchased) {
            return back()->with('error', 'You can only review products you have purchased and received.');
        }

        Review::create([
            'product_id' => $product->id,
            'user_id'    => $user->id,
            'rating'     => $request->rating,
            'title'      => $request->title,
            'body'       => $request->body,
            'status'     => 'pending', // Requires admin approval
        ]);

        return back()->with('success', 'Your review has been submitted and is pending approval.');
    }
}
