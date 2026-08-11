<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->query('period', 'last_30_days');
        
        $query = Order::where('payment_status', 'paid');
        
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
            case 'last_30_days':
            default:
                $query->where('created_at', '>=', Carbon::now()->subDays(30));
                break;
        }

        $orders = $query->latest()->get();
        
        $totalRevenue = $orders->sum('total');
        $totalOrders = $orders->count();
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Chart Data (Group by date for last 30 days)
        $chartData = [];
        if ($period == 'last_30_days') {
            $dailyOrders = Order::where('payment_status', 'paid')
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->selectRaw('DATE(created_at) as date, SUM(total) as revenue, COUNT(id) as orders')
                ->groupBy('date')
                ->orderBy('date')
                ->get();
            
            $labels = [];
            $revenues = [];
            $orderCounts = [];
            
            // Fill missing days with 0
            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->format('Y-m-d');
                $labels[] = Carbon::parse($date)->format('M d');
                
                $dayData = $dailyOrders->firstWhere('date', $date);
                $revenues[] = $dayData ? $dayData->revenue : 0;
                $orderCounts[] = $dayData ? $dayData->orders : 0;
            }
            
            $chartData = [
                'labels' => $labels,
                'revenues' => $revenues,
                'orders' => $orderCounts,
            ];
        }

        return view('admin.pages.reports.index', compact('orders', 'totalRevenue', 'totalOrders', 'averageOrderValue', 'period', 'chartData'));
    }

    public function exportCsv(Request $request)
    {
        $period = $request->query('period', 'last_30_days');
        
        $query = Order::where('payment_status', 'paid');
        
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
            case 'last_30_days':
            default:
                $query->where('created_at', '>=', Carbon::now()->subDays(30));
                break;
        }

        $orders = $query->with('user')->latest()->get();

        $fileName = "sales_report_{$period}_" . date('Y-m-d') . ".csv";
        
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = ['Order Number', 'Date', 'Customer', 'Amount', 'Status', 'Payment Method'];

        $callback = function() use($orders, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($orders as $order) {
                $row['Order Number']  = $order->order_number;
                $row['Date']    = $order->created_at->format('Y-m-d H:i');
                $row['Customer']    = $order->user ? $order->user->name : $order->shipping_name;
                $row['Amount']  = $order->total;
                $row['Status']  = $order->status;
                $row['Payment Method']  = $order->payment_method;

                fputcsv($file, array($row['Order Number'], $row['Date'], $row['Customer'], $row['Amount'], $row['Status'], $row['Payment Method']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
