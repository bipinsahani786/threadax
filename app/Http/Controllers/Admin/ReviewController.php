<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        $rating = $request->query('rating');
        $search = $request->query('search');
        
        $query = Review::with(['product.primaryImage', 'user'])->latest();

        if ($status !== 'all' && in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        if (!empty($rating) && is_numeric($rating)) {
            $query->where('rating', (int) $rating);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($qu) => $qu->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
                  ->orWhereHas('product', fn($qp) => $qp->where('name', 'like', "%{$search}%"));
            });
        }

        $reviews = $query->paginate(15)->withQueryString();

        // Analytics
        $totalReviews = Review::count();
        $approvedCount = Review::where('status', 'approved')->count();
        $pendingCount = Review::where('status', 'pending')->count();
        $rejectedCount = Review::where('status', 'rejected')->count();
        $avgRating = Review::where('status', 'approved')->avg('rating') ?? Review::avg('rating') ?? 5.0;

        $stats = [
            'total' => $totalReviews,
            'approved' => $approvedCount,
            'pending' => $pendingCount,
            'rejected' => $rejectedCount,
            'avg_rating' => round($avgRating, 1),
        ];
                     
        return view('admin.pages.reviews.index', compact('reviews', 'stats', 'status', 'rating', 'search'));
    }

    public function updateStatus(Request $request, Review $review)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $review->update(['status' => $request->status]);

        return back()->with('success', 'Review status updated to ' . ucfirst($request->status) . '.');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review deleted successfully.');
    }
}
