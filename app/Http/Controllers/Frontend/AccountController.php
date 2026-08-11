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

    public function downloadInvoice($id)
    {
        $order = Auth::user()->orders()->with(['items.variant.product', 'address'])->findOrFail($id);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.pages.orders.invoice', compact('order'));
        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('frontend.pages.account.profile', compact('user'));
    }

    public function updateProfile(\App\Http\Requests\Frontend\ProfileUpdateRequest $request)
    {
        Auth::user()->update([
            'name'  => $request->name,
            'phone' => $request->phone,
        ]);

        return redirect()->route('account.profile')->with('success', 'Profile updated successfully.');
    }

    public function addresses()
    {
        $addresses = Auth::user()->addresses;
        return view('frontend.pages.account.addresses', compact('addresses'));
    }

    public function reviews()
    {
        $reviews = Auth::user()->reviews()->with('product')->latest()->paginate(10);
        return view('frontend.pages.account.reviews', compact('reviews'));
    }

    public function storeAddress(\App\Http\Requests\Frontend\StoreAddressRequest $request)
    {
        Auth::user()->addresses()->create([
            'name'    => $request->name,
            'phone'   => $request->phone,
            'street'  => $request->street,
            'city'    => $request->city,
            'state'   => $request->state,
            'pincode' => $request->pincode,
            'type'    => $request->type ?? 'home',
        ]);

        return redirect()->route('account.addresses')->with('success', 'Address added successfully.');
    }

    public function destroyAddress($id)
    {
        $address = Auth::user()->addresses()->findOrFail($id);
        $address->delete();

        return redirect()->route('account.addresses')->with('success', 'Address removed.');
    }
}
