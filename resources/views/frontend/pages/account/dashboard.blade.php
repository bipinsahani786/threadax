@extends('frontend.layouts.account')

@section('account_content')

@if(!$profileComplete)
<div class="bg-brand-dark rounded-xl p-5 sm:p-6 mb-6 sm:mb-8 relative overflow-hidden flex flex-col md:flex-row items-center gap-4 sm:gap-6 shadow-sm">
    {{-- Background Pattern --}}
    <div class="absolute right-0 top-0 w-1/2 h-full opacity-10 pointer-events-none">
        <svg viewBox="0 0 100 100" class="w-full h-full text-white" fill="currentColor">
            <circle cx="80" cy="20" r="40" />
            <circle cx="20" cy="80" r="40" />
        </svg>
    </div>

    <div class="relative z-10 flex-1 w-full text-center md:text-left">
        <div class="flex items-center justify-center md:justify-start gap-2.5 mb-2">
            <span class="w-6 h-6 rounded-full bg-yellow-500/20 text-yellow-400 flex items-center justify-center shrink-0">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </span>
            <h3 class="text-base sm:text-lg font-heading font-bold text-white">Complete Your Profile</h3>
        </div>
        <p class="text-xs sm:text-sm text-white/70 max-w-lg mx-auto md:mx-0 leading-relaxed">Complete your profile to unlock faster checkout, personalized recommendations, and exclusive drops.</p>
    </div>

    <div class="relative z-10 w-full md:w-auto shrink-0 bg-white/10 backdrop-blur-md rounded-xl p-3.5 sm:p-4 border border-white/10 flex flex-col items-center justify-center min-w-[200px]">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-brand-dark border-2 border-white/20 flex items-center justify-center relative">
                <svg class="absolute inset-0 w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                    <path class="text-white/10" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3" />
                    <path class="text-white" stroke-dasharray="{{ $profilePercent }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3" />
                </svg>
                <span class="text-white font-bold text-xs">{{ $profilePercent }}%</span>
            </div>
            <div>
                <p class="text-white text-xs font-bold uppercase tracking-wider mb-0.5">{{ collect($profileChecks)->filter(fn($c) => $c['done'])->count() }} / {{ count($profileChecks) }} Done</p>
                @php $nextTask = collect($profileChecks)->firstWhere('done', false); @endphp
                @if($nextTask)
                    <a href="{{ route($nextTask['route']) }}" class="text-[11px] text-white/80 font-semibold hover:text-white underline">Next: {{ $nextTask['label'] }} →</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

{{-- Stats Cards Row --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6 sm:mb-8">
    
    {{-- Total Orders --}}
    <div class="bg-white rounded-xl border border-brand-border p-4 sm:p-5 hover:shadow-sm transition-all group relative overflow-hidden">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-brand-muted">Total Orders</span>
            <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/></svg>
            </div>
        </div>
        <p class="text-2xl sm:text-3xl font-extrabold font-heading text-brand-dark tracking-tight">{{ $orderCount }}</p>
    </div>

    {{-- Total Spent --}}
    <div class="bg-white rounded-xl border border-brand-border p-4 sm:p-5 hover:shadow-sm transition-all group relative overflow-hidden">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-brand-muted">Total Spent</span>
            <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
            </div>
        </div>
        <p class="text-2xl sm:text-3xl font-extrabold font-heading text-brand-dark tracking-tight">₹{{ number_format($totalSpent) }}</p>
    </div>

    {{-- Saved Addresses --}}
    <div class="bg-white rounded-xl border border-brand-border p-4 sm:p-5 hover:shadow-sm transition-all group relative overflow-hidden">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-brand-muted">Addresses</span>
            <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
            </div>
        </div>
        <p class="text-2xl sm:text-3xl font-extrabold font-heading text-brand-dark tracking-tight">{{ $user->addresses()->count() }}</p>
    </div>

    {{-- Wishlist --}}
    <div class="bg-white rounded-xl border border-brand-border p-4 sm:p-5 hover:shadow-sm transition-all group relative overflow-hidden">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-brand-muted">Wishlist</span>
            <div class="w-8 h-8 rounded-lg bg-rose-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
            </div>
        </div>
        <p class="text-2xl sm:text-3xl font-extrabold font-heading text-brand-dark tracking-tight">{{ $wishlistCount }}</p>
    </div>

</div>

{{-- Quick Actions --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 sm:gap-3 mb-6 sm:mb-8">
    <a href="{{ route('frontend.products.index') }}" class="flex items-center gap-2.5 bg-white border border-brand-border rounded-xl px-3.5 py-3 hover:border-brand-dark transition-all group">
        <span class="w-8 h-8 rounded-lg bg-brand-light flex items-center justify-center shrink-0 group-hover:bg-brand-dark group-hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.15c0 .415.336.75.75.75z"/></svg>
        </span>
        <span class="text-xs font-bold text-brand-dark">Shop Now</span>
    </a>
    <a href="{{ route('account.orders') }}" class="flex items-center gap-2.5 bg-white border border-brand-border rounded-xl px-3.5 py-3 hover:border-brand-dark transition-all group">
        <span class="w-8 h-8 rounded-lg bg-brand-light flex items-center justify-center shrink-0 group-hover:bg-brand-dark group-hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
        </span>
        <span class="text-xs font-bold text-brand-dark">My Orders</span>
    </a>
    <a href="{{ route('account.addresses') }}" class="flex items-center gap-2.5 bg-white border border-brand-border rounded-xl px-3.5 py-3 hover:border-brand-dark transition-all group">
        <span class="w-8 h-8 rounded-lg bg-brand-light flex items-center justify-center shrink-0 group-hover:bg-brand-dark group-hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
        </span>
        <span class="text-xs font-bold text-brand-dark">Addresses</span>
    </a>
    <a href="{{ route('account.profile') }}" class="flex items-center gap-2.5 bg-white border border-brand-border rounded-xl px-3.5 py-3 hover:border-brand-dark transition-all group">
        <span class="w-8 h-8 rounded-lg bg-brand-light flex items-center justify-center shrink-0 group-hover:bg-brand-dark group-hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
        </span>
        <span class="text-xs font-bold text-brand-dark">Edit Profile</span>
    </a>
</div>

{{-- Recent Orders --}}
<div class="bg-white rounded-xl border border-brand-border overflow-hidden">
    <div class="px-5 py-4 border-b border-brand-border flex items-center justify-between bg-brand-off-white">
        <div>
            <h3 class="text-sm sm:text-base font-heading font-bold text-brand-dark">Recent Orders</h3>
            <p class="text-[11px] text-brand-muted mt-0.5">Your last 3 orders</p>
        </div>
        <a href="{{ route('account.orders') }}" class="text-xs font-bold text-brand-dark hover:underline flex items-center gap-1 group">
            View All
            <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
        </a>
    </div>

    @if($recentOrders->count() > 0)
        <div class="divide-y divide-brand-border/60">
            @foreach($recentOrders as $order)
                <a href="{{ route('account.orders.show', $order->id) }}" class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 px-4 sm:px-5 py-3.5 sm:py-4 hover:bg-brand-off-white transition-colors group">
                    
                    <div class="flex items-center justify-between sm:justify-start w-full sm:w-auto gap-3">
                        {{-- Order Image Stack --}}
                        <div class="flex -space-x-2 shrink-0">
                            @foreach($order->items->take(3) as $item)
                                <div class="w-10 h-10 rounded-lg border border-white bg-brand-light overflow-hidden shadow-xs">
                                    @if($item->variant && $item->variant->product && $item->variant->product->primaryImage)
                                        <img src="{{ $item->variant->product->primaryImage->url }}" alt="{{ $item->variant->product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-brand-light flex items-center justify-center">
                                            <svg class="w-4 h-4 text-brand-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5"/></svg>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                            @if($order->items->count() > 3)
                                <div class="w-10 h-10 rounded-lg border border-white bg-brand-dark flex items-center justify-center shadow-xs">
                                    <span class="text-white text-[10px] font-bold">+{{ $order->items->count() - 3 }}</span>
                                </div>
                            @endif
                        </div>
                        
                        {{-- Price + Arrow (Mobile only) --}}
                        <div class="text-right shrink-0 flex sm:hidden items-center gap-2">
                            <p class="font-bold text-sm text-brand-dark">₹{{ number_format($order->total) }}</p>
                            <svg class="w-4 h-4 text-brand-muted group-hover:text-brand-dark group-hover:translate-x-0.5 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                        </div>
                    </div>

                    {{-- Order Info --}}
                    <div class="flex-1 min-w-0 w-full">
                        <div class="flex items-center gap-2 mb-0.5">
                            <p class="font-bold text-xs sm:text-sm text-brand-dark truncate">{{ $order->order_number ?? 'TX-'.$order->id }}</p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider
                                @if($order->status === 'delivered') bg-green-100 text-green-700
                                @elseif($order->status === 'cancelled') bg-red-100 text-red-700
                                @elseif($order->status === 'shipped') bg-blue-100 text-blue-700
                                @else bg-amber-100 text-amber-700 @endif
                            ">{{ $order->status }}</span>
                        </div>
                        <p class="text-[11px] text-brand-muted">{{ $order->items->count() }} items · {{ $order->created_at->format('M d, Y') }}</p>
                    </div>

                    {{-- Price + Arrow (Desktop only) --}}
                    <div class="text-right shrink-0 hidden sm:flex items-center gap-3">
                        <p class="font-bold text-sm text-brand-dark">₹{{ number_format($order->total) }}</p>
                        <svg class="w-4 h-4 text-brand-muted group-hover:text-brand-dark group-hover:translate-x-0.5 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="px-5 py-12 text-center">
            <div class="w-12 h-12 rounded-xl bg-brand-light flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-brand-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/></svg>
            </div>
            <h4 class="font-heading font-bold text-sm text-brand-dark mb-1">No Orders Yet</h4>
            <p class="text-xs text-brand-muted mb-4">Start shopping to see your orders here!</p>
            <a href="{{ route('frontend.products.index') }}" class="inline-flex items-center gap-2 bg-brand-dark text-white text-xs font-bold px-4 py-2 rounded-lg hover:bg-brand-text transition-colors">
                Start Shopping
            </a>
        </div>
    @endif
</div>

@endsection
