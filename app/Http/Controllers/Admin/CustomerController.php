<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filter = $request->input('filter');
        $sort = $request->input('sort', 'latest');

        $query = User::withCount('orders')
            ->withSum(['orders as total_spent' => function ($q) {
                $q->where('status', '!=', 'cancelled');
            }], 'total');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($filter === 'buyers') {
            $query->has('orders');
        } elseif ($filter === 'non_buyers') {
            $query->doesntHave('orders');
        } elseif ($filter === 'repeat') {
            $query->has('orders', '>=', 2);
        }

        if ($sort === 'spent_high') {
            $query->orderByDesc('total_spent');
        } elseif ($sort === 'orders_high') {
            $query->orderByDesc('orders_count');
        } elseif ($sort === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $customers = $query->paginate(15)->withQueryString();

        // Analytics
        $totalCustomers = User::count();
        $buyersCount = User::has('orders')->count();
        $repeatCount = User::has('orders', '>=', 2)->count();
        $totalLtv = Order::where('status', '!=', 'cancelled')->sum('total');

        $stats = [
            'total' => $totalCustomers,
            'buyers' => $buyersCount,
            'repeat' => $repeatCount,
            'total_ltv' => (float) $totalLtv,
        ];

        return view('admin.pages.customers.index', compact('customers', 'stats', 'search', 'filter', 'sort'));
    }

    public function impersonate(User $user)
    {
        // Save current admin ID to allow return
        session([
            'admin_impersonator_id'   => Auth::guard('admin')->id(),
            'impersonated_user_id'    => $user->id,
            'impersonated_user_name'  => $user->name,
            'impersonated_user_email' => $user->email,
        ]);

        // Login as customer on storefront
        Auth::guard('web')->login($user);

        return redirect()->route('account.dashboard')
            ->with('success', "Logged in as {$user->name} in Customer Impersonation Mode.");
    }

    public function leaveImpersonation()
    {
        Auth::guard('web')->logout();

        session()->forget([
            'admin_impersonator_id',
            'impersonated_user_id',
            'impersonated_user_name',
            'impersonated_user_email',
        ]);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Exited impersonation mode successfully.');
    }

    public function sendEmail(Request $request, User $user)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(
                new \App\Mail\CustomerDirectMail(
                    user: $user,
                    mailSubject: $request->subject,
                    messageBody: $request->message
                )
            );

            return back()->with('success', "Email successfully sent to {$user->name} ({$user->email}).");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }
}
