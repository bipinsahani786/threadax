@extends('frontend.layouts.account')

@section('account_content')
<div class="bg-white border border-brand-border rounded-2xl p-4 sm:p-6 lg:p-7 shadow-xs">
    
    {{-- Header --}}
    <div class="mb-5 sm:mb-6 flex flex-wrap items-center justify-between gap-2 border-b border-brand-border/60 pb-4">
        <div>
            <h2 class="text-base sm:text-lg font-heading font-extrabold text-brand-dark flex items-center gap-2">
                <span>My Orders</span>
                <span class="text-xs font-normal text-brand-muted">📦</span>
            </h2>
            <p class="text-xs sm:text-sm text-brand-muted mt-0.5">Track, view and manage your recent streetwear purchases.</p>
        </div>
        @if($orders->count() > 0)
            <span class="text-xs font-extrabold bg-brand-off-white border border-brand-border px-3 py-1 rounded-full text-brand-dark shadow-2xs">
                {{ $orders->total() }} {{ Str::plural('Order', $orders->total()) }}
            </span>
        @endif
    </div>

    @if($orders->count() > 0)
        <div class="space-y-4 sm:space-y-6">
            @foreach($orders as $order)
                @php
                    $status = strtolower($order->status);
                    $stepIndex = 1;
                    if (in_array($status, ['confirmed', 'processing'])) $stepIndex = 2;
                    elseif ($status === 'shipped') $stepIndex = 3;
                    elseif ($status === 'delivered') $stepIndex = 4;
                    elseif ($status === 'cancelled') $stepIndex = 0;
                @endphp

                <div class="border border-brand-border rounded-2xl overflow-hidden bg-white shadow-2xs hover:shadow-sm hover:border-brand-dark/40 transition-all duration-200">
                    
                    {{-- TOP HEADER BAR --}}
                    <div class="bg-brand-off-white/80 px-4 py-3.5 sm:px-5 sm:py-4 border-b border-brand-border/70 flex flex-wrap items-center justify-between gap-2.5">
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs">
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] font-bold text-brand-muted uppercase tracking-wider">Order</span>
                                <span class="font-mono font-extrabold text-brand-dark text-xs sm:text-sm">#{{ $order->order_number ?? 'TX-'.$order->id }}</span>
                            </div>
                            <span class="text-brand-border hidden sm:inline">•</span>
                            <div class="text-brand-muted text-[11px] sm:text-xs">
                                <span>Placed on {{ $order->created_at->format('M d, Y') }}</span>
                                <span class="text-[10px] text-brand-muted/70">({{ $order->created_at->format('h:i A') }})</span>
                            </div>
                        </div>

                        {{-- Status & Payment Badges --}}
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] sm:text-xs font-bold capitalize border
                                @if($status === 'delivered') bg-emerald-50 text-emerald-700 border-emerald-200
                                @elseif($status === 'cancelled') bg-rose-50 text-rose-700 border-rose-200
                                @elseif($status === 'shipped') bg-indigo-50 text-indigo-700 border-indigo-200
                                @elseif($status === 'confirmed') bg-sky-50 text-sky-700 border-sky-200
                                @else bg-amber-50 text-amber-700 border-amber-200 @endif
                            ">
                                <span class="w-1.5 h-1.5 rounded-full
                                    @if($status === 'delivered') bg-emerald-500
                                    @elseif($status === 'cancelled') bg-rose-500
                                    @elseif($status === 'shipped') bg-indigo-500
                                    @elseif($status === 'confirmed') bg-sky-500
                                    @else bg-amber-500 animate-pulse @endif
                                "></span>
                                <span>{{ $order->status }}</span>
                            </span>

                            @if($order->payment_method === 'cod')
                                <span class="hidden sm:inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-white border border-brand-border text-brand-muted">
                                    💵 COD
                                </span>
                            @else
                                <span class="hidden sm:inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-white border border-brand-border text-emerald-700">
                                    ✓ Paid Online
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- ITEMS LIST --}}
                    <div class="divide-y divide-brand-border/40 p-4 sm:p-5">
                        @foreach($order->items as $item)
                            @php
                                $product = $item->variant?->product;
                            @endphp
                            <div class="flex gap-3.5 sm:gap-4.5 items-start py-2.5 first:pt-0 last:pb-0">
                                {{-- Product Thumbnail --}}
                                <div class="w-16 h-20 sm:w-20 sm:h-24 rounded-xl border border-brand-border overflow-hidden bg-brand-off-white shrink-0 shadow-2xs">
                                    @if($product && $product->primaryImage)
                                        <img src="{{ $product->primaryImage->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-[10px] text-brand-muted">No Image</div>
                                    @endif
                                </div>

                                {{-- Product Info --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start gap-2">
                                        <h3 class="text-xs sm:text-sm font-bold text-brand-dark leading-snug line-clamp-2">
                                            @if($product)
                                                <a href="{{ route('frontend.products.show', $product->slug) }}" class="hover:underline">
                                                    {{ $product->name }}
                                                </a>
                                            @else
                                                <span>{{ $item->name ?? 'Streetwear Item' }}</span>
                                            @endif
                                        </h3>
                                        <span class="text-xs sm:text-sm font-extrabold text-brand-dark shrink-0 font-heading">
                                            ₹{{ number_format($item->price * $item->quantity) }}
                                        </span>
                                    </div>

                                    {{-- Variants & Quantity Pills --}}
                                    <div class="flex flex-wrap items-center gap-1.5 mt-1.5">
                                        @if($item->variant?->size)
                                            <span class="inline-flex items-center gap-1 bg-brand-off-white border border-brand-border px-2 py-0.5 rounded-md text-[10px] font-semibold text-brand-dark">
                                                Size: <strong>{{ $item->variant->size }}</strong>
                                            </span>
                                        @endif
                                        @if($item->variant?->color)
                                            <span class="inline-flex items-center gap-1 bg-brand-off-white border border-brand-border px-2 py-0.5 rounded-md text-[10px] font-semibold text-brand-dark">
                                                Color: <strong>{{ $item->variant->color }}</strong>
                                            </span>
                                        @endif
                                        <span class="inline-flex items-center gap-1 bg-brand-off-white border border-brand-border px-2 py-0.5 rounded-md text-[10px] font-semibold text-brand-dark">
                                            Qty: <strong>{{ $item->quantity }}</strong>
                                        </span>
                                    </div>

                                    @if($item->quantity > 1)
                                        <span class="text-[10px] text-brand-muted mt-1 block">
                                            ₹{{ number_format($item->price) }} each
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- VISUAL DELIVERY TRACKER (If not cancelled) --}}
                    @if($status !== 'cancelled')
                        <div class="px-4 py-3 sm:px-5 sm:py-3.5 bg-brand-off-white/40 border-t border-brand-border/60">
                            <div class="flex items-center justify-between text-[10px] sm:text-[11px] font-bold text-brand-muted uppercase tracking-wider mb-2">
                                <span class="flex items-center gap-1 text-brand-dark font-extrabold">
                                    <span>🚚</span>
                                    <span>Status Progress</span>
                                </span>
                                <span>
                                    @if($status === 'delivered')
                                        <span class="text-emerald-700">Delivered Successfully</span>
                                    @elseif($status === 'shipped')
                                        <span class="text-indigo-700">Out for Delivery</span>
                                    @else
                                        <span class="text-amber-700">Est. 3-5 Business Days</span>
                                    @endif
                                </span>
                            </div>

                            {{-- 4 Step Visual Bar --}}
                            <div class="grid grid-cols-4 gap-1 sm:gap-2 relative pt-1">
                                @php
                                    $steps = ['Placed', 'Processing', 'Shipped', 'Delivered'];
                                @endphp
                                @foreach($steps as $idx => $step)
                                    @php $currentStepNum = $idx + 1; @endphp
                                    <div class="text-center">
                                        <div class="h-1.5 rounded-full mb-1.5 transition-colors
                                            {{ $currentStepNum <= $stepIndex ? ($stepIndex === 4 ? 'bg-emerald-500' : 'bg-brand-dark') : 'bg-brand-border' }}">
                                        </div>
                                        <span class="text-[9px] sm:text-[10px] font-bold block truncate
                                            {{ $currentStepNum <= $stepIndex ? 'text-brand-dark' : 'text-brand-muted/70' }}">
                                            {{ $step }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- CARD FOOTER: TOTAL & ACTION BUTTONS --}}
                    <div class="px-4 py-3 sm:px-5 sm:py-3.5 bg-white border-t border-brand-border/70 flex flex-wrap items-center justify-between gap-3">
                        <div class="text-xs">
                            <span class="text-brand-muted text-[11px] block">Order Total</span>
                            <div class="flex items-baseline gap-1.5">
                                <span class="font-extrabold font-heading text-sm sm:text-base text-brand-dark">₹{{ number_format($order->total, 2) }}</span>
                                <span class="text-[10px] text-brand-muted font-normal">({{ $order->items->sum('quantity') }} {{ Str::plural('item', $order->items->sum('quantity')) }})</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            {{-- Invoice download link (Only available once order is Delivered) --}}
                            @if($status === 'delivered')
                                <a href="{{ route('account.orders.invoice', $order->id) }}" 
                                   class="inline-flex items-center justify-center gap-1.5 bg-brand-off-white border border-brand-border text-brand-dark hover:bg-white text-xs font-bold px-3.5 py-2.5 rounded-xl transition-all shadow-2xs hover:border-brand-dark/40 active:scale-95 flex-1 sm:flex-initial cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                    <span>Invoice</span>
                                </a>
                            @endif

                            {{-- View Details CTA --}}
                            <a href="{{ route('account.orders.show', $order->id) }}" 
                               class="inline-flex items-center justify-center gap-1.5 bg-brand-dark text-white hover:bg-brand-text text-xs font-bold uppercase tracking-wider px-4 py-2.5 rounded-xl transition-all shadow-sm hover:shadow active:scale-95 flex-1 sm:flex-initial cursor-pointer">
                                <span>View Details</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                            </a>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($orders->hasPages())
            <div class="mt-8 pt-4 border-t border-brand-border/60">
                {{ $orders->links() }}
            </div>
        @endif

    @else
        <div class="text-center py-12 sm:py-16 border border-dashed border-brand-border rounded-2xl bg-brand-off-white/30">
            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-white border border-brand-border flex items-center justify-center mx-auto mb-3 shadow-2xs">
                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-brand-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z" />
                </svg>
            </div>
            <h3 class="text-sm sm:text-base font-bold text-brand-dark">No Orders Placed Yet</h3>
            <p class="mt-1 text-xs text-brand-muted max-w-xs mx-auto">Explore our street-tailored drops and place your first order.</p>
            <div class="mt-5">
                <a href="{{ route('frontend.products.index') }}" class="inline-flex items-center gap-2 bg-brand-dark text-white text-xs font-bold uppercase tracking-wider px-5 py-2.5 rounded-xl hover:bg-brand-text transition-all shadow-xs active:scale-95">
                    <span>Start Shopping</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
