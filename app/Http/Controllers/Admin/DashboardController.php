<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        // ── 1. Top Level Key Stats ──────────────────────────────────────────
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total');
        $totalProducts = Product::count();
        $totalCustomers = User::count();
        
        $todayOrders = Order::whereDate('created_at', today())->count();
        $todayRevenue = Order::whereDate('created_at', today())->where('status', '!=', 'cancelled')->sum('total');
        $pendingOrders = Order::whereIn('status', ['pending', 'processing'])->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $lowStockCount = ProductVariant::where('stock', '<=', 5)->count();

        $stats = [
            'total_orders'    => $totalOrders,
            'total_revenue'   => $totalRevenue,
            'total_products'  => $totalProducts,
            'total_customers' => $totalCustomers,
            'today_orders'    => $todayOrders,
            'today_revenue'   => $todayRevenue,
            'pending_orders'  => $pendingOrders,
            'delivered_orders'=> $deliveredOrders,
            'low_stock_count' => $lowStockCount,
        ];

        // ── 2. Sales & Revenue 7-Day Trend (Chart 1) ───────────────────────
        $chartDates = [];
        $chartRevenue = [];
        $chartOrders = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            $chartDates[] = $date->format('d M');
            $chartRevenue[] = (float) Order::whereDate('created_at', $dateStr)->where('status', '!=', 'cancelled')->sum('total');
            $chartOrders[] = (int) Order::whereDate('created_at', $dateStr)->count();
        }

        // ── 3. Order Status Breakdown (Chart 2) ─────────────────────────────
        $statusCounts = [
            'Processing' => Order::whereIn('status', ['pending', 'processing'])->count(),
            'Confirmed'  => Order::where('status', 'confirmed')->count(),
            'Shipped'    => Order::where('status', 'shipped')->count(),
            'Delivered'  => Order::where('status', 'delivered')->count(),
            'Cancelled'  => Order::where('status', 'cancelled')->count(),
        ];

        // ── 4. Payment Methods Breakdown ───────────────────────────────────
        $paymentStats = [
            'online_count'   => Order::where('payment_method', 'razorpay')->count(),
            'cod_count'      => Order::where('payment_method', 'cod')->count(),
            'online_revenue' => Order::where('payment_method', 'razorpay')->where('status', '!=', 'cancelled')->sum('total'),
            'cod_revenue'    => Order::where('payment_method', 'cod')->where('status', '!=', 'cancelled')->sum('total'),
        ];

        // ── 5. Recent Orders (Latest 6) ────────────────────────────────────
        $recentOrders = Order::with(['items.variant.product.images', 'user', 'address'])
            ->latest()
            ->take(6)
            ->get();

        // ── 6. Low Stock Alerts (Stock <= 5) ───────────────────────────────
        $lowStockVariants = ProductVariant::with('product.images')
            ->where('stock', '<=', 5)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        // ── 7. Top Selling Products ────────────────────────────────────────
        $topProducts = OrderItem::select('product_variant_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(total) as total_sales'))
            ->with('variant.product.images')
            ->whereHas('order', fn($q) => $q->where('status', '!=', 'cancelled'))
            ->groupBy('product_variant_id')
            ->orderByDesc('total_qty')
            ->take(4)
            ->get();

        return view('admin.pages.dashboard.index', compact(
            'stats',
            'chartDates',
            'chartRevenue',
            'chartOrders',
            'statusCounts',
            'paymentStats',
            'recentOrders',
            'lowStockVariants',
            'topProducts'
        ));
    }

    /**
     * Mark all admin topbar notifications as read
     */
    public function markNotificationsRead(\Illuminate\Http\Request $request)
    {
        session(['admin_notifications_last_read' => now()]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Notifications marked as read.');
    }
}
