@extends('frontend.layouts.app')

@section('title', 'Track Your Order — THREADAX')

@section('content')
<div class="min-h-screen bg-brand-dark text-brand-off-white py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        
        {{-- Page Header --}}
        <div class="text-center mb-10">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-white/10 text-brand-gold border border-white/15 mb-3">
                <span>🛰️</span>
                <span>Live Shipment Radar</span>
            </span>
            <h1 class="text-3xl sm:text-4xl font-heading font-black tracking-tight text-white uppercase">
                Track Your Drop
            </h1>
            <p class="text-sm text-brand-muted max-w-md mx-auto mt-2">
                Enter your Order ID (e.g. <span class="font-mono text-white">TX-5N6T7J8D</span>) or AWB Tracking Number to check real-time dispatch milestones.
            </p>
        </div>

        {{-- Search Card --}}
        <div class="bg-brand-charcoal/80 border border-white/10 backdrop-blur-md rounded-2xl p-6 sm:p-8 mb-10 shadow-2xl">
            <form method="GET" action="{{ route('frontend.tracking') }}" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                    <div class="sm:col-span-7">
                        <label for="order_number" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">
                            Order ID or AWB Tracking Number <span class="text-rose-400">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">📦</span>
                            <input type="text" 
                                   name="order_number" 
                                   id="order_number" 
                                   value="{{ request('order_number') }}" 
                                   placeholder="e.g. TX-5N6T7J8D or AWB9876543210" 
                                   required 
                                   class="w-full pl-10 pr-4 py-3 bg-brand-dark/80 border border-white/15 focus:border-white/40 rounded-xl text-sm font-mono text-white placeholder-slate-500 outline-none transition-all">
                        </div>
                    </div>

                    <div class="sm:col-span-5">
                        <label for="contact" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">
                            Email or Mobile (Optional)
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">📱</span>
                            <input type="text" 
                                   name="contact" 
                                   id="contact" 
                                   value="{{ request('contact') }}" 
                                   placeholder="e.g. 9876543210" 
                                   class="w-full pl-10 pr-4 py-3 bg-brand-dark/80 border border-white/15 focus:border-white/40 rounded-xl text-sm text-white placeholder-slate-500 outline-none transition-all">
                        </div>
                    </div>
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-white hover:bg-slate-100 text-slate-900 font-extrabold text-xs uppercase tracking-wider rounded-xl transition-all shadow-lg active:scale-95 cursor-pointer flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                        <span>Locate Shipment</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Search Results --}}
        @if($searched)
            @if($order)
                @php
                    $status = strtolower($order->status);
                    $stepIndex = 1;
                    if (in_array($status, ['confirmed', 'processing', 'packed'])) $stepIndex = 2;
                    elseif (in_array($status, ['shipped', 'in_transit', 'out_for_delivery'])) $stepIndex = 3;
                    elseif ($status === 'delivered') $stepIndex = 4;
                    elseif ($status === 'cancelled') $stepIndex = 0;
                @endphp

                <div class="bg-brand-charcoal/90 border border-white/10 rounded-2xl p-6 sm:p-8 shadow-2xl space-y-8">
                    
                    {{-- Order Header Bar --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-6 border-b border-white/10">
                        <div>
                            <span class="text-xs font-mono font-bold text-brand-gold uppercase tracking-wider">Verified Drop</span>
                            <h2 class="text-2xl font-heading font-black text-white">Order #{{ $order->order_number }}</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2.5">
                            @if($order->effective_courier)
                                <span class="px-3 py-1.5 rounded-xl bg-white/5 border border-white/15 text-xs font-bold text-slate-200">
                                    🚚 {{ $order->effective_courier }}
                                </span>
                            @endif
                            <span class="px-3.5 py-1.5 rounded-full text-xs font-black uppercase tracking-wider border
                                @if($status === 'delivered') bg-emerald-500/20 text-emerald-300 border-emerald-500/30
                                @elseif($status === 'cancelled') bg-rose-500/20 text-rose-300 border-rose-500/30
                                @elseif(in_array($status, ['shipped', 'in_transit', 'out_for_delivery'])) bg-indigo-500/20 text-indigo-300 border-indigo-500/30
                                @else bg-amber-500/20 text-amber-300 border-amber-500/30 @endif
                            ">
                                {{ $order->status }}
                            </span>
                        </div>
                    </div>

                    {{-- Stepper Progress Bar --}}
                    @if($status !== 'cancelled')
                        <div class="p-6 bg-brand-dark/80 border border-white/10 rounded-2xl">
                            <div class="flex justify-between items-center text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">
                                <span class="text-white font-extrabold flex items-center gap-2">
                                    <span>⚡</span>
                                    <span>Fulfillment Status</span>
                                </span>
                                <span>
                                    @if($status === 'delivered')
                                        <span class="text-emerald-400">Delivered on {{ $order->delivered_at ? $order->delivered_at->format('M d, Y') : 'Doorstep' }}</span>
                                    @elseif(in_array($status, ['shipped', 'in_transit', 'out_for_delivery']))
                                        <span class="text-indigo-400">In Transit — Expected in 2-3 Days</span>
                                    @else
                                        <span class="text-amber-400">Preparing Drop at Warehouse</span>
                                    @endif
                                </span>
                            </div>

                            <div class="grid grid-cols-4 gap-2 sm:gap-4 relative pt-2">
                                @php $steps = ['Order Placed', 'Processing', 'In Transit', 'Delivered']; @endphp
                                @foreach($steps as $idx => $step)
                                    @php $currentNum = $idx + 1; @endphp
                                    <div class="text-center">
                                        <div class="h-2 rounded-full mb-2 transition-all
                                            {{ $currentNum <= $stepIndex ? ($stepIndex === 4 ? 'bg-emerald-400' : 'bg-brand-gold') : 'bg-white/15' }}">
                                        </div>
                                        <span class="text-[11px] sm:text-xs font-bold block truncate
                                            {{ $currentNum <= $stepIndex ? 'text-white' : 'text-slate-500' }}">
                                            {{ $step }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Courier AWB & Tracking Info --}}
                    @if($order->effective_awb)
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-4 bg-white/5 border border-white/10 rounded-xl">
                            <div>
                                <p class="text-xs text-slate-400">Air Waybill (AWB) Number:</p>
                                <p class="font-mono text-sm font-bold text-white mt-0.5">{{ $order->effective_awb }}</p>
                            </div>
                            @if($order->effective_tracking_url)
                                <a href="{{ $order->effective_tracking_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition-all">
                                    <span>Track on Courier Site</span>
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                                </a>
                            @endif
                        </div>
                    @endif

                    {{-- Live Tracking Activity Timeline Feed --}}
                    <div>
                        <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span>📜</span>
                            <span>Detailed Transit Milestones</span>
                        </h3>

                        @if($order->trackings->isNotEmpty())
                            <div class="relative pl-6 space-y-6 before:absolute before:left-2.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-white/15">
                                @foreach($order->trackings as $track)
                                    @php $badge = $track->status_badge; @endphp
                                    <div class="relative flex items-start gap-4">
                                        <div class="absolute -left-6 mt-1 w-5 h-5 rounded-full bg-brand-charcoal border-2 border-brand-gold flex items-center justify-center text-[10px]">
                                            {{ $badge['icon'] }}
                                        </div>
                                        <div class="bg-brand-dark/60 border border-white/10 p-4 rounded-xl flex-1">
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 mb-1">
                                                <h4 class="text-sm font-bold text-white">{{ $track->title }}</h4>
                                                <span class="text-[11px] font-mono text-slate-400">
                                                    {{ $track->event_time ? $track->event_time->format('M d, Y • h:i A') : $track->created_at->format('M d, Y • h:i A') }}
                                                </span>
                                            </div>
                                            @if($track->location)
                                                <p class="text-xs text-brand-gold font-medium mb-1">📍 {{ $track->location }}</p>
                                            @endif
                                            @if($track->activity)
                                                <p class="text-xs text-slate-300 leading-relaxed">{{ $track->activity }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 bg-brand-dark/40 border border-white/10 rounded-xl text-slate-400 text-xs">
                                Initial processing underway at ThreadAX Fulfillment Hub. Milestones will appear here once dispatched.
                            </div>
                        @endif
                    </div>

                    {{-- Items In Package Preview --}}
                    <div class="pt-4 border-t border-white/10">
                        <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-3">Items in this Drop</h3>
                        <div class="divide-y divide-white/10">
                            @foreach($order->items as $item)
                                @php $product = $item->variant?->product; @endphp
                                <div class="py-3 flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-14 rounded-lg bg-white/5 border border-white/10 overflow-hidden shrink-0">
                                            @if($product && $product->primaryImage)
                                                <img src="{{ $product->primaryImage->url }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-[10px] text-slate-500 font-bold">TX</div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-white">{{ $product?->name ?? 'Drop Item' }}</p>
                                            <p class="text-[11px] text-slate-400 mt-0.5">
                                                @if($item->variant?->size) Size: {{ $item->variant->size }} @endif
                                                @if($item->variant?->color) | Color: {{ $item->variant->color }} @endif
                                                • Qty: {{ $item->quantity }}
                                            </p>
                                        </div>
                                    </div>
                                    <p class="text-xs font-heading font-extrabold text-white">₹{{ number_format($item->total ?? ($item->price * $item->quantity), 2) }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            @else
                <div class="text-center py-12 bg-brand-charcoal/60 border border-rose-500/20 rounded-2xl p-6">
                    <span class="text-3xl">🔍</span>
                    <h3 class="text-lg font-bold text-white mt-2">No Order Found</h3>
                    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                        We couldn't find an order matching <span class="font-mono text-white">{{ request('order_number') }}</span>. Please check the ID from your confirmation email / SMS and try again.
                    </p>
                </div>
            @endif
        @endif

    </div>
</div>
@endsection
