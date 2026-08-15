<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->query('period', 'last_30_days');
        $paymentStatus = $request->query('payment_status', 'all');
        $orderStatus = $request->query('order_status', 'all');
        $paymentMethod = $request->query('payment_method', 'all');
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

        $query = Order::query();

        // 1. Date Period Filter
        $startDate = null;
        $endDate = Carbon::now()->endOfDay();

        switch ($period) {
            case 'today':
                $startDate = Carbon::today()->startOfDay();
                $query->where('created_at', '>=', $startDate);
                break;
            case 'yesterday':
                $startDate = Carbon::yesterday()->startOfDay();
                $endDate = Carbon::yesterday()->endOfDay();
                $query->whereBetween('created_at', [$startDate, $endDate]);
                break;
            case 'this_week':
                $startDate = Carbon::now()->startOfWeek();
                $query->where('created_at', '>=', $startDate);
                break;
            case 'this_month':
                $startDate = Carbon::now()->startOfMonth();
                $query->where('created_at', '>=', $startDate);
                break;
            case 'this_year':
                $startDate = Carbon::now()->startOfYear();
                $query->where('created_at', '>=', $startDate);
                break;
            case 'custom':
                if ($dateFrom) {
                    $startDate = Carbon::parse($dateFrom)->startOfDay();
                    $query->where('created_at', '>=', $startDate);
                }
                if ($dateTo) {
                    $endDate = Carbon::parse($dateTo)->endOfDay();
                    $query->where('created_at', '<=', $endDate);
                }
                break;
            case 'all_time':
                $startDate = null;
                break;
            case 'last_30_days':
            default:
                $period = 'last_30_days';
                $startDate = Carbon::now()->subDays(29)->startOfDay();
                $query->where('created_at', '>=', $startDate);
                break;
        }

        // 2. Additional Status Filters
        if ($paymentStatus !== 'all') {
            $query->where('payment_status', $paymentStatus);
        }
        if ($orderStatus !== 'all') {
            $query->where('status', $orderStatus);
        }
        if ($paymentMethod !== 'all') {
            $query->where('payment_method', $paymentMethod);
        }

        // Clone base filtered query for metrics calculation
        $filteredOrders = (clone $query)->get();

        // 3. High-Level Executive Metrics
        $grossRevenue = $filteredOrders->where('status', '!=', 'cancelled')->sum('total');
        $paidRevenue = $filteredOrders->where('payment_status', 'paid')->sum('total');
        $codPendingRevenue = $filteredOrders->where('payment_method', 'cod')
            ->where('payment_status', '!=', 'paid')
            ->where('status', '!=', 'cancelled')
            ->sum('total');

        $totalOrdersCount = $filteredOrders->count();
        $deliveredOrdersCount = $filteredOrders->where('status', 'delivered')->count();
        $processingOrdersCount = $filteredOrders->whereIn('status', ['processing', 'shipped'])->count();
        $cancelledOrdersCount = $filteredOrders->where('status', 'cancelled')->count();
        $averageOrderValue = $totalOrdersCount > 0 ? ($grossRevenue / $totalOrdersCount) : 0;

        // Unique Customers
        $uniqueCustomerEmails = $filteredOrders->pluck('shipping_email')->filter()->unique()->count();
        if ($uniqueCustomerEmails === 0) {
            $uniqueCustomerEmails = $filteredOrders->pluck('user_id')->filter()->unique()->count();
        }

        // 4. Payment Method Breakdown (Online vs COD)
        $onlineOrdersCount = $filteredOrders->where('payment_method', '!=', 'cod')->count();
        $codOrdersCount = $filteredOrders->where('payment_method', 'cod')->count();
        $onlineRevenue = $filteredOrders->where('payment_method', '!=', 'cod')->sum('total');
        $codTotalRevenue = $filteredOrders->where('payment_method', 'cod')->sum('total');

        // 5. Dynamic Chart.js Timeline Generation
        $chartLabels = [];
        $chartGrossRevenues = [];
        $chartPaidRevenues = [];
        $chartOrderCounts = [];

        if ($period === 'today' || $period === 'yesterday') {
            // Hourly breakdown (00:00 to 23:00)
            $targetDay = $period === 'today' ? Carbon::today() : Carbon::yesterday();
            for ($h = 0; $h < 24; $h++) {
                $hourLabel = sprintf('%02d:00', $h);
                $chartLabels[] = $hourLabel;
                
                $hourOrders = $filteredOrders->filter(function ($ord) use ($targetDay, $h) {
                    $c = Carbon::parse($ord->created_at);
                    return $c->isSameDay($targetDay) && $c->hour == $h;
                });

                $chartGrossRevenues[] = (float) $hourOrders->where('status', '!=', 'cancelled')->sum('total');
                $chartPaidRevenues[] = (float) $hourOrders->where('payment_status', 'paid')->sum('total');
                $chartOrderCounts[] = $hourOrders->count();
            }
        } elseif ($period === 'this_year' || $period === 'all_time') {
            // Monthly breakdown (Last 12 months)
            for ($m = 11; $m >= 0; $m--) {
                $monthDate = Carbon::now()->subMonths($m);
                $chartLabels[] = $monthDate->format('M Y');

                $monthOrders = $filteredOrders->filter(function ($ord) use ($monthDate) {
                    $c = Carbon::parse($ord->created_at);
                    return $c->year == $monthDate->year && $c->month == $monthDate->month;
                });

                $chartGrossRevenues[] = (float) $monthOrders->where('status', '!=', 'cancelled')->sum('total');
                $chartPaidRevenues[] = (float) $monthOrders->where('payment_status', 'paid')->sum('total');
                $chartOrderCounts[] = $monthOrders->count();
            }
        } else {
            // Daily breakdown (Default: up to 30 days)
            $daysCount = $period === 'this_week' ? 7 : ($period === 'this_month' ? Carbon::now()->day : 30);
            for ($d = $daysCount - 1; $d >= 0; $d--) {
                $dayDate = Carbon::now()->subDays($d);
                $chartLabels[] = $dayDate->format('M d');

                $dayOrders = $filteredOrders->filter(function ($ord) use ($dayDate) {
                    return Carbon::parse($ord->created_at)->isSameDay($dayDate);
                });

                $chartGrossRevenues[] = (float) $dayOrders->where('status', '!=', 'cancelled')->sum('total');
                $chartPaidRevenues[] = (float) $dayOrders->where('payment_status', 'paid')->sum('total');
                $chartOrderCounts[] = $dayOrders->count();
            }
        }

        // 6. Top Performing Drops in Selected Period
        $orderIds = $filteredOrders->pluck('id');
        $topProducts = OrderItem::whereIn('order_id', $orderIds)
            ->with(['variant.product.images'])
            ->select('product_variant_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(total) as total_sales'))
            ->groupBy('product_variant_id')
            ->orderByDesc('total_sales')
            ->take(5)
            ->get();

        // 7. Orders Table Data
        $orders = (clone $query)->with(['user', 'items.variant.product'])->latest()->paginate(15)->withQueryString();

        return view('admin.pages.reports.index', compact(
            'orders',
            'grossRevenue',
            'paidRevenue',
            'codPendingRevenue',
            'totalOrdersCount',
            'deliveredOrdersCount',
            'processingOrdersCount',
            'cancelledOrdersCount',
            'averageOrderValue',
            'uniqueCustomerEmails',
            'onlineOrdersCount',
            'codOrdersCount',
            'onlineRevenue',
            'codTotalRevenue',
            'period',
            'paymentStatus',
            'orderStatus',
            'paymentMethod',
            'dateFrom',
            'dateTo',
            'chartLabels',
            'chartGrossRevenues',
            'chartPaidRevenues',
            'chartOrderCounts',
            'topProducts'
        ));
    }

    public function exportCsv(Request $request)
    {
        $period = $request->query('period', 'last_30_days');
        $query = Order::query();

        switch ($period) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'this_week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'this_month':
                $query->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year);
                break;
            case 'this_year':
                $query->whereYear('created_at', Carbon::now()->year);
                break;
            case 'last_30_days':
            default:
                $query->where('created_at', '>=', Carbon::now()->subDays(30));
                break;
        }

        $orders = $query->with('user')->latest()->get();
        $fileName = "threadax_sales_report_{$period}_" . date('Y-m-d') . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename={$fileName}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Order Number', 'Date & Time', 'Customer Name', 'Customer Email', 'Amount (INR)', 'Payment Method', 'Payment Status', 'Fulfillment Status'];

        $callback = function() use ($orders, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number ?? ('TX-' . $order->id),
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->user ? $order->user->name : ($order->shipping_name ?? 'Guest Customer'),
                    $order->user ? $order->user->email : ($order->shipping_email ?? '-'),
                    $order->total,
                    strtoupper($order->payment_method ?? 'ONLINE'),
                    strtoupper($order->payment_status ?? 'PENDING'),
                    strtoupper($order->status ?? 'PROCESSING'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
