<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use App\Models\Order;
use App\Http\Requests\Frontend\StoreReviewRequest;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(StoreReviewRequest $request, Product $product)
    {
        $user = Auth::user();

        // 1. Check if user has purchased this product and order is delivered
        $hasPurchased = $user->orders()
            ->where('status', 'delivered')
            ->whereHas('items.variant', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->exists();

        // Check if user has already reviewed this product
        $existingReview = Review::where('user_id', $user->id)
                                ->where('product_id', $product->id)
                                ->first();

        if ($existingReview) {
            return back()->with('error', 'You have already reviewed this product.');
        }

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
