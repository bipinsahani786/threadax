@extends('admin.layouts.app')

@section('title', 'Returns & Exchanges')
@section('page-title', 'Return & Exchange Management')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    {{-- Top Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <h1 class="text-xl sm:text-2xl font-heading font-black text-slate-900 tracking-tight flex items-center gap-2">
                <span>Returns & Exchanges</span>
                <span class="text-base">🔄</span>
            </h1>
            <p class="text-xs text-slate-400 mt-1">Review customer return requests, assign reverse pickups, QC inspections and process refunds.</p>
        </div>

        <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-bold flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                <span>{{ $stats['requested'] }} Pending Review</span>
            </span>
        </div>
    </div>

    {{-- KPI Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Pending Review --}}
        <div class="stat-card">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider">Pending Review</span>
                <span class="text-base">⏳</span>
            </div>
            <div class="text-2xl font-heading font-black text-amber-600">{{ $stats['requested'] }}</div>
            <p class="text-[11px] text-slate-400 mt-1">Requires admin approval</p>
        </div>

        {{-- Card 2: Reverse Pickups Active --}}
        <div class="stat-card">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider">Pickup / Transit</span>
                <span class="text-base">🚚</span>
            </div>
            <div class="text-2xl font-heading font-black text-indigo-600">{{ $stats['pickup_scheduled'] }}</div>
            <p class="text-[11px] text-slate-400 mt-1">Courier reverse transit</p>
        </div>

        {{-- Card 3: Received at Warehouse --}}
        <div class="stat-card">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider">Received & QC</span>
                <span class="text-base">🔍</span>
            </div>
            <div class="text-2xl font-heading font-black text-teal-600">{{ $stats['received_at_hub'] }}</div>
            <p class="text-[11px] text-slate-400 mt-1">Ready for refund / restock</p>
        </div>

        {{-- Card 4: Total Refunded Value --}}
        <div class="stat-card">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider">Total Refunded</span>
                <span class="text-base">💵</span>
            </div>
            <div class="text-2xl font-heading font-black text-emerald-600">₹{{ number_format($stats['total_refund_val'], 2) }}</div>
            <p class="text-[11px] text-slate-400 mt-1">{{ $stats['refunded'] }} returns processed</p>
        </div>
    </div>

    {{-- Filters & Search Bar --}}
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
        <form action="{{ route('admin.returns.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            {{-- Search --}}
            <div class="flex-1 min-w-[240px] relative">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Search Return #, Order #, Customer, Phone, AWB..." 
                       class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 outline-none focus:border-slate-400">
            </div>

            {{-- Status Filter --}}
            <select name="status" class="py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 outline-none focus:border-slate-400">
                <option value="all">All Statuses</option>
                <option value="requested" {{ request('status') === 'requested' ? 'selected' : '' }}>⏳ Review Pending</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>✅ Approved</option>
                <option value="pickup_scheduled" {{ request('status') === 'pickup_scheduled' ? 'selected' : '' }}>🚚 Pickup Scheduled</option>
                <option value="picked_up" {{ request('status') === 'picked_up' ? 'selected' : '' }}>📦 In Transit</option>
                <option value="received_at_hub" {{ request('status') === 'received_at_hub' ? 'selected' : '' }}>🔍 Received / QC Passed</option>
                <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>💵 Refunded</option>
                <option value="exchanged" {{ request('status') === 'exchanged' ? 'selected' : '' }}>🔄 Exchanged</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>❌ Rejected</option>
            </select>

            {{-- Type Filter --}}
            <select name="type" class="py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 outline-none focus:border-slate-400">
                <option value="all">All Types</option>
                <option value="return" {{ request('type') === 'return' ? 'selected' : '' }}>↩ Return (Refund)</option>
                <option value="exchange" {{ request('type') === 'exchange' ? 'selected' : '' }}>🔄 Size Exchange</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition-all cursor-pointer">
                Filter
            </button>

            @if(request()->hasAny(['search', 'status', 'type']))
                <a href="{{ route('admin.returns.index') }}" class="px-3 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200 transition-all">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Returns Ledger Table --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-5">Request & Order</th>
                        <th class="py-3.5 px-4">Customer</th>
                        <th class="py-3.5 px-4">Type & Reason</th>
                        <th class="py-3.5 px-4">Items / Value</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4">Created Date</th>
                        <th class="py-3.5 px-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($returns as $ret)
                        @php $badge = $ret->status_badge; @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            {{-- Request & Order # --}}
                            <td class="py-4 px-5">
                                <div class="font-mono font-extrabold text-slate-900 text-xs">
                                    <a href="{{ route('admin.returns.show', $ret->id) }}" class="hover:text-blue-600 hover:underline">
                                        #{{ $ret->return_number }}
                                    </a>
                                </div>
                                <div class="text-[11px] text-slate-400 mt-0.5">
                                    Order: <a href="{{ route('admin.orders.show', $ret->order_id) }}" class="font-bold text-slate-600 hover:underline">#{{ $ret->order->order_number }}</a>
                                </div>
                            </td>

                            {{-- Customer --}}
                            <td class="py-4 px-4">
                                <div class="font-bold text-slate-900">{{ $ret->order->address->name ?? $ret->user->name }}</div>
                                <div class="text-[11px] text-slate-400 font-mono">{{ $ret->order->address->phone ?? $ret->user->phone }}</div>
                            </td>

                            {{-- Type & Reason --}}
                            <td class="py-4 px-4">
                                <span class="inline-block px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $ret->type === 'exchange' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-purple-50 text-purple-700 border border-purple-200' }}">
                                    {{ $ret->type }}
                                </span>
                                <p class="text-[11px] text-slate-500 mt-1 line-clamp-1" title="{{ $ret->reason_label }}">
                                    {{ $ret->reason_label }}
                                </p>
                            </td>

                            {{-- Items & Refund Value --}}
                            <td class="py-4 px-4">
                                <div class="font-bold text-slate-900">{{ $ret->items->count() }} {{ Str::plural('drop', $ret->items->count()) }}</div>
                                @if($ret->refund_amount > 0)
                                    <span class="text-[11px] font-mono font-extrabold text-emerald-700">₹{{ number_format($ret->refund_amount, 2) }}</span>
                                @else
                                    <span class="text-[10px] text-slate-400 uppercase">Size Exchange</span>
                                @endif
                            </td>

                            {{-- Status Badge --}}
                            <td class="py-4 px-4 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold capitalize border {{ $badge['bg'] }} {{ $badge['text'] }} {{ $badge['border'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $badge['dot'] }}"></span>
                                    <span>{{ $badge['label'] }}</span>
                                </span>
                            </td>

                            {{-- Date --}}
                            <td class="py-4 px-4 text-slate-500 text-[11px]">
                                <div>{{ $ret->created_at->format('M d, Y') }}</div>
                                <div class="text-[10px] text-slate-400">{{ $ret->created_at->diffForHumans() }}</div>
                            </td>

                            {{-- Action --}}
                            <td class="py-4 px-5 text-right">
                                <a href="{{ route('admin.returns.show', $ret->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition-all shadow-2xs active:scale-95 cursor-pointer">
                                    <span>Manage</span>
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <div class="text-2xl mb-1">📦</div>
                                <p class="font-bold text-slate-600 text-sm">No Return Requests Found</p>
                                <p class="text-xs text-slate-400 mt-0.5">When customers submit returns or size exchanges, they will appear here.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($returns->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $returns->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
