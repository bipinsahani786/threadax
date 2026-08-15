@extends('admin.layouts.app')

@section('title', 'Sales & Revenue Analytics — ThreadAX Admin')

@section('content')
<div x-data="{
    customRangeOpen: '{{ $period }}' === 'custom',
    printReport() {
        window.print();
    }
}">

    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- 1. TOP TITLE & ACTION CONTROLS                                 --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-900 text-white">
                    Sales Intelligence
                </span>
                <span class="text-xs text-slate-400 font-medium">Real-time Performance Metrics</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-heading font-black text-slate-900 tracking-tight flex items-center gap-2">
                <span>📊 Sales & Revenue Analytics</span>
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">
                Monitor sales velocity, payment channel distributions, and customer drop performance.
            </p>
        </div>

        {{-- Action Buttons (Export & Print) --}}
        <div class="flex flex-wrap items-center gap-2.5">
            <button type="button" 
                    @click="printReport()"
                    class="px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-bold transition-all flex items-center gap-1.5 shadow-2xs cursor-pointer">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.077-.37-2.18-.37-3.329 0-3.866 2.4-7 6-7s6 3.134 6 7c0 1.149-.13 2.252-.37 3.329m-11.26 0A9.97 9.97 0 0012 17.5c2.32 0 4.43-.8 6.01-2.171m-12.02 0l-1.42 4.26A1 1 0 005.52 21h12.96a1 1 0 00.95-1.411l-1.42-4.26"/></svg>
                <span>Print Analytics</span>
            </button>

            <a href="{{ route('admin.reports.export', request()->all()) }}" 
               class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all flex items-center gap-1.5 shadow-xs cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                <span>Export CSV Report</span>
            </a>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- 2. ADVANCED TIME-RANGE & CHANNEL FILTER TOOLBAR               --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 mb-6 shadow-xs">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="space-y-4">
            
            {{-- Quick Presets Row --}}
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-1.5">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mr-1">Time Horizon:</span>
                    
                    @php
                        $presets = [
                            'today'        => 'Today',
                            'yesterday'    => 'Yesterday',
                            'this_week'    => 'This Week',
                            'this_month'   => 'This Month',
                            'last_30_days' => 'Last 30 Days',
                            'this_year'    => 'This Year',
                            'all_time'     => 'All Time',
                        ];
                    @endphp

                    @foreach($presets as $key => $label)
                        <button type="submit" 
                                name="period" 
                                value="{{ $key }}" 
                                class="px-3 py-1.5 rounded-xl border text-xs font-bold transition-all cursor-pointer {{ $period === $key ? 'bg-slate-900 text-white border-slate-900 shadow-xs' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">
                            {{ $label }}
                        </button>
                    @endforeach

                    <button type="button" 
                            @click="customRangeOpen = !customRangeOpen" 
                            :class="customRangeOpen ? 'bg-blue-600 text-white border-blue-600 shadow-xs' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100'"
                            class="px-3 py-1.5 rounded-xl border text-xs font-bold transition-all cursor-pointer flex items-center gap-1">
                        <span>📅 Custom Range</span>
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                    </button>
                </div>

                {{-- Status & Channel Quick Filter --}}
                <div class="flex flex-wrap items-center gap-2">
                    <select name="payment_method" onchange="this.form.submit()" class="py-1.5 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 outline-none">
                        <option value="all" {{ $paymentMethod === 'all' ? 'selected' : '' }}>All Payment Channels</option>
                        <option value="razorpay" {{ $paymentMethod === 'razorpay' ? 'selected' : '' }}>Online (Razorpay)</option>
                        <option value="cod" {{ $paymentMethod === 'cod' ? 'selected' : '' }}>Cash on Delivery (COD)</option>
                    </select>

                    <select name="order_status" onchange="this.form.submit()" class="py-1.5 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 outline-none">
                        <option value="all" {{ $orderStatus === 'all' ? 'selected' : '' }}>All Order Statuses</option>
                        <option value="delivered" {{ $orderStatus === 'delivered' ? 'selected' : '' }}>🟢 Delivered Only</option>
                        <option value="processing" {{ $orderStatus === 'processing' ? 'selected' : '' }}>🟡 Processing / In-Flight</option>
                        <option value="cancelled" {{ $orderStatus === 'cancelled' ? 'selected' : '' }}>🔴 Cancelled</option>
                    </select>
                </div>
            </div>

            {{-- Collapsible Custom Date Range Picker --}}
            <div x-show="customRangeOpen" x-cloak class="pt-3 border-t border-slate-100 flex flex-wrap items-center gap-3">
                <input type="hidden" name="period" value="custom">
                <div class="flex items-center gap-2 text-xs">
                    <span class="font-bold text-slate-500">From:</span>
                    <input type="date" name="date_from" value="{{ $dateFrom }}" class="py-1.5 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 outline-none">
                </div>
                <div class="flex items-center gap-2 text-xs">
                    <span class="font-bold text-slate-500">To:</span>
                    <input type="date" name="date_to" value="{{ $dateTo }}" class="py-1.5 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 outline-none">
                </div>
                <button type="submit" class="px-4 py-1.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition-all shadow-xs cursor-pointer">
                    Apply Custom Filter
                </button>
            </div>

        </form>
    </div>

    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- 3. EXECUTIVE METRIC KPI CARDS (5-CARD GRID)                   --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        
        {{-- Card 1: Gross Revenue --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Gross Sales Volume</span>
                <span class="w-7 h-7 rounded-xl bg-slate-100 text-slate-900 flex items-center justify-center text-xs font-black">₹</span>
            </div>
            <p class="text-2xl font-heading font-black text-slate-900 tracking-tight">₹{{ number_format($grossRevenue, 0) }}</p>
            <p class="text-[11px] text-slate-500 mt-1 font-medium flex items-center gap-1">
                <span>Total non-cancelled value</span>
            </p>
        </div>

        {{-- Card 2: Online Realized Revenue --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Paid / Realized</span>
                <span class="w-7 h-7 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-xs">💳</span>
            </div>
            <p class="text-2xl font-heading font-black text-emerald-600 tracking-tight">₹{{ number_format($paidRevenue, 0) }}</p>
            <p class="text-[11px] text-slate-500 mt-1 font-medium">
                {{ $onlineOrdersCount }} online payment(s)
            </p>
        </div>

        {{-- Card 3: COD Receivables --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold text-amber-600 uppercase tracking-wider">COD In-Flight</span>
                <span class="w-7 h-7 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center text-xs">🚚</span>
            </div>
            <p class="text-2xl font-heading font-black text-amber-600 tracking-tight">₹{{ number_format($codPendingRevenue, 0) }}</p>
            <p class="text-[11px] text-slate-500 mt-1 font-medium">
                {{ $codOrdersCount }} COD receivable(s)
            </p>
        </div>

        {{-- Card 4: Total Orders Placed --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold text-blue-600 uppercase tracking-wider">Total Orders</span>
                <span class="w-7 h-7 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center text-xs">📦</span>
            </div>
            <p class="text-2xl font-heading font-black text-slate-900 tracking-tight">{{ number_format($totalOrdersCount) }}</p>
            <p class="text-[11px] text-slate-500 mt-1 font-medium">
                {{ $deliveredOrdersCount }} delivered • {{ $processingOrdersCount }} active
            </p>
        </div>

        {{-- Card 5: Average Order Value --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold text-purple-600 uppercase tracking-wider">Avg. Order Value</span>
                <span class="w-7 h-7 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center text-xs">🏷️</span>
            </div>
            <p class="text-2xl font-heading font-black text-purple-700 tracking-tight">₹{{ number_format($averageOrderValue, 0) }}</p>
            <p class="text-[11px] text-slate-500 mt-1 font-medium">
                Across {{ $uniqueCustomerEmails }} unique buyer(s)
            </p>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- 4. INTERACTIVE CHARTS & CHANNEL DISTRIBUTION                  --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">
        
        {{-- Main Revenue & Volume Curve (col-span-8) --}}
        <div class="lg:col-span-8 bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-heading font-extrabold text-slate-900">Revenue & Order Velocity</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Timeline distribution of sales revenue (₹) and order volumes.</p>
                    </div>
                    <div class="flex items-center gap-3 text-xs font-bold">
                        <div class="flex items-center gap-1.5 text-slate-700">
                            <span class="w-3 h-3 rounded-full bg-slate-900"></span>
                            <span>Gross Sales</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-emerald-600">
                            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                            <span>Paid Online</span>
                        </div>
                    </div>
                </div>

                <div class="h-72 sm:h-80 w-full relative">
                    <canvas id="salesAnalyticsChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Channel Breakdown & Fulfillment Health (col-span-4) --}}
        <div class="lg:col-span-4 space-y-6">
            
            {{-- Payment Channels Breakdown --}}
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                <h3 class="text-sm font-heading font-extrabold text-slate-900 mb-1">Payment Method Split</h3>
                <p class="text-xs text-slate-400 mb-4">Realized online vs Cash on Delivery collection.</p>
                
                @php
                    $totalChannelRevenue = $onlineRevenue + $codTotalRevenue;
                    $onlinePercent = $totalChannelRevenue > 0 ? round(($onlineRevenue / $totalChannelRevenue) * 100) : 0;
                    $codPercent = $totalChannelRevenue > 0 ? (100 - $onlinePercent) : 0;
                @endphp

                <div class="space-y-3.5 text-xs">
                    {{-- Online --}}
                    <div>
                        <div class="flex items-center justify-between font-bold mb-1">
                            <span class="text-slate-700 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span>Online (Razorpay / UPI)</span>
                            </span>
                            <span class="text-slate-900">₹{{ number_format($onlineRevenue) }} ({{ $onlinePercent }}%)</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full bg-emerald-500 rounded-full transition-all duration-500" style="width: {{ $onlinePercent }}%"></div>
                        </div>
                    </div>

                    {{-- COD --}}
                    <div>
                        <div class="flex items-center justify-between font-bold mb-1">
                            <span class="text-slate-700 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                <span>Cash on Delivery (COD)</span>
                            </span>
                            <span class="text-slate-900">₹{{ number_format($codTotalRevenue) }} ({{ $codPercent }}%)</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full bg-amber-500 rounded-full transition-all duration-500" style="width: {{ $codPercent }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Top Performing Drops Card --}}
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                <h3 class="text-sm font-heading font-extrabold text-slate-900 mb-1">Top Selling Drops</h3>
                <p class="text-xs text-slate-400 mb-3">Highest revenue generators in period.</p>

                @if($topProducts->count() > 0)
                    <div class="space-y-2.5">
                        @foreach($topProducts as $topItem)
                            @php
                                $variant = $topItem->variant;
                                $product = $variant ? $variant->product : null;
                                $productImg = $product ? ($product->primaryImage?->url ?? $product->images->first()?->url) : null;
                            @endphp
                            <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50 border border-slate-100 text-xs">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-8 h-8 rounded-lg bg-slate-200 shrink-0 overflow-hidden border border-slate-200">
                                        @if($productImg)
                                            <img src="{{ $productImg }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-[10px] font-bold text-slate-400">TX</div>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-900 truncate">{{ $product ? $product->name : 'Product Variant' }}</p>
                                        <p class="text-[10px] text-slate-400 font-mono">{{ $variant ? ($variant->size . ' • ' . $variant->color) : 'Default' }}</p>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="font-bold text-slate-900">₹{{ number_format($topItem->total_sales) }}</p>
                                    <p class="text-[10px] text-slate-500">{{ $topItem->total_qty }} sold</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-6 text-center text-slate-400 text-xs">
                        No product drop sales recorded in this period.
                    </div>
                @endif
            </div>

        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- 5. ORDERS LEDGER FOR SELECTED PERIOD                          --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="text-base font-heading font-extrabold text-slate-900">Orders Ledger ({{ $orders->total() }} Total)</h3>
                <p class="text-xs text-slate-400">Detailed itemized order transactions for the selected timeframe.</p>
            </div>
            <div class="text-xs font-bold text-slate-500">
                Period: <span class="text-slate-900 uppercase font-mono">{{ str_replace('_', ' ', $period) }}</span>
            </div>
        </div>

        @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-200/80">
                        <tr>
                            <th class="py-3.5 px-4 sm:px-6">Order ID</th>
                            <th class="py-3.5 px-4 sm:px-6">Date & Time</th>
                            <th class="py-3.5 px-4 sm:px-6">Customer</th>
                            <th class="py-3.5 px-4 sm:px-6">Payment Mode</th>
                            <th class="py-3.5 px-4 sm:px-6">Payment Status</th>
                            <th class="py-3.5 px-4 sm:px-6">Fulfillment</th>
                            <th class="py-3.5 px-4 sm:px-6 text-right">Amount</th>
                            <th class="py-3.5 px-4 sm:px-6 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @foreach($orders as $order)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                
                                {{-- Order ID --}}
                                <td class="py-4 px-4 sm:px-6 whitespace-nowrap">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="font-mono font-bold text-slate-900 hover:text-blue-600 hover:underline">
                                        #{{ $order->order_number ?? ('TX-'.$order->id) }}
                                    </a>
                                </td>

                                {{-- Date --}}
                                <td class="py-4 px-4 sm:px-6 whitespace-nowrap">
                                    <span class="block font-medium text-slate-800">{{ $order->created_at->format('M d, Y') }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $order->created_at->format('h:i A') }}</span>
                                </td>

                                {{-- Customer --}}
                                <td class="py-4 px-4 sm:px-6">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-xl bg-slate-900 text-white font-black text-[11px] flex items-center justify-center shrink-0">
                                            {{ strtoupper(substr($order->user ? $order->user->name : ($order->shipping_name ?? 'C'), 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-slate-900 truncate">{{ $order->user ? $order->user->name : ($order->shipping_name ?? 'Guest Buyer') }}</p>
                                            <p class="text-[10px] text-slate-400 truncate">{{ $order->user ? $order->user->email : ($order->shipping_email ?? '-') }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Payment Method --}}
                                <td class="py-4 px-4 sm:px-6 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider {{ $order->payment_method === 'cod' ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-blue-50 text-blue-800 border border-blue-200' }}">
                                        {{ strtoupper($order->payment_method ?? 'ONLINE') }}
                                    </span>
                                </td>

                                {{-- Payment Status --}}
                                <td class="py-4 px-4 sm:px-6 whitespace-nowrap">
                                    @if($order->payment_status === 'paid')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Paid</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            <span>Pending</span>
                                        </span>
                                    @endif
                                </td>

                                {{-- Fulfillment Status --}}
                                <td class="py-4 px-4 sm:px-6 whitespace-nowrap">
                                    @if($order->status === 'delivered')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span>Delivered</span>
                                        </span>
                                    @elseif($order->status === 'cancelled')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-rose-50 text-rose-700 border border-rose-200">
                                            <span>Cancelled</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-slate-100 text-slate-800 border border-slate-200">
                                            <span>{{ ucfirst($order->status) }}</span>
                                        </span>
                                    @endif
                                </td>

                                {{-- Amount --}}
                                <td class="py-4 px-4 sm:px-6 text-right whitespace-nowrap font-heading font-black text-slate-900 text-sm">
                                    ₹{{ number_format($order->total) }}
                                </td>

                                {{-- Action --}}
                                <td class="py-4 px-4 sm:px-6 text-right whitespace-nowrap">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" 
                                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-900 hover:text-white text-slate-900 font-bold transition-all shadow-2xs">
                                        <span>Details</span>
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                                    </a>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($orders->hasPages())
                <div class="px-6 py-4 border-t border-slate-200/80 bg-slate-50/50">
                    {{ $orders->links() }}
                </div>
            @endif
        @else
            <div class="p-12 text-center text-slate-500">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 text-xl">
                    📊
                </div>
                <h4 class="font-heading font-bold text-sm text-slate-900 mb-1">No Orders Found</h4>
                <p class="text-xs text-slate-400 mb-4 max-w-sm mx-auto">There are no matching order transactions for this specific filter or timeframe.</p>
                <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold shadow-xs">
                    Reset Timeframe
                </a>
            </div>
        @endif
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesAnalyticsChart');
        if (!ctx) return;

        const labels = {!! json_encode($chartLabels) !!};
        const grossRevenues = {!! json_encode($chartGrossRevenues) !!};
        const paidRevenues = {!! json_encode($chartPaidRevenues) !!};
        const orderCounts = {!! json_encode($chartOrderCounts) !!};

        new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Gross Sales (₹)',
                        data: grossRevenues,
                        borderColor: '#0f172a', // slate-900
                        backgroundColor: 'rgba(15, 23, 42, 0.05)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.35,
                        pointRadius: 3,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#0f172a',
                        yAxisID: 'y'
                    },
                    {
                        label: 'Realized Paid (₹)',
                        data: paidRevenues,
                        borderColor: '#10b981', // emerald-500
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        borderDash: [4, 4],
                        tension: 0.35,
                        pointRadius: 2,
                        pointHoverRadius: 5,
                        pointBackgroundColor: '#10b981',
                        yAxisID: 'y'
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
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleColor: '#ffffff',
                        bodyColor: '#e2e8f0',
                        padding: 12,
                        cornerRadius: 12,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += '₹' + new Intl.NumberFormat('en-IN').format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: { size: 10, weight: '600' },
                            color: '#94a3b8'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f5f9'
                        },
                        ticks: {
                            font: { size: 10, weight: '600' },
                            color: '#94a3b8',
                            callback: function(value) {
                                if (value >= 100000) return '₹' + (value / 100000).toFixed(1) + 'L';
                                if (value >= 1000) return '₹' + (value / 1000).toFixed(0) + 'k';
                                return '₹' + value;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
