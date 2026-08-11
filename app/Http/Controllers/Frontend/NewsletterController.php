<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscription;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $subscription = NewsletterSubscription::firstOrCreate(
            ['email' => $request->email],
            ['is_active' => true]
        );

        if (! $subscription->wasRecentlyCreated) {
            if (! $subscription->is_active) {
                $subscription->update(['is_active' => true]);
                return response()->json(['success' => true, 'message' => 'Subscription re-activated successfully!']);
            }
            return response()->json(['success' => false, 'message' => 'You are already subscribed.']);
        }

        return response()->json(['success' => true, 'message' => 'Subscribed successfully!']);
    }
}
