<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');
        
        $reviews = Review::with(['product', 'user'])
                         ->when($status !== 'all', function ($query) use ($status) {
                             $query->where('status', $status);
                         })
                         ->latest()
                         ->paginate(15);
                         
        return view('admin.pages.reviews.index', compact('reviews', 'status'));
    }

    public function updateStatus(Request $request, Review $review)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $review->update(['status' => $request->status]);

        return back()->with('success', 'Review status updated to ' . $request->status . '.');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review deleted.');
    }
}
