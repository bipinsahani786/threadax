<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\OfferNotification;

class NotificationController extends Controller
{
    public function create()
    {
        return view('admin.pages.notifications.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'link' => 'nullable|url',
            'target' => 'required|in:all_users', // Can be expanded later to specific segments
        ]);

        $users = User::where('role', 'customer')->get();

        foreach ($users as $user) {
            $user->notify(new OfferNotification($request->title, $request->message, $request->link));
        }

        return back()->with('success', 'Notification sent successfully to ' . $users->count() . ' customers.');
    }
}
