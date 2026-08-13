<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ContactLead;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'order_number' => 'nullable|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        ContactLead::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for contacting us. We will get back to you shortly!'
        ]);
    }
}
