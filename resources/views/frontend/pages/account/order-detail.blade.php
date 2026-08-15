@extends('frontend.layouts.account')

@section('account_content')
<div class="bg-white border border-brand-border rounded-2xl p-4 sm:p-6 lg:p-7 shadow-xs">
    @php
        $status = strtolower($order->status);
        $stepIndex = 1;
        if (in_array($status, ['confirmed', 'processing', 'packed'])) $stepIndex = 2;
        elseif (in_array($status, ['shipped', 'in_transit', 'out_for_delivery'])) $stepIndex = 3;
        elseif ($status === 'delivered') $stepIndex = 4;
        elseif ($status === 'cancelled') $stepIndex = 0;
    @endphp

    {{-- Top Navigation & Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6 pb-5 border-b border-brand-border/60">
        <div>
            <a href="{{ route('account.orders') }}" class="text-xs font-bold text-brand-muted hover:text-brand-dark mb-2 inline-flex items-center gap-1.5 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                <span>Back to All Orders</span>
            </a>
            <h2 class="text-base sm:text-xl font-heading font-extrabold text-brand-dark">Order #{{ $order->order_number ?? 'TX-'.$order->id }}</h2>
            <p class="text-xs text-brand-muted mt-0.5">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            {{-- Invoice Download --}}
            <a href="{{ route('account.orders.invoice', $order->id) }}" class="inline-flex items-center gap-1.5 bg-brand-off-white border border-brand-border px-3.5 py-2 rounded-xl text-xs font-bold text-brand-dark hover:bg-white transition-all shadow-2xs hover:border-brand-dark cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                <span>Tax Invoice</span>
            </a>

            {{-- Status Pill --}}
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold capitalize border
                @if($status === 'delivered') bg-emerald-50 text-emerald-700 border-emerald-200
                @elseif($status === 'cancelled') bg-rose-50 text-rose-700 border-rose-200
                @elseif(in_array($status, ['shipped', 'in_transit', 'out_for_delivery'])) bg-indigo-50 text-indigo-700 border-indigo-200
                @elseif(in_array($status, ['confirmed', 'processing', 'packed'])) bg-sky-50 text-sky-700 border-sky-200
                @else bg-amber-50 text-amber-700 border-amber-200 @endif
            ">
                <span class="w-1.5 h-1.5 rounded-full
                    @if($status === 'delivered') bg-emerald-500
                    @elseif($status === 'cancelled') bg-rose-500
                    @elseif(in_array($status, ['shipped', 'in_transit', 'out_for_delivery'])) bg-indigo-500
                    @elseif(in_array($status, ['confirmed', 'processing', 'packed'])) bg-sky-500
                    @else bg-amber-500 animate-pulse @endif
                "></span>
                <span>{{ $order->status }}</span>
            </span>
        </div>
    </div>

    {{-- Visual Tracking Stepper --}}
    @if($status !== 'cancelled')
        <div class="mb-6 p-4 sm:p-5 bg-brand-off-white/60 border border-brand-border rounded-2xl">
            <div class="flex items-center justify-between text-xs font-bold text-brand-muted uppercase tracking-wider mb-2.5">
                <span class="flex items-center gap-1.5 text-brand-dark font-extrabold">
                    <span>🚚</span>
                    <span>Order Progress Tracker</span>
                </span>
                <span>
                    @if($status === 'delivered')
                        <span class="text-emerald-700 font-extrabold">Delivered on {{ $order->delivered_at ? $order->delivered_at->format('M d, Y') : 'Doorstep' }}</span>
                    @elseif(in_array($status, ['shipped', 'in_transit', 'out_for_delivery']))
                        <span class="text-indigo-700 font-extrabold">In Transit — Out for Delivery</span>
                    @else
                        <span class="text-amber-700">Estimated Delivery: 3-5 Business Days</span>
                    @endif
                </span>
            </div>

            <div class="grid grid-cols-4 gap-1.5 sm:gap-3 relative pt-1">
                @php $steps = ['Order Placed', 'Processing', 'In Transit', 'Delivered']; @endphp
                @foreach($steps as $idx => $step)
                    @php $currentStepNum = $idx + 1; @endphp
                    <div class="text-center">
                        <div class="h-2 rounded-full mb-1.5 transition-colors
                            {{ $currentStepNum <= $stepIndex ? ($stepIndex === 4 ? 'bg-emerald-500' : 'bg-brand-dark') : 'bg-brand-border' }}">
                        </div>
                        <span class="text-[10px] sm:text-xs font-bold block truncate
                            {{ $currentStepNum <= $stepIndex ? 'text-brand-dark' : 'text-brand-muted/70' }}">
                            {{ $step }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Courier & AWB Information Bar --}}
    @if($order->effective_awb)
        <div class="mb-6 p-4 bg-slate-900 text-white rounded-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-lg">
                    📦
                </div>
                <div>
                    <p class="text-xs text-slate-300 font-medium">Shipped via <strong class="text-white">{{ $order->effective_courier ?? 'Express Logistics' }}</strong></p>
                    <p class="text-xs font-mono font-bold text-amber-300 mt-0.5">AWB: {{ $order->effective_awb }}</p>
                </div>
            </div>
            @if($order->effective_tracking_url)
                <a href="{{ $order->effective_tracking_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white text-slate-900 text-xs font-bold hover:bg-slate-100 transition-all">
                    <span>Track on Courier Partner</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                </a>
            @endif
        </div>
    @endif

    {{-- Detailed Tracking Activity History --}}
    @if($order->trackings->isNotEmpty())
        <div class="border border-brand-border rounded-2xl p-4 sm:p-5 mb-6 bg-brand-off-white/40">
            <h3 class="text-xs font-extrabold text-brand-dark uppercase tracking-wider mb-4 flex items-center gap-2">
                <span>📍</span>
                <span>Live Shipment Timeline & Updates</span>
            </h3>

            <div class="relative pl-6 space-y-4 before:absolute before:left-2.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-brand-border">
                @foreach($order->trackings as $track)
                    @php $badge = $track->status_badge; @endphp
                    <div class="relative flex items-start gap-3">
                        <div class="absolute -left-6 mt-1 w-5 h-5 rounded-full bg-white border-2 border-brand-dark flex items-center justify-center text-[9px] shadow-2xs">
                            {{ $badge['icon'] }}
                        </div>
                        <div class="bg-white border border-brand-border p-3.5 rounded-xl flex-1 shadow-2xs">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 mb-1">
                                <h4 class="text-xs font-bold text-brand-dark">{{ $track->title }}</h4>
                                <span class="text-[10px] font-mono text-brand-muted">
                                    {{ $track->event_time ? $track->event_time->format('M d, Y • h:i A') : $track->created_at->format('M d, Y • h:i A') }}
                                </span>
                            </div>
                            @if($track->location)
                                <p class="text-[11px] font-semibold text-slate-700 mb-0.5">📍 {{ $track->location }}</p>
                            @endif
                            @if($track->activity)
                                <p class="text-xs text-brand-muted leading-relaxed">{{ $track->activity }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Ordered Items Section --}}
    <div class="border border-brand-border rounded-2xl overflow-hidden mb-6">
        <div class="bg-brand-off-white/80 px-4 sm:px-5 py-3 border-b border-brand-border flex items-center justify-between">
            <h3 class="text-xs font-extrabold text-brand-dark uppercase tracking-wider">Ordered Items</h3>
            <span class="text-xs font-bold text-brand-muted">{{ $order->items->sum('quantity') }} {{ Str::plural('Item', $order->items->sum('quantity')) }}</span>
        </div>
        <ul role="list" class="divide-y divide-brand-border/60">
            @foreach($order->items as $item)
                @php
                    $product = $item->variant?->product;
                    $primaryImg = $product?->primaryImage ?? $product?->images->first();
                    $imgUrl = $primaryImg?->url;
                @endphp
                <li class="p-4 sm:p-5 flex gap-4 sm:gap-5 items-start">
                    <div class="flex-shrink-0 w-16 h-20 sm:w-20 sm:h-24 border border-brand-border bg-brand-off-white overflow-hidden rounded-xl shadow-2xs">
                        @if($imgUrl)
                            <img src="{{ $imgUrl }}" alt="{{ $product->name ?? 'Drop' }}" class="w-full h-full object-center object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-100 text-xs font-bold text-gray-400">TX</div>
                        @endif
                    </div>
                    <div class="flex-1 flex flex-col justify-center min-w-0">
                        <div class="flex justify-between items-start text-xs sm:text-sm font-bold text-brand-dark gap-2">
                            <h3 class="line-clamp-2">
                                @if($product)
                                    <a href="{{ route('frontend.products.show', $product->slug) }}" class="hover:underline">{{ $product->name }}</a>
                                @else
                                    <span>Streetwear Item</span>
                                @endif
                            </h3>
                            <p class="ml-4 shrink-0 font-heading font-extrabold text-brand-dark">₹{{ number_format($item->total ?? ($item->price * $item->quantity), 2) }}</p>
                        </div>
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
                        </div>
                        <p class="mt-1.5 text-xs text-brand-muted">Qty: {{ $item->quantity }} × ₹{{ number_format($item->price, 2) }}</p>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Delivery Address & Payment Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-6">
        {{-- Shipping Address --}}
        <div class="p-4 sm:p-5 bg-brand-off-white/50 rounded-2xl border border-brand-border">
            <h3 class="text-xs font-extrabold uppercase tracking-wider text-brand-dark mb-3 pb-2 border-b border-brand-border flex items-center gap-1.5">
                <span>📍</span>
                <span>Delivery Address</span>
            </h3>
            @if($order->address)
                <address class="not-italic text-xs sm:text-sm text-brand-muted space-y-1.5 leading-relaxed">
                    <span class="block font-extrabold text-brand-dark">{{ $order->address->name }}</span>
                    <span class="block">{{ $order->address->line1 }}</span>
                    @if($order->address->line2) <span class="block">{{ $order->address->line2 }}</span> @endif
                    <span class="block">{{ $order->address->city }}, {{ $order->address->state }} — {{ $order->address->pincode }}</span>
                    <span class="block pt-1 text-brand-dark font-semibold">📞 Phone: {{ $order->address->phone }}</span>
                </address>
            @else
                <p class="text-xs text-brand-muted">Address details unavailable.</p>
            @endif
        </div>

        {{-- Payment Info --}}
        <div class="p-4 sm:p-5 bg-brand-off-white/50 rounded-2xl border border-brand-border">
            <h3 class="text-xs font-extrabold uppercase tracking-wider text-brand-dark mb-3 pb-2 border-b border-brand-border flex items-center gap-1.5">
                <span>💳</span>
                <span>Payment Information</span>
            </h3>
            <div class="text-xs sm:text-sm text-brand-muted space-y-2.5 leading-relaxed">
                <p class="flex justify-between items-center">
                    <span class="font-semibold text-brand-dark">Payment Method:</span> 
                    <span class="font-bold text-brand-dark">{{ $order->payment_method === 'razorpay' ? 'Razorpay (Online)' : 'Cash on Delivery (COD)' }}</span>
                </p>
                <p class="flex justify-between items-center">
                    <span class="font-semibold text-brand-dark">Payment Status:</span> 
                    <span class="font-bold capitalize px-2 py-0.5 rounded text-[11px]
                        @if($order->payment_status === 'paid') bg-emerald-50 text-emerald-700 border border-emerald-200
                        @elseif($order->payment_status === 'failed') bg-rose-50 text-rose-700 border border-rose-200
                        @else bg-amber-50 text-amber-700 border border-amber-200 @endif
                    ">
                        {{ $order->payment_status }}
                    </span>
                </p>
                @if($order->payment && $order->payment->gateway_payment_id)
                    <p class="flex justify-between items-center text-[11px]">
                        <span class="font-semibold text-brand-dark">Transaction ID:</span> 
                        <span class="font-mono text-brand-muted">{{ $order->payment->gateway_payment_id }}</span>
                    </p>
                @endif
            </div>
        </div>
    </div>

    {{-- Order Summary --}}
    <div class="border-t border-brand-border/70 pt-5 flex justify-end">
        <dl class="w-full sm:w-1/2 lg:w-1/3 space-y-2.5 text-xs sm:text-sm text-brand-muted">
            <div class="flex justify-between">
                <dt>Subtotal</dt>
                <dd class="text-brand-dark font-semibold">₹{{ number_format($order->subtotal, 2) }}</dd>
            </div>
            @if($order->discount > 0)
            <div class="flex justify-between text-emerald-700">
                <dt>Discount</dt>
                <dd class="font-bold">-₹{{ number_format($order->discount, 2) }}</dd>
            </div>
            @endif
            <div class="flex justify-between">
                <dt>Shipping Charges</dt>
                <dd class="text-brand-dark font-semibold">{{ $order->shipping == 0 ? 'FREE' : '₹'.number_format($order->shipping, 2) }}</dd>
            </div>
            <div class="flex justify-between items-center border-t border-brand-border pt-3">
                <dt class="text-xs sm:text-sm font-extrabold text-brand-dark uppercase tracking-wider">Total Amount</dt>
                <dd class="text-base sm:text-lg font-heading font-extrabold text-brand-dark">₹{{ number_format($order->total, 2) }}</dd>
            </div>
        </dl>
    </div>

</div>
@endsection
