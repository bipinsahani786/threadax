@extends('admin.layouts.app')

@section('title', 'Sales Reports')

@section('header')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <h1 class="text-2xl font-bold text-gray-900">Sales Reports</h1>
    
    <div class="flex items-center gap-3">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="flex items-center gap-2">
            <select name="period" onchange="this.form.submit()" class="rounded-md border-gray-300 py-1.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Today</option>
                <option value="this_week" {{ $period == 'this_week' ? 'selected' : '' }}>This Week</option>
                <option value="this_month" {{ $period == 'this_month' ? 'selected' : '' }}>This Month</option>
                <option value="last_30_days" {{ $period == 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
            </select>
        </form>
        
        <a href="{{ route('admin.reports.export', ['period' => $period]) }}" class="btn-secondary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Export CSV
        </a>
    </div>
</div>
@endsection

@section('content')

<!-- Metrics -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Revenue</p>
        <p class="mt-2 text-3xl font-bold text-gray-900">₹{{ number_format($totalRevenue) }}</p>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Orders (Paid)</p>
        <p class="mt-2 text-3xl font-bold text-gray-900">{{ $totalOrders }}</p>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Average Order Value</p>
        <p class="mt-2 text-3xl font-bold text-gray-900">₹{{ number_format($averageOrderValue) }}</p>
    </div>
</div>

<!-- Chart -->
@if(!empty($chartData))
<div class="bg-white rounded-lg shadow p-6 mb-8">
    <h2 class="text-lg font-bold text-gray-900 mb-4">Revenue (Last 30 Days)</h2>
    <div class="h-80 w-full">
        <canvas id="revenueChart"></canvas>
    </div>
</div>
@endif

<!-- Data Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-bold text-gray-900">Orders for Selected Period</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600">
                        <a href="{{ route('admin.orders.show', $order) }}">{{ $order->order_number ?? 'TX-'.$order->id }}</a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $order->created_at->format('M d, Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $order->user ? $order->user->name : $order->shipping_name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        ₹{{ number_format($order->total) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800">
                            Paid
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                        No paid orders found for this period.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
@if(!empty($chartData))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const labels = {!! json_encode($chartData['labels']) !!};
        const revenues = {!! json_encode($chartData['revenues']) !!};
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Revenue (₹)',
                    data: revenues,
                    borderColor: '#4f46e5', // indigo-600
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR' }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₹' + new Intl.NumberFormat('en-IN').format(value);
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endif
@endpush
@endsection
