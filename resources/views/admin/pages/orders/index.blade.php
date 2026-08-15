@extends('admin.layouts.app')

@section('title', 'Orders & Fulfillment Center — ThreadAX Admin')

@section('content')
<div x-data="{
    detailModalOpen: false,
    activeOrder: null,
    statusForm: {
        status: 'pending',
        payment_status: 'pending'
    },
    openOrder(order) {
        this.activeOrder = order;
        this.statusForm.status = order.status;
        this.statusForm.payment_status = order.payment_status;
        this.detailModalOpen = true;
    },
    getCleanPhone(phone) {
        if (!phone) return '';
        let clean = phone.replace(/[^0-9]/g, '');
        if (clean.length === 10) clean = '91' + clean;
        return clean;
    }
}">

    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- 1. TOP HEADER & SUMMARY                                       --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-900 text-white">
                    Commerce Desk
                </span>
                <span class="text-xs text-slate-400 font-medium">Real-time Order Processing</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-heading font-black text-slate-900 tracking-tight flex items-center gap-2">
                <span>📦 Orders & Fulfillment Center</span>
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">
                Track customer orders, process shipping status, and manage payment settlements.
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.orders.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all flex items-center gap-1.5 shadow-2xs">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                <span>Refresh</span>
            </a>

            <a href="{{ route('admin.reports.export', request()->all()) }}" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition-all flex items-center gap-1.5 shadow-xs">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                <span>Export CSV</span>
            </a>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- 2. CLICKABLE ANALYTICS KPI CARDS                              --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}
    @php
        $currentStatus = request('status', '');
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5 sm:gap-4 mb-6">
        
        {{-- Card 1: All Orders --}}
        <a href="{{ route('admin.orders.index', array_merge(request()->except(['status', 'page']), [])) }}" 
           class="p-4 rounded-2xl border transition-all duration-200 cursor-pointer {{ empty($currentStatus) || $currentStatus === 'all' ? 'bg-slate-900 text-white border-slate-900 shadow-md transform -translate-y-0.5' : 'bg-white text-slate-900 border-slate-200/80 hover:border-slate-300 shadow-xs' }}">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[10px] font-bold uppercase tracking-wider {{ empty($currentStatus) || $currentStatus === 'all' ? 'text-slate-300' : 'text-slate-400' }}">All Orders</span>
                <span class="text-xs">📦</span>
            </div>
            <p class="text-2xl font-heading font-black tracking-tight">{{ number_format($stats['total']) }}</p>
            <p class="text-[10px] mt-1 {{ empty($currentStatus) || $currentStatus === 'all' ? 'text-slate-300' : 'text-slate-500' }}">₹{{ number_format($stats['total_revenue']) }} volume</p>
        </a>

        {{-- Card 2: Pending Confirmation --}}
        <a href="{{ route('admin.orders.index', array_merge(request()->except(['status', 'page']), ['status' => 'pending'])) }}" 
           class="p-4 rounded-2xl border transition-all duration-200 cursor-pointer {{ $currentStatus === 'pending' ? 'bg-amber-600 text-white border-amber-600 shadow-md transform -translate-y-0.5' : 'bg-white text-slate-900 border-slate-200/80 hover:border-amber-300 shadow-xs' }}">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[10px] font-bold uppercase tracking-wider {{ $currentStatus === 'pending' ? 'text-amber-100' : 'text-amber-600' }}">Pending</span>
                <span class="w-2 h-2 rounded-full bg-amber-400 {{ $stats['pending'] > 0 ? 'animate-ping' : '' }}"></span>
            </div>
            <p class="text-2xl font-heading font-black tracking-tight">{{ number_format($stats['pending']) }}</p>
            <p class="text-[10px] mt-1 {{ $currentStatus === 'pending' ? 'text-amber-100' : 'text-slate-500' }}">Awaiting confirm</p>
        </a>

        {{-- Card 3: Processing & Active --}}
        <a href="{{ route('admin.orders.index', array_merge(request()->except(['status', 'page']), ['status' => 'active'])) }}" 
           class="p-4 rounded-2xl border transition-all duration-200 cursor-pointer {{ $currentStatus === 'active' || $currentStatus === 'processing' ? 'bg-blue-600 text-white border-blue-600 shadow-md transform -translate-y-0.5' : 'bg-white text-slate-900 border-slate-200/80 hover:border-blue-300 shadow-xs' }}">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[10px] font-bold uppercase tracking-wider {{ $currentStatus === 'active' || $currentStatus === 'processing' ? 'text-blue-100' : 'text-blue-600' }}">Processing</span>
                <span class="text-xs">⚡</span>
            </div>
            <p class="text-2xl font-heading font-black tracking-tight">{{ number_format($stats['processing_shipped']) }}</p>
            <p class="text-[10px] mt-1 {{ $currentStatus === 'active' || $currentStatus === 'processing' ? 'text-blue-100' : 'text-slate-500' }}">In packing / transit</p>
        </a>

        {{-- Card 4: Delivered --}}
        <a href="{{ route('admin.orders.index', array_merge(request()->except(['status', 'page']), ['status' => 'delivered'])) }}" 
           class="p-4 rounded-2xl border transition-all duration-200 cursor-pointer {{ $currentStatus === 'delivered' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md transform -translate-y-0.5' : 'bg-white text-slate-900 border-slate-200/80 hover:border-emerald-300 shadow-xs' }}">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[10px] font-bold uppercase tracking-wider {{ $currentStatus === 'delivered' ? 'text-emerald-100' : 'text-emerald-600' }}">Delivered</span>
                <span class="text-xs">✓</span>
            </div>
            <p class="text-2xl font-heading font-black tracking-tight">{{ number_format($stats['delivered']) }}</p>
            <p class="text-[10px] mt-1 {{ $currentStatus === 'delivered' ? 'text-emerald-100' : 'text-slate-500' }}">Completed drops</p>
        </a>

        {{-- Card 5: Cancelled --}}
        <a href="{{ route('admin.orders.index', array_merge(request()->except(['status', 'page']), ['status' => 'cancelled'])) }}" 
           class="p-4 rounded-2xl border transition-all duration-200 cursor-pointer {{ $currentStatus === 'cancelled' ? 'bg-rose-600 text-white border-rose-600 shadow-md transform -translate-y-0.5' : 'bg-white text-slate-900 border-slate-200/80 hover:border-rose-300 shadow-xs' }}">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[10px] font-bold uppercase tracking-wider {{ $currentStatus === 'cancelled' ? 'text-rose-100' : 'text-rose-600' }}">Cancelled</span>
                <span class="text-xs">✕</span>
            </div>
            <p class="text-2xl font-heading font-black tracking-tight">{{ number_format($stats['cancelled']) }}</p>
            <p class="text-[10px] mt-1 {{ $currentStatus === 'cancelled' ? 'text-rose-100' : 'text-slate-500' }}">Voided / returned</p>
        </a>

        {{-- Card 6: Received Today --}}
        <a href="{{ route('admin.orders.index', array_merge(request()->except(['preset', 'page']), ['preset' => 'today'])) }}" 
           class="p-4 rounded-2xl border transition-all duration-200 cursor-pointer {{ request('preset') === 'today' ? 'bg-purple-700 text-white border-purple-700 shadow-md transform -translate-y-0.5' : 'bg-white text-slate-900 border-slate-200/80 hover:border-purple-300 shadow-xs' }}">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[10px] font-bold uppercase tracking-wider {{ request('preset') === 'today' ? 'text-purple-100' : 'text-purple-600' }}">Today's Drops</span>
                <span class="text-xs">🔥</span>
            </div>
            <p class="text-2xl font-heading font-black tracking-tight">{{ number_format($stats['today']) }}</p>
            <p class="text-[10px] mt-1 {{ request('preset') === 'today' ? 'text-purple-100' : 'text-slate-500' }}">New today</p>
        </a>

    </div>

    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- 3. SEARCH & ADVANCED COMMERCE FILTERS TOOLBAR                 --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 mb-6 shadow-xs">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="space-y-4">
            
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                
                {{-- Search Box --}}
                <div class="sm:col-span-5 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    </div>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search Order #, customer name, email, phone..." 
                           class="w-full pl-9 pr-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all">
                </div>

                {{-- Status Select --}}
                <div class="sm:col-span-2">
                    <select name="status" class="w-full py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 outline-none">
                        <option value="all">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>🟡 Pending</option>
                        <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>⚡ Processing</option>
                        <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>🚚 Shipped</option>
                        <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>🟢 Delivered</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>🔴 Cancelled</option>
                    </select>
                </div>

                {{-- Payment Channel Select --}}
                <div class="sm:col-span-2">
                    <select name="payment_method" class="w-full py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 outline-none">
                        <option value="all">All Channels</option>
                        <option value="razorpay" {{ request('payment_method') === 'razorpay' ? 'selected' : '' }}>💳 Online (Razorpay)</option>
                        <option value="cod" {{ request('payment_method') === 'cod' ? 'selected' : '' }}>💵 Cash on Delivery</option>
                    </select>
                </div>

                {{-- Date Pickers --}}
                <div class="sm:col-span-3 grid grid-cols-2 gap-2">
                    <input type="date" name="date_from" value="{{ request('date_from') }}" title="From Date" class="w-full py-2.5 px-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 outline-none">
                    <input type="date" name="date_to" value="{{ request('date_to') }}" title="To Date" class="w-full py-2.5 px-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 outline-none">
                </div>

            </div>

            {{-- Quick Presets & Actions --}}
            <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-slate-100 text-xs">
                <div class="flex flex-wrap items-center gap-1.5">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mr-1">Presets:</span>
                    <button type="submit" name="preset" value="today" class="px-2.5 py-1 rounded-lg border text-[11px] font-bold transition-all {{ request('preset') === 'today' ? 'bg-slate-900 text-white border-slate-900' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">
                        Today
                    </button>
                    <button type="submit" name="preset" value="7_days" class="px-2.5 py-1 rounded-lg border text-[11px] font-bold transition-all {{ request('preset') === '7_days' ? 'bg-slate-900 text-white border-slate-900' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">
                        Last 7 Days
                    </button>
                    <button type="submit" name="preset" value="30_days" class="px-2.5 py-1 rounded-lg border text-[11px] font-bold transition-all {{ request('preset') === '30_days' ? 'bg-slate-900 text-white border-slate-900' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">
                        Last 30 Days
                    </button>
                </div>

                <div class="flex items-center gap-2">
                    @if(request()->hasAny(['search', 'status', 'payment_method', 'date_from', 'date_to', 'preset']))
                        <a href="{{ route('admin.orders.index') }}" class="px-3 py-1.5 text-xs font-bold text-rose-600 hover:bg-rose-50 rounded-xl transition-all">
                            Clear Filters
                        </a>
                    @endif
                    <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl transition-all shadow-xs active:scale-95 cursor-pointer">
                        Filter Orders
                    </button>
                </div>
            </div>

        </form>
    </div>

    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- 4. ORDERS LEDGER TABLE                                        --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs">
        @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-200/80">
                        <tr>
                            <th class="py-3.5 px-4 sm:px-6">Order Details</th>
                            <th class="py-3.5 px-4 sm:px-6">Customer</th>
                            <th class="py-3.5 px-4 sm:px-6">Ordered Drops</th>
                            <th class="py-3.5 px-4 sm:px-6">Payment Mode</th>
                            <th class="py-3.5 px-4 sm:px-6">Fulfillment</th>
                            <th class="py-3.5 px-4 sm:px-6 text-right">Total Amount</th>
                            <th class="py-3.5 px-4 sm:px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @foreach($orders as $order)
                            <tr class="hover:bg-slate-50/70 transition-colors group">
                                
                                {{-- Order ID & Date --}}
                                <td class="py-4 px-4 sm:px-6 whitespace-nowrap">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="font-mono font-bold text-slate-900 hover:text-blue-600 hover:underline flex items-center gap-1.5 text-sm">
                                        <span>#{{ $order->order_number ?? ('TX-'.$order->id) }}</span>
                                    </a>
                                    <p class="text-[11px] text-slate-400 mt-0.5">
                                        {{ $order->created_at->format('M d, Y • h:i A') }}
                                    </p>
                                </td>

                                {{-- Customer Info --}}
                                <td class="py-4 px-4 sm:px-6">
                                    <div class="flex items-start gap-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-slate-900 text-white font-black text-xs flex items-center justify-center shrink-0 shadow-2xs mt-0.5">
                                            {{ strtoupper(substr($order->user ? $order->user->name : ($order->shipping_name ?? 'C'), 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-slate-900 truncate">
                                                {{ $order->user ? $order->user->name : ($order->shipping_name ?? 'Guest Buyer') }}
                                            </p>
                                            <p class="text-[11px] text-slate-400 truncate max-w-[170px]">
                                                {{ $order->user ? $order->user->email : ($order->shipping_email ?? '-') }}
                                            </p>
                                            @if(!empty($order->shipping_phone))
                                                @php
                                                    $cleanP = preg_replace('/[^0-9]/', '', $order->shipping_phone);
                                                    if(strlen($cleanP) === 10) $cleanP = '91' . $cleanP;
                                                @endphp
                                                <div class="flex items-center gap-1 mt-0.5">
                                                    <span class="text-[10px] text-slate-500 font-mono">📞 {{ $order->shipping_phone }}</span>
                                                    <a href="https://wa.me/{{ $cleanP }}?text={{ urlencode('Hi ' . ($order->shipping_name ?? 'Customer') . ', regarding your ThreadAx Order #' . $order->order_number) }}" 
                                                       target="_blank" 
                                                       title="Chat on WhatsApp"
                                                       class="text-emerald-600 hover:text-emerald-700">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Ordered Items Preview --}}
                                <td class="py-4 px-4 sm:px-6">
                                    <div class="flex items-center gap-1.5">
                                        @foreach($order->items->take(3) as $item)
                                            @php
                                                $prod = $item->variant ? $item->variant->product : null;
                                                $imgModel = $prod ? ($prod->primaryImage ?? $prod->images->first()) : null;
                                                $imgUrl = $imgModel?->url;
                                            @endphp
                                            <div class="w-8 h-8 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden relative group/img shrink-0" title="{{ $prod->name ?? 'Drop Item' }}">
                                                @if($imgUrl)
                                                    <img src="{{ $imgUrl }}" class="w-full h-full object-cover">
                                                @else
                                                    <span class="w-full h-full flex items-center justify-center text-[9px] font-bold text-slate-400">TX</span>
                                                @endif
                                                <span class="absolute bottom-0 right-0 bg-slate-900/80 text-white text-[8px] font-bold px-1 rounded-tl">
                                                    {{ $item->quantity }}
                                                </span>
                                            </div>
                                        @endforeach
                                        @if($order->items->count() > 3)
                                            <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded-md">
                                                +{{ $order->items->count() - 3 }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-1">{{ $order->items->sum('quantity') }} total unit(s)</p>
                                </td>

                                {{-- Payment Channel & Status --}}
                                <td class="py-4 px-4 sm:px-6 whitespace-nowrap">
                                    <div class="space-y-1">
                                        <div>
                                            <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-bold uppercase tracking-wider {{ $order->payment_method === 'cod' ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-blue-50 text-blue-800 border border-blue-200' }}">
                                                {{ strtoupper($order->payment_method ?? 'ONLINE') }}
                                            </span>
                                        </div>
                                        <div>
                                            @if($order->payment_status === 'paid')
                                                <span class="inline-flex items-center gap-1 text-[10px] font-extrabold text-emerald-600">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    <span>Paid</span>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-[10px] font-extrabold text-amber-600">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                    <span>Pending</span>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Fulfillment Status --}}
                                <td class="py-4 px-4 sm:px-6 whitespace-nowrap">
                                    @if($order->status === 'delivered')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Delivered</span>
                                        </span>
                                    @elseif($order->status === 'cancelled')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            <span>Cancelled</span>
                                        </span>
                                    @elseif($order->status === 'processing')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                            <span>Processing</span>
                                        </span>
                                    @elseif($order->status === 'shipped')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                            <span>Shipped</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            <span>Pending</span>
                                        </span>
                                    @endif
                                </td>

                                {{-- Total Amount --}}
                                <td class="py-4 px-4 sm:px-6 text-right whitespace-nowrap font-heading font-black text-slate-900 text-sm">
                                    ₹{{ number_format($order->total, 2) }}
                                </td>

                                {{-- Action Buttons --}}
                                <td class="py-4 px-4 sm:px-6 text-right whitespace-nowrap space-x-1">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" 
                                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-slate-900 text-white hover:bg-slate-800 font-bold transition-all shadow-xs cursor-pointer">
                                        <span>Manage</span>
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                                    </a>

                                    @if($order->status === 'delivered')
                                        <a href="{{ route('admin.orders.invoice.download', $order->id) }}" 
                                           title="Download Tax Invoice" 
                                           class="p-1.5 rounded-xl text-slate-400 hover:text-slate-900 hover:bg-slate-100 inline-block transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                        </a>
                                    @endif
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
                    📦
                </div>
                <h4 class="font-heading font-bold text-sm text-slate-900 mb-1">No Orders Found</h4>
                <p class="text-xs text-slate-400 mb-4 max-w-sm mx-auto">No orders match your current search terms, status, or date filters.</p>
                <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold shadow-xs">
                    Reset All Filters
                </a>
            </div>
        @endif
    </div>

</div>
@endsection
