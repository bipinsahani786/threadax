@extends('frontend.layouts.account')

@section('account_content')
<div class="bg-white border border-brand-border rounded-2xl p-4 sm:p-6 lg:p-7 shadow-xs">
    
    {{-- Header --}}
    <div class="mb-5 sm:mb-6 flex flex-wrap items-center justify-between gap-2 border-b border-brand-border/60 pb-4">
        <div>
            <h2 class="text-base sm:text-lg font-heading font-extrabold text-brand-dark flex items-center gap-2">
                <span>Returns & Exchanges</span>
                <span class="text-xs font-normal text-brand-muted">🔄</span>
            </h2>
            <p class="text-xs sm:text-sm text-brand-muted mt-0.5">Track your active return pickups, size swaps and refund statuses.</p>
        </div>
        @if($returns->count() > 0)
            <span class="text-xs font-extrabold bg-brand-off-white border border-brand-border px-3 py-1 rounded-full text-brand-dark shadow-2xs">
                {{ $returns->count() }} {{ Str::plural('Request', $returns->count()) }}
            </span>
        @endif
    </div>

    @if($returns->count() > 0)
        <div class="space-y-4 sm:space-y-6">
            @foreach($returns as $ret)
                @php
                    $badge = $ret->status_badge;
                @endphp
                <div class="border border-brand-border rounded-2xl overflow-hidden bg-white shadow-2xs hover:shadow-sm hover:border-brand-dark/40 transition-all duration-200">
                    
                    {{-- Header Bar --}}
                    <div class="bg-brand-off-white/80 px-4 py-3.5 sm:px-5 sm:py-4 border-b border-brand-border/70 flex flex-wrap items-center justify-between gap-2.5">
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs">
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] font-bold text-brand-muted uppercase tracking-wider">Request</span>
                                <span class="font-mono font-extrabold text-brand-dark text-xs sm:text-sm">#{{ $ret->return_number }}</span>
                            </div>
                            <span class="text-brand-border hidden sm:inline">•</span>
                            <div class="flex items-center gap-1 text-[11px] sm:text-xs text-brand-muted">
                                <span>Order</span>
                                <a href="{{ route('account.orders.show', $ret->order_id) }}" class="font-mono font-bold text-brand-dark hover:underline">
                                    #{{ $ret->order->order_number }}
                                </a>
                            </div>
                            <span class="text-brand-border hidden sm:inline">•</span>
                            <span class="text-[11px] sm:text-xs text-brand-muted">{{ $ret->created_at->format('M d, Y') }}</span>
                        </div>

                        {{-- Badges --}}
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] sm:text-xs font-bold capitalize border {{ $badge['bg'] }} {{ $badge['text'] }} {{ $badge['border'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $badge['dot'] }}"></span>
                                <span>{{ $badge['label'] }}</span>
                            </span>

                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase bg-white border border-brand-border text-brand-dark">
                                {{ $ret->type === 'exchange' ? '🔄 Exchange' : '↩ Return' }}
                            </span>
                        </div>
                    </div>

                    {{-- Items List --}}
                    <div class="divide-y divide-brand-border/40 p-4 sm:p-5">
                        @foreach($ret->items as $item)
                            @php
                                $product = $item->product;
                                $primaryImg = $product?->primaryImage ?? $product?->images->first();
                                $imgUrl = $primaryImg?->url;
                            @endphp
                            <div class="flex gap-3.5 sm:gap-4.5 items-start py-2.5 first:pt-0 last:pb-0">
                                <div class="w-14 h-16 sm:w-16 sm:h-20 rounded-xl border border-brand-border overflow-hidden bg-brand-off-white shrink-0 shadow-2xs">
                                    @if($imgUrl)
                                        <img src="{{ $imgUrl }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-[10px] text-brand-muted font-bold">TX</div>
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start gap-2">
                                        <h4 class="text-xs sm:text-sm font-bold text-brand-dark leading-snug">
                                            {{ $product->name ?? 'Streetwear Drop' }}
                                        </h4>
                                        <span class="text-xs sm:text-sm font-extrabold text-brand-dark font-heading">
                                            ₹{{ number_format($item->total, 2) }}
                                        </span>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-1.5 mt-1.5 text-[10px] font-semibold text-brand-muted">
                                        @if($item->originalVariant?->size)
                                            <span class="bg-brand-off-white border border-brand-border px-2 py-0.5 rounded text-brand-dark">
                                                Size: <strong>{{ $item->originalVariant->size }}</strong>
                                            </span>
                                        @endif
                                        <span>Qty: <strong>{{ $item->quantity }}</strong></span>
                                        @if($item->exchangeVariant)
                                            <span class="bg-blue-50 text-blue-700 border border-blue-200 px-2 py-0.5 rounded font-bold">
                                                ➔ New Size: {{ $item->exchangeVariant->size }}
                                            </span>
                                        @endif
                                    </div>

                                    <p class="text-[11px] text-brand-muted mt-1">Reason: <span class="text-brand-dark font-medium">{{ $ret->reason_label }}</span></p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Footer with CTA --}}
                    <div class="px-4 py-3 sm:px-5 sm:py-3.5 bg-white border-t border-brand-border/70 flex flex-wrap items-center justify-between gap-3">
                        <div class="text-xs">
                            @if($ret->refund_amount > 0)
                                <span class="text-brand-muted text-[11px] block">Refund Value</span>
                                <span class="font-extrabold font-heading text-sm text-emerald-700">₹{{ number_format($ret->refund_amount, 2) }}</span>
                            @else
                                <span class="text-brand-muted text-[11px] block">Action</span>
                                <span class="font-bold text-xs text-brand-dark">Size Exchange</span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('account.returns.show', $ret->id) }}" 
                               class="inline-flex items-center justify-center gap-1.5 bg-brand-dark text-white hover:bg-brand-text text-xs font-bold uppercase tracking-wider px-4 py-2.5 rounded-xl transition-all shadow-sm hover:shadow active:scale-95 cursor-pointer">
                                <span>Track Return Status</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                            </a>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12 sm:py-16 border border-dashed border-brand-border rounded-2xl bg-brand-off-white/30">
            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-white border border-brand-border flex items-center justify-center mx-auto mb-3 shadow-2xs">
                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-brand-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
            </div>
            <h3 class="text-sm sm:text-base font-bold text-brand-dark">No Return or Exchange Requests</h3>
            <p class="mt-1 text-xs text-brand-muted max-w-xs mx-auto">You can request a return or size exchange on any delivered order within 7 days.</p>
            <div class="mt-5">
                <a href="{{ route('account.orders') }}" class="inline-flex items-center gap-2 bg-brand-dark text-white text-xs font-bold uppercase tracking-wider px-5 py-2.5 rounded-xl hover:bg-brand-text transition-all shadow-xs active:scale-95">
                    <span>View Delivered Orders</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
