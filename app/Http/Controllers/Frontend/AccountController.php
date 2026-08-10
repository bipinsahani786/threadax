<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Address;

class AccountController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $recentOrder = $user->orders()->latest()->first();
        $orderCount = $user->orders()->count();

        return view('frontend.pages.account.dashboard', compact('user', 'recentOrder', 'orderCount'));
    }

    public function orders()
    {
        $orders = Auth::user()->orders()->with('items.variant.product.images')->latest()->paginate(10);
        return view('frontend.pages.account.orders', compact('orders'));
    }

    public function showOrder($id)
    {
        $order = Auth::user()->orders()->with(['items.variant.product.images', 'address', 'payment'])->findOrFail($id);
        return view('frontend.pages.account.order-detail', compact('order'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('frontend.pages.account.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        return redirect()->route('account.profile')->with('success', 'Profile updated successfully.');
    }

    public function addresses()
    {
        $addresses = Auth::user()->addresses;
        return view('frontend.pages.account.addresses', compact('addresses'));
    }

    public function destroyAddress($id)
    {
        $address = Auth::user()->addresses()->findOrFail($id);
        $address->delete();
        
        return redirect()->route('account.addresses')->with('success', 'Address removed.');
    }
}
