<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        // Stats will be wired to real DB data in Phase 4
        $stats = [
            'total_orders'    => 0,
            'total_revenue'   => 0,
            'total_products'  => \App\Models\Product::count(),
            'total_customers' => \App\Models\User::count(),
        ];

        return view('admin.pages.dashboard.index', compact('stats'));
    }
}
