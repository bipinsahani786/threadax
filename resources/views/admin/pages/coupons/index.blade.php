@extends('admin.layouts.app')

@section('title', 'Discount Coupons - Admin')
@section('page-title', 'Promotions & Discount Codes')

@section('content')
<div class="space-y-6 sm:space-y-8" x-data="{ copiedCode: null }">

    {{-- Executive Metric Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        
        {{-- Total Coupons --}}
        <a href="{{ route('admin.coupons.index') }}" class="group block bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs hover:border-slate-400 hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Coupons</span>
                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    🎟️
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900">{{ number_format($stats['total']) }}</span>
                <span class="text-[11px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">
                    Campaigns
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">All promotional discount codes</p>
        </a>

        {{-- Active Valid --}}
        <a href="{{ route('admin.coupons.index', ['status' => 'active']) }}" class="group block bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs hover:border-emerald-400 hover:shadow-md transition-all {{ request('status') === 'active' ? 'ring-2 ring-emerald-500 border-transparent' : '' }}">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Active Promos</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    🟢
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-emerald-950">{{ number_format($stats['active']) }}</span>
                <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                    Live
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Redeemable by customers at checkout</p>
        </a>

        {{-- Total Redemptions --}}
        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-purple-700 uppercase tracking-wider">Total Redemptions</span>
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-lg">
                    🏷️
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-purple-950">{{ number_format($stats['total_uses']) }}</span>
                <span class="text-[11px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-full border border-purple-200">
                    Orders Applied
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Checkout discounts claimed</p>
        </div>

        {{-- Types Split --}}
        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-blue-700 uppercase tracking-wider">Promo Formats</span>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg">
                    📊
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-blue-950">{{ $stats['percent_cnt'] }} <span class="text-sm font-normal text-slate-400">%</span></span>
                <span class="text-[11px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-200">
                    {{ $stats['flat_cnt'] }} Flat ₹
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Percentage vs fixed rate discounts</p>
        </div>

    </div>

    {{-- Main Coupons Ledger Card --}}
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden">
        
        {{-- Filters & Search Toolbar --}}
        <div class="p-5 sm:p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            
            <form action="{{ route('admin.coupons.index') }}" method="GET" class="flex flex-1 items-center gap-3 flex-wrap" x-data x-ref="filterForm">
                
                {{-- Search Bar --}}
                <div class="relative w-full sm:w-64">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                        🔍
                    </span>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search coupon code..." 
                           class="w-full pl-9 pr-4 py-2.5 bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold uppercase text-slate-900 outline-none transition-all shadow-2xs">
                </div>

                {{-- Type Filter --}}
                <select name="type" class="px-3.5 py-2.5 bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-700 outline-none shadow-2xs cursor-pointer" @change="$refs.filterForm.submit()">
                    <option value="all">All Discount Formats</option>
                    <option value="percent" {{ request('type') === 'percent' ? 'selected' : '' }}>Percentage (%)</option>
                    <option value="flat" {{ request('type') === 'flat' ? 'selected' : '' }}>Flat Amount (₹)</option>
                </select>

                {{-- Status Filter --}}
                <select name="status" class="px-3.5 py-2.5 bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-700 outline-none shadow-2xs cursor-pointer" @change="$refs.filterForm.submit()">
                    <option value="all">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active (Valid)</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive / Disabled</option>
                </select>

                <button type="submit" class="hidden">Search</button>

                @if(request()->hasAny(['search', 'type', 'status']))
                    <a href="{{ route('admin.coupons.index') }}" class="text-xs font-bold text-rose-600 hover:text-rose-700 hover:underline px-2 py-1 transition-colors">
                        ✕ Clear
                    </a>
                @endif
            </form>

            {{-- Create Coupon CTA --}}
            <div class="shrink-0 flex items-center gap-2.5">
                <a href="{{ route('admin.coupons.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-extrabold text-white bg-slate-900 hover:bg-slate-800 transition-all shadow-sm hover:shadow-md cursor-pointer">
                    <span>➕ New Coupon Code</span>
                </a>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-[11px] uppercase font-extrabold text-slate-400 border-b border-slate-100 tracking-wider">
                    <tr>
                        <th scope="col" class="px-6 py-4 min-w-[200px]">Coupon Code</th>
                        <th scope="col" class="px-5 py-4 min-w-[150px]">Discount Value</th>
                        <th scope="col" class="px-5 py-4 min-w-[120px]">Min. Order</th>
                        <th scope="col" class="px-5 py-4 min-w-[140px]">Usage / Cap</th>
                        <th scope="col" class="px-5 py-4 min-w-[150px]">Validity / Expiry</th>
                        <th scope="col" class="px-5 py-4 min-w-[100px]">Status</th>
                        <th scope="col" class="px-6 py-4 text-right min-w-[120px]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($coupons as $coupon)
                        <tr class="hover:bg-slate-50/60 transition-colors group">
                            
                            {{-- Code & Description --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-extrabold text-slate-900 bg-slate-100 border border-slate-200 px-3 py-1 rounded-xl text-xs tracking-wider">
                                        {{ $coupon->code }}
                                    </span>
                                    <button type="button" 
                                            @click="navigator.clipboard.writeText('{{ $coupon->code }}'); copiedCode = '{{ $coupon->code }}'; setTimeout(() => copiedCode = null, 2000)" 
                                            class="text-slate-400 hover:text-slate-900 p-1 rounded-md hover:bg-slate-100 transition-colors"
                                            title="Copy Code">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    </button>
                                </div>
                                @if($coupon->description)
                                    <p class="text-[11px] text-slate-400 mt-1 leading-snug">{{ $coupon->description }}</p>
                                @endif
                            </td>

                            {{-- Discount Value --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($coupon->type === 'percent')
                                    <div class="font-extrabold text-emerald-600 text-sm">
                                        {{ number_format($coupon->value) }}% OFF
                                    </div>
                                    @if($coupon->max_discount_amount)
                                        <div class="text-[10px] text-slate-400 font-bold">
                                            Max cap: ₹{{ number_format($coupon->max_discount_amount) }}
                                        </div>
                                    @endif
                                @else
                                    <div class="font-extrabold text-emerald-600 text-sm">
                                        ₹{{ number_format($coupon->value) }} FLAT OFF
                                    </div>
                                @endif
                            </td>

                            {{-- Min Order --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($coupon->min_order_amount > 0)
                                    <span class="font-bold text-slate-800 text-xs">₹{{ number_format($coupon->min_order_amount) }}</span>
                                @else
                                    <span class="text-slate-400 text-xs">No Minimum</span>
                                @endif
                            </td>

                            {{-- Uses & Cap --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-1.5">
                                    <span class="font-extrabold text-slate-900 text-xs">{{ $coupon->used_count }}</span>
                                    <span class="text-slate-400 text-xs">/ {{ $coupon->max_uses ? $coupon->max_uses . ' max' : '∞ unltd' }}</span>
                                </div>
                                <div class="text-[10px] text-slate-400 mt-0.5">
                                    Max {{ $coupon->max_uses_per_user ?? 1 }}/user
                                </div>
                            </td>

                            {{-- Expiry --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($coupon->expires_at)
                                    <div class="text-xs font-bold {{ $coupon->expires_at->isPast() ? 'text-rose-600' : 'text-slate-700' }}">
                                        {{ $coupon->expires_at->format('M d, Y') }}
                                    </div>
                                    <div class="text-[10px] text-slate-400">
                                        {{ $coupon->expires_at->diffForHumans() }}
                                    </div>
                                @else
                                    <span class="text-xs font-bold text-slate-500">♾️ Never Expires</span>
                                @endif
                            </td>

                            {{-- Status Badge --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($coupon->isValid())
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> ACTIVE
                                    </span>
                                @elseif($coupon->expires_at && $coupon->expires_at->isPast())
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-50 text-rose-700 border border-rose-200">
                                        EXPIRED
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-600 border border-slate-200">
                                        DISABLED
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    
                                    {{-- Edit --}}
                                    <a href="{{ route('admin.coupons.edit', $coupon) }}" 
                                       title="Edit Coupon" 
                                       class="p-2 rounded-xl text-slate-400 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete coupon {{ $coupon->code }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer" title="Delete Coupon">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 mb-3 text-2xl">
                                    🎟️
                                </div>
                                <h3 class="text-sm font-extrabold text-slate-900 mb-1">No coupons found</h3>
                                <p class="text-xs text-slate-400 mb-4">No discount promotions match your filters.</p>
                                <a href="{{ route('admin.coupons.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-slate-900 hover:bg-slate-800">
                                    ➕ Create First Coupon
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($coupons->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="text-xs text-slate-500 font-medium">
                    Showing <strong>{{ $coupons->firstItem() }}</strong> to <strong>{{ $coupons->lastItem() }}</strong> of <strong>{{ $coupons->total() }}</strong> coupons
                </div>
                <div>
                    {{ $coupons->links() }}
                </div>
            </div>
        @endif

    </div>

</div>
@endsection
