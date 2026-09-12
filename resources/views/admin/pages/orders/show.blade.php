@extends('admin.layouts.app')

@section('title', 'Order ' . ($order->order_number ?? '#' . $order->id))
@section('page-title', 'Order Management & Fulfillment')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto" x-data="{ showTrackingModal: false, showRefundModal: false, refundAmount: '{{ $order->total }}' }">

    {{-- Top Navigation & Action Header Bar --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900 inline-flex items-center gap-1 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                    <span>Orders Ledger</span>
                </a>
                <span class="text-slate-300">•</span>
                <span class="text-xs font-mono font-bold text-blue-600">ID #{{ $order->id }}</span>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-xl sm:text-2xl font-heading font-black text-slate-900 tracking-tight flex items-center gap-2">
                    <span>Order {{ $order->order_number }}</span>
                    <span class="text-base">📦</span>
                </h1>

                {{-- Fulfillment Status Badge --}}
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider border
                    @if($order->status === 'delivered') bg-emerald-50 text-emerald-700 border-emerald-200
                    @elseif($order->status === 'cancelled') bg-rose-50 text-rose-700 border-rose-200
                    @elseif(in_array($order->status, ['shipped', 'in_transit', 'out_for_delivery'])) bg-indigo-50 text-indigo-700 border-indigo-200
                    @elseif($order->status === 'processing') bg-blue-50 text-blue-700 border-blue-200
                    @else bg-amber-50 text-amber-700 border-amber-200 @endif
                ">
                    <span class="w-1.5 h-1.5 rounded-full
                        @if($order->status === 'delivered') bg-emerald-500
                        @elseif($order->status === 'cancelled') bg-rose-500
                        @elseif(in_array($order->status, ['shipped', 'in_transit', 'out_for_delivery'])) bg-indigo-500
                        @elseif($order->status === 'processing') bg-blue-500
                        @else bg-amber-500 animate-pulse @endif
                    "></span>
                    <span>{{ $order->status }}</span>
                </span>

                {{-- Payment Status Badge --}}
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider
                    @if($order->payment_status === 'paid') bg-emerald-100/70 text-emerald-800
                    @elseif($order->payment_status === 'failed') bg-rose-100 text-rose-800
                    @else bg-amber-100 text-amber-800 @endif
                ">
                    <span>{{ $order->payment_method === 'cod' ? 'COD' : 'ONLINE' }}</span>
                    <span>•</span>
                    <span>{{ ucfirst($order->payment_status) }}</span>
                </span>
            </div>
            <p class="text-xs text-slate-400 mt-1">
                Placed on {{ $order->created_at->format('M d, Y • h:i A') }} ({{ $order->created_at->diffForHumans() }})
            </p>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-wrap items-center gap-2">
            {{-- Print Shipping Label --}}
            <a href="{{ route('admin.orders.shipping.label', $order->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold shadow-xs active:scale-95 transition-all cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-3.11 2.215-5.679 5.28-5.679 3.065 0 5.52 2.569 5.28 5.679m-10.56 0A5.98 5.98 0 0012 18a5.98 5.98 0 005.28-4.171m-10.56 0a5.99 5.99 0 0110.56 0M9 10.5h6M9 7.5h6"/></svg>
                <span>🏷️ Shipping Label</span>
            </a>

            {{-- Tax Invoice PDF --}}
            <a href="{{ route('admin.orders.invoice.download', $order->id) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold border border-slate-200 shadow-2xs active:scale-95 transition-all cursor-pointer">
                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                <span>📄 Tax Invoice</span>
            </a>

            {{-- Shiprocket 1-Click Push / Sync --}}
            @if(!$order->shiprocket_order_id)
                @if($shiprocketConfigured)
                    <form action="{{ route('admin.orders.shiprocket.push', $order->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold shadow-xs active:scale-95 transition-all cursor-pointer" title="Push order and manifest shipment in Shiprocket">
                            <span>🚀 Ship via Shiprocket</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-bold border border-amber-300 shadow-xs active:scale-95 transition-all cursor-pointer" title="Configure Shiprocket API credentials in Settings > Logistics">
                        <span>⚙️ Setup Shiprocket</span>
                    </a>
                @endif
            @else
                <form action="{{ route('admin.orders.shiprocket.sync', $order->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-purple-50 hover:bg-purple-100 text-purple-700 text-xs font-bold border border-purple-200 active:scale-95 transition-all cursor-pointer" title="Fetch live courier tracking from Shiprocket">
                        <svg class="w-3.5 h-3.5 animate-spin-hover" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                        <span>🔄 Sync Shiprocket</span>
                    </button>
                </form>
            @endif

            {{-- WhatsApp Contact --}}
            @php
                $phone = $order->address->phone ?? ($order->user->phone ?? null);
                $cleanPhone = $phone ? preg_replace('/[^0-9]/', '', $phone) : null;
                if ($cleanPhone && strlen($cleanPhone) === 10) {
                    $cleanPhone = '91' . $cleanPhone;
                }
            @endphp
            @if($cleanPhone)
                <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode('Hello ' . ($order->address->name ?? 'there') . ', regarding your ThreadAX Order #' . $order->order_number . ': ') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 text-xs font-bold transition-all cursor-pointer">
                    <span>💬 WhatsApp</span>
                </a>
            @endif
        </div>
    </div>

    {{-- Shiprocket Status Banner if Manifested --}}
    @if($order->shiprocket_order_id)
        <div class="p-4 bg-gradient-to-r from-purple-900 to-indigo-900 text-white rounded-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-md">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-lg">
                    🚀
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-purple-200 uppercase tracking-wider">Shiprocket Fulfillment Active</span>
                        <span class="text-[10px] bg-purple-500/40 text-purple-200 px-2 py-0.5 rounded-md font-mono">ID: {{ $order->shiprocket_order_id }}</span>
                    </div>
                    <p class="text-xs text-white/90 mt-0.5">
                        Courier: <strong class="text-white">{{ $order->effective_courier ?? 'Shiprocket Logistics' }}</strong>
                        @if($order->effective_awb) • AWB: <span class="font-mono text-amber-300 font-bold">{{ $order->effective_awb }}</span> @endif
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if(!$order->shiprocket_awb_code)
                    <form action="{{ route('admin.orders.shiprocket.awb', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-white text-purple-900 text-xs font-bold hover:bg-purple-50 transition-all cursor-pointer">
                            Generate AWB Code
                        </button>
                    </form>
                @endif
                @if($order->effective_tracking_url)
                    <a href="{{ $order->effective_tracking_url }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition-all">
                        Track Online ↗
                    </a>
                @endif
            </div>
        </div>
    @endif

    {{-- Active / Past Return Request Banner if exists --}}
    @if($order->returns->isNotEmpty())
        @php $latestRet = $order->returns->first(); $retBadge = $latestRet->status_badge; @endphp
        <div class="p-4 sm:p-5 bg-gradient-to-r from-amber-500/10 via-indigo-500/10 to-purple-500/10 border-2 border-amber-300 rounded-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-2xs">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center text-lg font-bold shrink-0">
                    🔄
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h4 class="text-sm font-extrabold text-slate-900">
                            {{ $latestRet->type === 'exchange' ? 'Size Exchange' : 'Return Request' }} #{{ $latestRet->return_number }}
                        </h4>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold capitalize border {{ $retBadge['bg'] }} {{ $retBadge['text'] }} {{ $retBadge['border'] }}">
                            {{ $retBadge['label'] }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-600 mt-0.5">
                        Reason: <strong>{{ $latestRet->reason_label }}</strong> 
                        • Value: <strong class="text-emerald-700">₹{{ number_format($latestRet->refund_amount, 2) }}</strong>
                        @if($latestRet->pickup_courier) • Pickup by: {{ $latestRet->pickup_courier }} (AWB: {{ $latestRet->pickup_awb ?? 'Pending' }}) @endif
                    </p>
                </div>
            </div>

            <a href="{{ route('admin.returns.show', $latestRet->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all shadow-xs shrink-0 cursor-pointer">
                <span>Manage Return & Refund</span>
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>
    @endif

    {{-- Main Grid: 2 Columns on XL --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- LEFT COLUMN (2/3 width): Items Ledger & Tracking Timeline --}}
        <div class="xl:col-span-2 space-y-6">

            {{-- 1. Order Items Table --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-heading font-extrabold text-slate-900">Ordered Drops</h2>
                        <p class="text-xs text-slate-400">{{ $order->items->sum('quantity') }} items in this package</p>
                    </div>
                    <span class="text-xs font-mono font-bold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg">
                        {{ $order->items->count() }} unique {{ Str::plural('drop', $order->items->count()) }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="py-3 px-5">Product Details</th>
                                <th class="py-3 px-4 text-center">Unit Price</th>
                                <th class="py-3 px-4 text-center">Qty</th>
                                <th class="py-3 px-5 text-right">Line Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @foreach($order->items as $item)
                                @php
                                    $product = $item->variant?->product;
                                    $primaryImg = $product?->primaryImage ?? $product?->images->first();
                                    $imgUrl = $primaryImg?->url;
                                    $itemPrice = (float) $item->price;
                                    $itemTotal = (float) ($item->total ?? ($itemPrice * $item->quantity));
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="py-4 px-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-14 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden shrink-0">
                                                @if($imgUrl)
                                                    <img src="{{ $imgUrl }}" alt="{{ $product->name ?? 'Drop' }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center font-bold text-slate-400 text-xs">TX</div>
                                                @endif
                                            </div>
                                            <div>
                                                @if($product)
                                                    <a href="{{ route('frontend.products.show', $product->slug) }}" target="_blank" class="font-bold text-slate-900 hover:text-blue-600 hover:underline">
                                                        {{ $product->name }}
                                                    </a>
                                                @else
                                                    <span class="font-bold text-slate-900">Drop Item</span>
                                                @endif
                                                <div class="flex flex-wrap items-center gap-1.5 mt-1">
                                                    @if($item->variant?->size)
                                                        <span class="px-2 py-0.5 rounded-md bg-slate-100 border border-slate-200 text-[10px] font-bold text-slate-700">
                                                            Size: {{ $item->variant->size }}
                                                        </span>
                                                    @endif
                                                    @if($item->variant?->color)
                                                        <span class="px-2 py-0.5 rounded-md bg-slate-100 border border-slate-200 text-[10px] font-bold text-slate-700">
                                                            Color: {{ $item->variant->color }}
                                                        </span>
                                                    @endif
                                                    @if($item->variant?->sku)
                                                        <span class="text-[10px] font-mono text-slate-400">
                                                            SKU: {{ $item->variant->sku }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 text-center font-bold text-slate-900">
                                        ₹{{ number_format($itemPrice, 2) }}
                                    </td>
                                    <td class="py-4 px-4 text-center">
                                        <span class="px-2.5 py-1 rounded-lg bg-slate-100 font-bold text-slate-900">
                                            {{ $item->quantity }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-5 text-right font-heading font-black text-slate-900 text-sm">
                                        ₹{{ number_format($itemTotal, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Totals Summary --}}
                <div class="p-5 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="text-xs text-slate-500 space-y-1">
                        <p>Payment Channel: <strong class="text-slate-900 uppercase">{{ $order->payment_method }}</strong></p>
                        @if($order->coupon_code)
                            <p>Applied Coupon: <strong class="text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 font-mono">{{ $order->coupon_code }}</strong></p>
                        @endif
                    </div>
                    <div class="w-full sm:w-64 space-y-2 text-xs">
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal</span>
                            <span class="font-bold text-slate-900">₹{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        @if($order->discount > 0)
                            <div class="flex justify-between text-emerald-700">
                                <span>Discount</span>
                                <span class="font-bold">-₹{{ number_format($order->discount, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-slate-600">
                            <span>Shipping Charge</span>
                            <span class="font-bold text-slate-900">{{ $order->shipping == 0 ? 'FREE' : '₹'.number_format($order->shipping, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-slate-900 font-heading font-black text-base pt-2 border-t border-slate-200">
                            <span>Grand Total</span>
                            <span class="text-slate-900">₹{{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Order Tracking Milestones & Timeline --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 pb-4 border-b border-slate-100">
                    <div>
                        <h2 class="text-sm font-heading font-extrabold text-slate-900 flex items-center gap-2">
                            <span>📍 Real-Time Tracking Radar</span>
                            <span class="text-xs bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full font-bold">
                                {{ $order->trackings->count() }} Milestones
                            </span>
                        </h2>
                        <p class="text-xs text-slate-400">Live logistics checkpoints visible to admin and customer</p>
                    </div>

                    <button @click="showTrackingModal = true" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all shadow-xs active:scale-95 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        <span>➕ Add Milestone</span>
                    </button>
                </div>

                {{-- Timeline Feed --}}
                @if($order->trackings->isNotEmpty())
                    <div class="relative pl-6 space-y-6 before:absolute before:left-2.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                        @foreach($order->trackings as $track)
                            @php $badge = $track->status_badge; @endphp
                            <div class="relative flex items-start gap-4">
                                <div class="absolute -left-6 mt-1 w-5 h-5 rounded-full bg-white border-2 border-slate-900 flex items-center justify-center text-[10px] shadow-xs">
                                    {{ $badge['icon'] }}
                                </div>
                                <div class="bg-slate-50 border border-slate-200/80 p-4 rounded-xl flex-1 hover:border-slate-300 transition-colors">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 mb-1">
                                        <div class="flex items-center gap-2">
                                            <h4 class="text-xs font-extrabold text-slate-900">{{ $track->title }}</h4>
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ $badge['bg'] }} {{ $badge['text'] }}">
                                                {{ $badge['label'] }}
                                            </span>
                                        </div>
                                        <span class="text-[11px] font-mono text-slate-400">
                                            {{ $track->event_time ? $track->event_time->format('M d, Y • h:i A') : $track->created_at->format('M d, Y • h:i A') }}
                                        </span>
                                    </div>
                                    @if($track->location)
                                        <p class="text-[11px] font-bold text-slate-700 mt-0.5">
                                            📍 {{ $track->location }}
                                            @if($track->courier_name)
                                                <span class="text-slate-400 font-normal">• via {{ $track->courier_name }}</span>
                                            @endif
                                        </p>
                                    @endif
                                    @if($track->activity)
                                        <p class="text-xs text-slate-600 mt-1.5 leading-relaxed bg-white p-2.5 rounded-lg border border-slate-200/60">
                                            {{ $track->activity }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                        <span class="text-2xl">📦</span>
                        <p class="text-xs font-bold text-slate-700 mt-2">No tracking checkpoints added yet</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Click "Add Milestone" above to record dispatch checkpoints.</p>
                    </div>
                @endif
            </div>

        </div>

        {{-- RIGHT COLUMN (1/3 width): Fulfillment Actions, Customer & Shipping Address --}}
        <div class="space-y-6">

            {{-- 3. Fulfillment & Courier Management Form --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs">
                <div class="flex items-center gap-3 mb-5 pb-3 border-b border-slate-100">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-base">
                        ⚡
                    </div>
                    <div>
                        <h3 class="text-sm font-heading font-extrabold text-slate-900">Update Fulfillment</h3>
                        <p class="text-xs text-slate-400">Manage status & courier tracking details</p>
                    </div>
                </div>

                <form action="{{ route('admin.orders.status.update', $order->id) }}" method="POST" class="space-y-4">
                    @csrf

                    {{-- Order Status --}}
                    <div>
                        <label for="status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Order Status <span class="text-rose-500">*</span>
                        </label>
                        <select name="status" id="status" class="w-full py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 outline-none focus:border-slate-400">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>🟡 Pending Confirmation</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>🔵 Processing & Packing</option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>🚚 Shipped / Dispatched</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>🟢 Delivered (Auto-sends Invoice Email)</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>🔴 Cancelled</option>
                        </select>
                        <p class="text-[10px] text-slate-400 mt-1">Marking <strong>Delivered</strong> automatically emails the customer with their PDF invoice!</p>
                    </div>

                    {{-- Payment Status --}}
                    <div>
                        <label for="payment_status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Payment Status <span class="text-rose-500">*</span>
                        </label>
                        <select name="payment_status" id="payment_status" class="w-full py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 outline-none focus:border-slate-400">
                            <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>🟡 Pending</option>
                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>🟢 Paid</option>
                            <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>🔴 Failed</option>
                            <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>↩️ Refunded</option>
                        </select>
                    </div>

                    {{-- Courier Name --}}
                    <div>
                        <label for="courier_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Courier Partner Name
                        </label>
                        <input type="text" 
                               name="courier_name" 
                               id="courier_name" 
                               value="{{ $order->effective_courier }}" 
                               placeholder="e.g. Delhivery, Blue Dart, DTDC, Shiprocket" 
                               class="w-full py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 outline-none focus:border-slate-400">
                    </div>

                    {{-- Tracking / AWB Number --}}
                    <div>
                        <label for="tracking_number" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            AWB / Tracking Number
                        </label>
                        <input type="text" 
                               name="tracking_number" 
                               id="tracking_number" 
                               value="{{ $order->effective_awb }}" 
                               placeholder="e.g. 1234567890" 
                               class="w-full py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-900 outline-none focus:border-slate-400">
                    </div>

                    {{-- Custom Tracking URL --}}
                    <div>
                        <label for="tracking_url" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Custom Tracking URL (Optional)
                        </label>
                        <input type="url" 
                               name="tracking_url" 
                               id="tracking_url" 
                               value="{{ $order->tracking_url }}" 
                               placeholder="https://track.courier.com/..." 
                               class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 outline-none focus:border-slate-400">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-md hover:shadow-lg active:scale-95 transition-all cursor-pointer">
                            Save Changes & Sync Order
                        </button>
                    </div>
                </form>
            </div>

            {{-- 3B. Razorpay Payment & Instant Refund Gateway Card --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-base">
                            💳
                        </div>
                        <div>
                            <h3 class="text-sm font-heading font-extrabold text-slate-900">Payment & Gateway</h3>
                            <p class="text-xs text-slate-400">Transaction & refund controls</p>
                        </div>
                    </div>
                    @if($order->payment_method === 'razorpay')
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-50 text-blue-700 border border-blue-200 uppercase">
                            Razorpay
                        </span>
                    @else
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-700 border border-slate-200 uppercase">
                            COD
                        </span>
                    @endif
                </div>

                <div class="space-y-3 text-xs">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">Payment Status:</span>
                        @if($order->payment_status === 'paid')
                            <span class="font-extrabold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">🟢 Paid Online</span>
                        @elseif($order->payment_status === 'refunded')
                            <span class="font-extrabold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-md border border-purple-200">↩️ Refunded</span>
                        @elseif($order->payment_status === 'failed')
                            <span class="font-extrabold text-rose-700 bg-rose-50 px-2 py-0.5 rounded-md border border-rose-200">🔴 Payment Failed</span>
                        @else
                            <span class="font-extrabold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200">🟡 Pending</span>
                        @endif
                    </div>

                    @if($order->payment)
                        @if($order->payment->transaction_id)
                            <div class="flex justify-between items-center">
                                <span class="text-slate-500">Razorpay Order:</span>
                                <span class="font-mono font-bold text-slate-900 truncate max-w-[150px]" title="{{ $order->payment->transaction_id }}">
                                    {{ $order->payment->transaction_id }}
                                </span>
                            </div>
                        @endif

                        @if($order->payment->razorpay_payment_id)
                            <div class="flex justify-between items-center">
                                <span class="text-slate-500">Payment ID:</span>
                                <span class="font-mono font-bold text-blue-600 truncate max-w-[150px]" title="{{ $order->payment->razorpay_payment_id }}">
                                    {{ $order->payment->razorpay_payment_id }}
                                </span>
                            </div>
                        @endif

                        @if($order->payment->refund_id)
                            <div class="p-3 bg-purple-50 rounded-xl border border-purple-200 space-y-1">
                                <div class="flex items-center justify-between text-purple-900 font-extrabold">
                                    <span>Refund Processed</span>
                                    <span class="text-[10px] bg-purple-200/80 px-1.5 py-0.5 rounded font-mono">{{ $order->payment->refund_status }}</span>
                                </div>
                                <p class="text-[11px] font-mono text-purple-700">ID: {{ $order->payment->refund_id }}</p>
                            </div>
                        @endif
                    @endif

                    {{-- 1-Click Instant Refund Trigger Button --}}
                    @if($order->payment && $order->payment->isRefundable())
                        <div class="pt-2">
                            <button type="button" 
                                    @click="showRefundModal = true" 
                                    class="w-full py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-xs transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                                <span>⚡ Issue Razorpay Refund</span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            {{-- 4. Customer Details --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs">
                <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-100">
                    <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-base">
                        👤
                    </div>
                    <div>
                        <h3 class="text-sm font-heading font-extrabold text-slate-900">Customer Profile</h3>
                        <p class="text-xs text-slate-400">Account and contact information</p>
                    </div>
                </div>

                <div class="space-y-3 text-xs">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-900 text-white font-black text-sm flex items-center justify-center shrink-0">
                            {{ strtoupper(substr($order->user->name ?? ($order->address->name ?? 'U'), 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-extrabold text-slate-900 truncate">{{ $order->user->name ?? ($order->address->name ?? 'Guest User') }}</p>
                            <p class="text-slate-400 truncate">{{ $order->user->email ?? 'No account email' }}</p>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-slate-100 space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500">Account Phone:</span>
                            <span class="font-bold text-slate-900 font-mono">{{ $order->user->phone ?? ($order->address->phone ?? 'N/A') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500">Lifetime Orders:</span>
                            <span class="font-bold text-blue-600">{{ $order->user ? $order->user->orders()->count() : 1 }} orders</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5. Shipping & Delivery Address --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-base">
                            📍
                        </div>
                        <div>
                            <h3 class="text-sm font-heading font-extrabold text-slate-900">Delivery Address</h3>
                            <p class="text-xs text-slate-400">Destination for shipment</p>
                        </div>
                    </div>
                    @if($order->address)
                        <button onclick="navigator.clipboard.writeText('{{ $order->address->name }}, {{ $order->address->line1 }}, {{ $order->address->line2 }}, {{ $order->address->city }}, {{ $order->address->state }} - {{ $order->address->pincode }}, Ph: {{ $order->address->phone }}'); alert('Address copied to clipboard!');" class="text-[11px] font-bold text-slate-600 hover:text-slate-900 bg-slate-100 px-2 py-1 rounded-md cursor-pointer">
                            📋 Copy
                        </button>
                    @endif
                </div>

                @if($order->address)
                    <address class="not-italic text-xs text-slate-600 space-y-1.5 leading-relaxed">
                        <p class="font-extrabold text-slate-900 text-sm">{{ $order->address->name }}</p>
                        <p>{{ $order->address->line1 }}</p>
                        @if($order->address->line2) <p>{{ $order->address->line2 }}</p> @endif
                        @if($order->address->landmark) <p class="text-slate-400">Landmark: {{ $order->address->landmark }}</p> @endif
                        <p class="font-bold text-slate-900 pt-1">
                            {{ $order->address->city }}, {{ $order->address->state }} — <span class="font-mono text-blue-600">{{ $order->address->pincode }}</span>
                        </p>
                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                            <span class="font-bold text-slate-900">📞 {{ $order->address->phone }}</span>
                            <a href="https://maps.google.com/?q={{ urlencode($order->address->line1 . ' ' . $order->address->city . ' ' . $order->address->pincode) }}" target="_blank" class="text-blue-600 hover:underline font-bold text-[11px]">
                                Open Maps ↗
                            </a>
                        </div>
                    </address>
                @else
                    <p class="text-xs text-slate-400 italic">No delivery address attached.</p>
                @endif
            </div>

        </div>

    </div>

    {{-- Add Tracking Milestone Modal --}}
    <div x-show="showTrackingModal" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
         @keydown.escape.window="showTrackingModal = false">
        <div class="bg-white rounded-3xl border border-slate-200 max-w-lg w-full p-6 shadow-2xl space-y-5" @click.outside="showTrackingModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <span class="text-xl">📍</span>
                    <div>
                        <h3 class="text-base font-heading font-black text-slate-900">Add Tracking Checkpoint</h3>
                        <p class="text-xs text-slate-400">Record a new milestone in package transit</p>
                    </div>
                </div>
                <button @click="showTrackingModal = false" class="text-slate-400 hover:text-slate-600 font-bold text-lg cursor-pointer">
                    ✕
                </button>
            </div>

            <form action="{{ route('admin.orders.tracking.store', $order->id) }}" method="POST" class="space-y-4">
                @csrf

                {{-- Status Stage --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Fulfillment Stage <span class="text-rose-500">*</span>
                    </label>
                    <select name="status" required class="w-full py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 outline-none">
                        <option value="processing">🔵 Processing & Packing</option>
                        <option value="packed">🎁 Packed at Warehouse</option>
                        <option value="shipped">🚚 Shipped & Picked Up by Courier</option>
                        <option value="in_transit" selected>✈️ In Transit / Sorting Hub</option>
                        <option value="out_for_delivery">🛵 Out for Delivery</option>
                        <option value="delivered">🟢 Delivered to Customer</option>
                        <option value="rto">↩️ Returned to Origin (RTO)</option>
                    </select>
                </div>

                {{-- Milestone Title --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Milestone Title / Action <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="title" 
                           required 
                           placeholder="e.g. Departed Mumbai Hub towards Delhi" 
                           class="w-full py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 outline-none">
                </div>

                {{-- Location --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Current Hub / City Location
                    </label>
                    <input type="text" 
                           name="location" 
                           placeholder="e.g. Mumbai Sorting Facility, MH" 
                           class="w-full py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 outline-none">
                </div>

                {{-- Activity Details / Delivery Agent Info --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Activity Note (Optional)
                    </label>
                    <textarea name="activity" 
                              rows="2" 
                              placeholder="e.g. Bagged for inter-city flight route. Delivery executive Amit assigned." 
                              class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 outline-none"></textarea>
                </div>

                {{-- Courier & AWB --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Courier</label>
                        <input type="text" name="courier_name" value="{{ $order->effective_courier }}" placeholder="e.g. Delhivery" class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">AWB Code</label>
                        <input type="text" name="tracking_number" value="{{ $order->effective_awb }}" placeholder="e.g. AWB12345" class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-900 outline-none">
                    </div>
                </div>

                {{-- Sync Main Order Status Checkbox --}}
                <div class="p-3 bg-blue-50/70 border border-blue-100 rounded-xl flex items-center gap-2.5">
                    <input type="checkbox" name="sync_order_status" id="sync_order_status" value="1" checked class="w-4 h-4 text-blue-600 rounded">
                    <label for="sync_order_status" class="text-xs font-bold text-blue-900 cursor-pointer">
                        Sync main order status & notify customer tracker
                    </label>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2">
                    <button type="button" @click="showTrackingModal = false" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold shadow-md active:scale-95 transition-all cursor-pointer">
                        Save Checkpoint
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Issue Razorpay Refund Modal --}}
    <div x-show="showRefundModal" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
         @keydown.escape.window="showRefundModal = false">
        <div class="bg-white rounded-3xl border border-slate-200 max-w-lg w-full p-6 shadow-2xl space-y-5" @click.outside="showRefundModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <span class="text-xl">⚡</span>
                    <div>
                        <h3 class="text-base font-heading font-black text-slate-900">Issue Razorpay Refund</h3>
                        <p class="text-xs text-slate-400">Direct instant refund to customer original payment source</p>
                    </div>
                </div>
                <button @click="showRefundModal = false" class="text-slate-400 hover:text-slate-600 font-bold text-lg cursor-pointer">
                    ✕
                </button>
            </div>

            <form action="{{ route('admin.orders.refund', $order->id) }}" method="POST" class="space-y-4">
                @csrf

                <div class="p-3.5 bg-purple-50 border border-purple-200 rounded-2xl text-xs text-purple-900 space-y-1">
                    <div class="flex justify-between font-extrabold">
                        <span>Paid Order Amount:</span>
                        <span>₹{{ number_format($order->total, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-[11px] text-purple-700 font-mono">
                        <span>Payment ID:</span>
                        <span>{{ $order->payment?->razorpay_payment_id }}</span>
                    </div>
                </div>

                {{-- Refund Amount --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Refund Amount (₹) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" 
                           step="0.01" 
                           name="amount" 
                           x-model="refundAmount" 
                           min="1" 
                           max="{{ $order->total }}" 
                           required 
                           class="w-full py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-extrabold font-mono text-slate-900 outline-none focus:border-purple-500">
                    <p class="text-[11px] text-slate-400 mt-1">Leave as <strong>₹{{ number_format($order->total, 2) }}</strong> for full refund, or enter lesser for partial refund.</p>
                </div>

                {{-- Reason --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Refund Reason (Optional)
                    </label>
                    <input type="text" 
                           name="reason" 
                           placeholder="e.g. Customer cancelled / Out of stock" 
                           class="w-full py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 outline-none focus:border-purple-500">
                </div>

                <div class="pt-2 flex items-center justify-end gap-2">
                    <button type="button" @click="showRefundModal = false" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" onclick="return confirm('Are you sure you want to trigger this Razorpay refund? Money will be refunded to customer immediately.');" class="px-5 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-xs font-extrabold shadow-md active:scale-95 transition-all cursor-pointer">
                        ⚡ Confirm & Issue Refund
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
