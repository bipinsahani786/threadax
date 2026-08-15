@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Overview')

@section('content')

{{-- 1. WELCOME HERO BANNER --}}
<div class="relative overflow-hidden rounded-3xl p-6 sm:p-8 mb-8 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 text-white shadow-xl border border-slate-800">
    <div class="absolute -top-16 -right-16 w-64 h-64 rounded-full bg-emerald-500/10 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-16 right-48 w-56 h-56 rounded-full bg-blue-500/10 blur-2xl pointer-events-none"></div>
    
    <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/10 backdrop-blur-md mb-3 text-xs font-semibold text-white/90">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>ThreadAx Control Center • {{ now()->format('l, d M Y') }}</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-heading font-black tracking-tight text-white">
                Welcome back, {{ explode(' ', Auth::guard('admin')->user()->name ?? 'Admin')[0] }}! 👋
            </h2>
            <p class="text-white/60 text-xs sm:text-sm mt-1 max-w-xl leading-relaxed">
                Real-time performance overview of your streetwear store. Track orders, revenue growth, and live customer activity.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <a href="{{ route('admin.products.create') }}" class="flex-1 md:flex-initial inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-slate-900 bg-white hover:bg-slate-100 transition-all shadow-md active:scale-95 cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                <span>+ Add Product</span>
            </a>
            <a href="{{ route('admin.reports.index') }}" class="flex-1 md:flex-initial inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-white/10 hover:bg-white/20 border border-white/15 backdrop-blur-md transition-all active:scale-95 cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                <span>Analytics Report</span>
            </a>
        </div>
    </div>
</div>

{{-- 2. KEY METRIC STATS (4-GRID) --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6 mb-8">
    
    {{-- Revenue Card --}}
    <div class="bg-white rounded-2xl p-5 sm:p-6 shadow-xs border border-slate-200/80 hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Revenue</span>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                ₹
            </div>
        </div>
        <p class="text-2xl sm:text-3xl font-heading font-black text-slate-900 tracking-tight">₹{{ number_format($stats['total_revenue']) }}</p>
        <div class="mt-2.5 flex items-center gap-1.5 text-xs">
            <span class="font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">Today: ₹{{ number_format($stats['today_revenue']) }}</span>
            <span class="text-slate-400">from active orders</span>
        </div>
    </div>

    {{-- Total Orders Card --}}
    <div class="bg-white rounded-2xl p-5 sm:p-6 shadow-xs border border-slate-200/80 hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Orders</span>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/></svg>
            </div>
        </div>
        <p class="text-2xl sm:text-3xl font-heading font-black text-slate-900 tracking-tight">{{ number_format($stats['total_orders']) }}</p>
        <div class="mt-2.5 flex items-center gap-1.5 text-xs">
            <span class="font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md">{{ $stats['pending_orders'] }} Processing</span>
            <span class="text-slate-400">• {{ $stats['today_orders'] }} today</span>
        </div>
    </div>

    {{-- Products Catalog Card --}}
    <div class="bg-white rounded-2xl p-5 sm:p-6 shadow-xs border border-slate-200/80 hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Catalog Products</span>
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
            </div>
        </div>
        <p class="text-2xl sm:text-3xl font-heading font-black text-slate-900 tracking-tight">{{ number_format($stats['total_products']) }}</p>
        <div class="mt-2.5 flex items-center gap-1.5 text-xs">
            @if($stats['low_stock_count'] > 0)
                <span class="font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md">⚠️ {{ $stats['low_stock_count'] }} Low Stock</span>
            @else
                <span class="font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">All In Stock</span>
            @endif
        </div>
    </div>

    {{-- Customers Card --}}
    <div class="bg-white rounded-2xl p-5 sm:p-6 shadow-xs border border-slate-200/80 hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Registered Users</span>
            <div class="w-10 h-10 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
            </div>
        </div>
        <p class="text-2xl sm:text-3xl font-heading font-black text-slate-900 tracking-tight">{{ number_format($stats['total_customers']) }}</p>
        <div class="mt-2.5 flex items-center gap-1.5 text-xs text-slate-500">
            <span class="font-bold text-slate-800">100%</span>
            <span>OTP & Google verified</span>
        </div>
    </div>

</div>

{{-- 3. INTERACTIVE CHARTS ROW (7-DAY TREND + ORDER STATUS BREAKDOWN) --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    {{-- Left Chart (2/3 width): Sales & Revenue 7-Day Trend --}}
    <div class="lg:col-span-2 bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div>
                <h3 class="text-base font-heading font-extrabold text-slate-900">Revenue & Sales Performance</h3>
                <p class="text-xs text-slate-400 mt-0.5">Last 7 days revenue generated and orders placed</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1 text-xs font-bold text-slate-700 bg-slate-100 px-2.5 py-1 rounded-lg">
                    <span class="w-2.5 h-2.5 rounded-full bg-slate-900"></span> Revenue (₹)
                </span>
                <span class="inline-flex items-center gap-1 text-xs font-bold text-slate-700 bg-slate-100 px-2.5 py-1 rounded-lg">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> Orders
                </span>
            </div>
        </div>
        <div class="relative w-full h-[280px]">
            <canvas id="salesTrendChart"></canvas>
        </div>
    </div>

    {{-- Right Chart (1/3 width): Order Status Distribution --}}
    <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-heading font-extrabold text-slate-900">Order Fulfillment</h3>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Live Status</span>
            </div>
            <p class="text-xs text-slate-400 mb-4">Distribution of total active and completed orders</p>
            
            <div class="relative w-full h-[180px] flex items-center justify-center mb-4">
                <canvas id="orderStatusChart"></canvas>
            </div>
        </div>

        {{-- Status Breakdown Pills --}}
        <div class="grid grid-cols-2 gap-2 text-xs pt-4 border-t border-slate-100">
            <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50">
                <span class="flex items-center gap-1.5 text-slate-600 font-medium">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span> Processing
                </span>
                <span class="font-bold text-slate-900">{{ $statusCounts['Processing'] }}</span>
            </div>
            <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50">
                <span class="flex items-center gap-1.5 text-slate-600 font-medium">
                    <span class="w-2 h-2 rounded-full bg-sky-500"></span> Confirmed
                </span>
                <span class="font-bold text-slate-900">{{ $statusCounts['Confirmed'] }}</span>
            </div>
            <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50">
                <span class="flex items-center gap-1.5 text-slate-600 font-medium">
                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span> Shipped
                </span>
                <span class="font-bold text-slate-900">{{ $statusCounts['Shipped'] }}</span>
            </div>
            <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50">
                <span class="flex items-center gap-1.5 text-slate-600 font-medium">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Delivered
                </span>
                <span class="font-bold text-slate-900">{{ $statusCounts['Delivered'] }}</span>
            </div>
        </div>
    </div>

</div>

{{-- 4. PAYMENT METHOD BREAKDOWN & OPERATIONAL OVERVIEW --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    
    {{-- Razorpay / Online Payments --}}
    <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <span class="text-xl">💳</span>
                <h4 class="text-sm font-extrabold font-heading text-slate-900">Razorpay (Online)</h4>
            </div>
            <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">Instant</span>
        </div>
        <p class="text-2xl font-heading font-black text-slate-900">₹{{ number_format($paymentStats['online_revenue']) }}</p>
        <p class="text-xs text-slate-400 mt-1">{{ $paymentStats['online_count'] }} successful online transactions</p>
    </div>

    {{-- COD Payments --}}
    <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <span class="text-xl">💵</span>
                <h4 class="text-sm font-extrabold font-heading text-slate-900">Cash on Delivery (COD)</h4>
            </div>
            <span class="text-xs font-bold text-slate-600 bg-slate-100 px-2.5 py-0.5 rounded-full">Upon Delivery</span>
        </div>
        <p class="text-2xl font-heading font-black text-slate-900">₹{{ number_format($paymentStats['cod_revenue']) }}</p>
        <p class="text-xs text-slate-400 mt-1">{{ $paymentStats['cod_count'] }} cash on delivery orders</p>
    </div>

    {{-- Order Fulfillment Rate --}}
    <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <span class="text-xl">🚀</span>
                <h4 class="text-sm font-extrabold font-heading text-slate-900">Delivery Success</h4>
            </div>
            <span class="text-xs font-bold text-blue-700 bg-blue-50 px-2.5 py-0.5 rounded-full border border-blue-200">Efficiency</span>
        </div>
        @php
            $successRate = $stats['total_orders'] > 0 ? round(($stats['delivered_orders'] / $stats['total_orders']) * 100) : 100;
        @endphp
        <p class="text-2xl font-heading font-black text-slate-900">{{ $successRate }}% Delivered</p>
        <p class="text-xs text-slate-400 mt-1">{{ $stats['delivered_orders'] }} of {{ $stats['total_orders'] }} orders fully fulfilled</p>
    </div>

</div>

{{-- 5. RECENT ORDERS TABLE & LOW STOCK ALERTS --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    {{-- Recent Orders (2/3 width) --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs">
        <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div>
                <h3 class="text-base font-heading font-extrabold text-slate-900">Recent Customer Orders</h3>
                <p class="text-xs text-slate-400 mt-0.5">Latest purchase activity across your storefront</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-slate-900 hover:underline flex items-center gap-1">
                <span>View All Orders</span>
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>

        @if($recentOrders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-3 px-4">Order</th>
                            <th class="py-3 px-4">Customer</th>
                            <th class="py-3 px-4">Amount</th>
                            <th class="py-3 px-4">Payment</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @foreach($recentOrders as $rOrder)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 px-4">
                                    <span class="font-mono font-bold text-slate-900 block">#{{ $rOrder->order_number ?? 'TX-'.$rOrder->id }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $rOrder->created_at->format('d M, h:i A') }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-slate-900 block">{{ $rOrder->user->name ?? $rOrder->address->name ?? 'Customer' }}</span>
                                    <span class="text-[10px] text-slate-400 block truncate max-w-[140px]">{{ $rOrder->user->email ?? 'Guest' }}</span>
                                </td>
                                <td class="py-3.5 px-4 font-heading font-extrabold text-slate-900">
                                    ₹{{ number_format($rOrder->total, 2) }}
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($rOrder->payment_method === 'cod')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">COD</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Paid Online</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    @php $st = strtolower($rOrder->status); @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold capitalize
                                        @if($st === 'delivered') bg-emerald-50 text-emerald-700
                                        @elseif($st === 'cancelled') bg-rose-50 text-rose-700
                                        @elseif($st === 'shipped') bg-indigo-50 text-indigo-700
                                        @elseif($st === 'confirmed') bg-sky-50 text-sky-700
                                        @else bg-amber-50 text-amber-700 @endif
                                    ">
                                        {{ $rOrder->status }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <a href="{{ route('admin.orders.show', $rOrder->id) }}" class="inline-flex items-center gap-1 font-bold text-slate-900 bg-slate-100 hover:bg-slate-900 hover:text-white px-2.5 py-1.5 rounded-lg transition-all">
                                        <span>Manage</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-8 text-center text-slate-400 text-xs">
                No orders placed yet.
            </div>
        @endif
    </div>

    {{-- Low Stock Warnings & Top Selling (1/3 width) --}}
    <div class="space-y-6">
        
        {{-- Low Stock Alert Box --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
                <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-900 flex items-center gap-1.5">
                    <span>⚠️</span>
                    <span>Low Stock Alerts</span>
                </h4>
                <span class="text-[10px] font-bold bg-amber-50 text-amber-700 px-2 py-0.5 rounded border border-amber-200">
                    {{ $lowStockVariants->count() }} items
                </span>
            </div>

            @if($lowStockVariants->count() > 0)
                <div class="space-y-3">
                    @foreach($lowStockVariants as $ls)
                        <div class="flex items-center justify-between gap-3 text-xs">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-9 h-9 rounded-lg bg-slate-100 overflow-hidden shrink-0 border border-slate-200">
                                    @if($ls->product && $ls->product->primaryImage)
                                        <img src="{{ $ls->product->primaryImage->url }}" alt="" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-[8px] text-slate-400">Img</div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-900 truncate">{{ $ls->product->name ?? 'Product' }}</p>
                                    <p class="text-[10px] text-slate-400">Size: {{ $ls->size ?? 'Standard' }} • Color: {{ $ls->color ?? 'Default' }}</p>
                                </div>
                            </div>
                            <span class="font-extrabold text-xs px-2 py-0.5 rounded {{ $ls->stock == 0 ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-800' }}">
                                {{ $ls->stock }} left
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-4 text-center text-xs text-slate-400">
                    ✓ All product variants are healthy in stock!
                </div>
            @endif
        </div>

        {{-- Top Selling Products --}}
        @if($topProducts->count() > 0)
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-900 mb-4 pb-2 border-b border-slate-100 flex items-center gap-1.5">
                    <span>🔥</span>
                    <span>Top Selling Drops</span>
                </h4>
                <div class="space-y-3 text-xs">
                    @foreach($topProducts as $tp)
                        @php $tProduct = $tp->variant?->product; @endphp
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-9 h-9 rounded-lg bg-slate-100 overflow-hidden shrink-0 border border-slate-200">
                                    @if($tProduct && $tProduct->primaryImage)
                                        <img src="{{ $tProduct->primaryImage->url }}" alt="" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-[8px] text-slate-400">Img</div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-900 truncate">{{ $tProduct->name ?? 'Product' }}</p>
                                    <p class="text-[10px] text-slate-400">{{ $tp->total_qty }} units sold</p>
                                </div>
                            </div>
                            <span class="font-heading font-extrabold text-slate-900">₹{{ number_format($tp->total_sales) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ── 1. Sales & Revenue 7-Day Trend Chart ───────────────────────────
    const ctxSales = document.getElementById('salesTrendChart');
    if (ctxSales) {
        const labels = {!! json_encode($chartDates) !!};
        const revenues = {!! json_encode($chartRevenue) !!};
        const orders = {!! json_encode($chartOrders) !!};

        new Chart(ctxSales.getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Revenue (₹)',
                        data: revenues,
                        borderColor: '#0F172A',
                        backgroundColor: 'rgba(15, 23, 42, 0.04)',
                        fill: true,
                        tension: 0.35,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointBackgroundColor: '#0F172A',
                        yAxisID: 'y'
                    },
                    {
                        label: 'Orders Count',
                        data: orders,
                        borderColor: '#3B82F6',
                        backgroundColor: 'transparent',
                        borderDash: [5, 5],
                        tension: 0.35,
                        borderWidth: 2,
                        pointRadius: 3,
                        pointBackgroundColor: '#3B82F6',
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        padding: 12,
                        cornerRadius: 10,
                        titleFont: { family: 'Inter', weight: 'bold', size: 12 },
                        bodyFont: { family: 'Inter', size: 12 },
                        callbacks: {
                            label: function(context) {
                                if (context.datasetIndex === 0) {
                                    return ' Revenue: ₹' + Number(context.raw).toLocaleString();
                                }
                                return ' Orders: ' + context.raw;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Inter', size: 11 }, color: '#94A3B8' }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        grid: { color: '#F1F5F9' },
                        ticks: {
                            font: { family: 'Inter', size: 11 },
                            color: '#94A3B8',
                            callback: function(value) { return '₹' + value; }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: false,
                        position: 'right',
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // ── 2. Order Status Donut Chart ────────────────────────────────────
    const ctxStatus = document.getElementById('orderStatusChart');
    if (ctxStatus) {
        const statusData = {!! json_encode(array_values($statusCounts)) !!};
        const statusLabels = {!! json_encode(array_keys($statusCounts)) !!};

        new Chart(ctxStatus.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusData,
                    backgroundColor: [
                        '#F59E0B', // Processing - Amber
                        '#0EA5E9', // Confirmed - Sky
                        '#6366F1', // Shipped - Indigo
                        '#10B981', // Delivered - Emerald
                        '#F43F5E'  // Cancelled - Rose
                    ],
                    borderWidth: 2,
                    borderColor: '#FFFFFF',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        padding: 10,
                        cornerRadius: 8,
                        titleFont: { family: 'Inter', weight: 'bold', size: 12 }
                    }
                },
                cutout: '72%'
            }
        });
    }
});
</script>
@endpush
