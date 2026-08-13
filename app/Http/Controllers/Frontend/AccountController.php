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
        $wishlistCount = $user->wishlists()->count();
        $totalSpent = $user->orders()->where('payment_status', 'paid')->sum('total');
        $recentOrders = $user->orders()->with('items.variant.product.images')->latest()->take(3)->get();

        // Profile completion checklist
        $profileChecks = [
            ['label' => 'Add your full name', 'done' => !empty($user->name), 'route' => 'account.profile'],
            ['label' => 'Add phone number', 'done' => !empty($user->phone), 'route' => 'account.profile'],
            ['label' => 'Add a delivery address', 'done' => $user->addresses()->count() > 0, 'route' => 'account.addresses'],
            ['label' => 'Place your first order', 'done' => $orderCount > 0, 'route' => 'frontend.products.index'],
        ];
        $profileComplete = collect($profileChecks)->every(fn($c) => $c['done']);
        $profilePercent = round(collect($profileChecks)->filter(fn($c) => $c['done'])->count() / count($profileChecks) * 100);

        return view('frontend.pages.account.dashboard', compact(
            'user', 'recentOrder', 'orderCount', 'wishlistCount', 'totalSpent', 'recentOrders',
            'profileChecks', 'profileComplete', 'profilePercent'
        ));
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
        $isDefault = $request->has('is_default');
        
        if ($isDefault) {
            Auth::user()->addresses()->update(['is_default' => false]);
        }

        // If it's the first address, make it default automatically
        if (Auth::user()->addresses()->count() === 0) {
            $isDefault = true;
        }

        Auth::user()->addresses()->create([
            'name'            => $request->name,
            'phone'           => $request->phone,
            'alternate_phone' => $request->alternate_phone,
            'line1'           => $request->line1,
            'line2'           => $request->line2,
            'landmark'        => $request->landmark,
            'city'            => $request->city,
            'state'           => $request->state,
            'pincode'         => $request->pincode,
            'type'            => $request->type ?? 'home',
            'is_default'      => $isDefault,
        ]);

        return redirect()->route('account.addresses')->with('success', 'Address added successfully.');
    }

    public function destroyAddress($id)
    {
        $address = Auth::user()->addresses()->findOrFail($id);
        $address->delete();

        return redirect()->route('account.addresses')->with('success', 'Address removed.');
    }

    public function notifications()
    {
        $notifications = Auth::user()->notifications()->paginate(15);
        return view('frontend.pages.account.notifications', compact('notifications'));
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    }
}
