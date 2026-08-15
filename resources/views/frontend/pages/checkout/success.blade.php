@extends('frontend.layouts.app')

@section('title', 'Order Confirmed - ' . $order->order_number)

@section('content')
<div class="bg-[#f1f2f6] min-h-screen py-8 sm:py-12 font-sans selection:bg-brand-dark selection:text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        
        {{-- TOP CELEBRATION HEADER CARD --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 sm:p-10 text-center mb-6">
            {{-- Clean Check Circle --}}
            <div class="w-16 h-16 bg-black text-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>

            <span class="inline-block text-[10px] font-black uppercase tracking-widest bg-gray-100 text-gray-700 px-3 py-1 rounded-full mb-3">
                ✓ Order Successfully Placed
            </span>

            <h1 class="text-2xl sm:text-3xl font-heading font-black uppercase tracking-tight text-black mb-2">
                Order Confirmed
            </h1>
            
            <p class="text-xs sm:text-sm text-gray-500 max-w-md mx-auto mb-6 leading-relaxed">
                Thank you for shopping with <strong class="text-black font-extrabold">ThreadAx</strong>. We’ve received your order and sent a confirmation email.
            </p>

            {{-- Order Number Badge with Copy --}}
            <div class="inline-flex items-center gap-4 bg-gray-50 border border-gray-200/80 rounded-lg px-5 py-3 text-left">
                <div>
                    <span class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Order Reference</span>
                    <span class="text-sm sm:text-base font-black text-black font-mono tracking-wider">{{ $order->order_number }}</span>
                </div>
                <button type="button" @click="navigator.clipboard.writeText('{{ $order->order_number }}'); if (window.showToast) window.showToast('Order ID Copied to Clipboard!', 'success');" class="text-[11px] font-bold text-gray-600 hover:text-black uppercase tracking-wider bg-white border border-gray-300 hover:border-black px-3 py-1.5 rounded transition-colors cursor-pointer">
                    Copy 📋
                </button>
            </div>
        </div>

        {{-- DELIVERY ESTIMATE & TRACKER TIMELINE --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-6">
                <div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Expected Delivery</span>
                    <h3 class="text-sm sm:text-base font-bold text-black flex items-center gap-2">
                        <span>🚚 3 - 5 Business Days</span>
                        <span class="text-xs font-semibold text-green-600 bg-green-50 px-2.5 py-0.5 rounded-full border border-green-200">On Schedule</span>
                    </h3>
                </div>
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider hidden sm:block">Standard Express</span>
            </div>

            {{-- Progress Tracker Steps --}}
            <div class="grid grid-cols-4 gap-2 relative">
                <div class="text-center space-y-2">
                    <div class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center text-xs font-bold mx-auto shadow-sm">✓</div>
                    <span class="block text-[11px] font-bold text-black">Placed</span>
                </div>
                <div class="text-center space-y-2">
                    <div class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center text-xs font-bold mx-auto shadow-sm">✓</div>
                    <span class="block text-[11px] font-bold text-black">Processing</span>
                </div>
                <div class="text-center space-y-2 opacity-50">
                    <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center text-xs font-bold mx-auto">3</div>
                    <span class="block text-[11px] font-semibold text-gray-600">Shipped</span>
                </div>
                <div class="text-center space-y-2 opacity-50">
                    <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center text-xs font-bold mx-auto">4</div>
                    <span class="block text-[11px] font-semibold text-gray-600">Delivered</span>
                </div>
            </div>
        </div>

        {{-- 2-COLUMN DETAILS: Items & Shipping/Payment --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            
            {{-- Order Items Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
                <div>
                    <h3 class="text-xs font-heading font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-3 mb-4">
                        Items Ordered ({{ $order->items->sum('quantity') }})
                    </h3>

                    <div class="space-y-4 max-h-[280px] overflow-y-auto pr-1">
                        @foreach($order->items as $item)
                            <div class="flex items-center gap-3.5 pb-3 border-b border-gray-100 last:border-0 last:pb-0">
                                <div class="w-14 h-16 bg-gray-50 rounded border border-gray-200 overflow-hidden shrink-0">
                                    @if($item->variant->product->primaryImage)
                                        <img src="{{ $item->variant->product->primaryImage->url }}" alt="{{ $item->variant->product->name }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0 text-xs">
                                    <h4 class="font-bold text-black truncate">{{ $item->variant->product->name }}</h4>
                                    <p class="text-gray-500 mt-0.5">
                                        @if($item->variant->size) Size: <span class="font-semibold text-black">{{ $item->variant->size }}</span> @endif
                                        @if($item->variant->color) | Color: <span class="font-semibold text-black">{{ $item->variant->color }}</span> @endif
                                    </p>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-gray-500 font-medium">Qty: {{ $item->quantity }}</span>
                                        <span class="font-black text-black">₹{{ number_format($item->price * $item->quantity) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Price Breakdown Summary --}}
                <div class="mt-6 pt-4 border-t border-gray-200 text-xs space-y-2">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span class="font-bold text-black">₹{{ number_format($order->subtotal ?? $order->total) }}</span>
                    </div>
                    @if($order->discount_amount > 0)
                    <div class="flex justify-between text-green-600 font-bold">
                        <span>Discount</span>
                        <span>- ₹{{ number_format($order->discount_amount) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between text-gray-600">
                        <span>Shipping</span>
                        <span class="text-green-600 font-bold uppercase">FREE</span>
                    </div>
                    <div class="flex justify-between items-baseline pt-2 border-t border-gray-100 font-black text-sm">
                        <span>Total Paid</span>
                        <span class="text-base text-black">₹{{ number_format($order->total) }}</span>
                    </div>
                </div>
            </div>

            {{-- Shipping Address & Payment Info Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
                <div class="space-y-6">
                    {{-- Delivery Address --}}
                    <div>
                        <h3 class="text-xs font-heading font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-3 mb-3">
                            Delivery Address
                        </h3>
                        @if($order->address)
                            <div class="text-xs text-gray-600 space-y-1 leading-relaxed">
                                <p class="font-bold text-black text-sm capitalize">{{ $order->address->name }}</p>
                                <p>{{ $order->address->line1 }}</p>
                                @if($order->address->line2)<p>{{ $order->address->line2 }}</p>@endif
                                <p>{{ $order->address->city }}, {{ $order->address->state }} - <span class="font-bold text-black">{{ $order->address->pincode }}</span></p>
                                <p class="pt-1 text-black font-semibold">📞 Phone: {{ $order->address->phone }}</p>
                            </div>
                        @else
                            <p class="text-xs text-gray-400">Standard Delivery Address</p>
                        @endif
                    </div>

                    {{-- Payment Details --}}
                    <div>
                        <h3 class="text-xs font-heading font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-3 mb-3">
                            Payment Info
                        </h3>
                        <div class="text-xs space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">Method</span>
                                <span class="font-bold text-black uppercase">{{ $order->payment_method === 'razorpay' ? 'Online (Razorpay)' : 'Cash on Delivery (COD)' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">Status</span>
                                @if($order->payment_status === 'paid')
                                    <span class="font-bold text-green-700 bg-green-50 px-2 py-0.5 rounded uppercase">PAID ✓</span>
                                @else
                                    <span class="font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded uppercase">PAY ON DELIVERY</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Support Box --}}
                <div class="mt-6 pt-4 border-t border-gray-100 bg-gray-50 rounded-xl p-3 text-center">
                    <p class="text-[11px] text-gray-500 font-medium">Need help with your order?</p>
                    <a href="{{ route('frontend.page.show', 'contact') }}" class="text-xs font-bold text-black hover:underline uppercase tracking-wider block mt-1">
                        Contact Support →
                    </a>
                </div>
            </div>

        </div>

        {{-- ACTION BUTTONS --}}
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ route('account.orders') }}" class="w-full sm:w-auto bg-black text-white hover:bg-neutral-800 font-black uppercase tracking-wider text-xs px-8 py-4 rounded-xl shadow-md hover:shadow-lg transition-all text-center">
                View My Orders
            </a>
            <a href="{{ route('frontend.products.index') }}" class="w-full sm:w-auto bg-white border border-gray-300 hover:border-black text-black font-bold uppercase tracking-wider text-xs px-8 py-4 rounded-xl transition-all text-center">
                Continue Shopping
            </a>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    // GA4 Purchase Event
    if (typeof gtag === 'function') {
        gtag('event', 'purchase', {
            transaction_id: '{{ $order->order_number }}',
            value: {{ $order->total }},
            currency: 'INR',
            items: [
                @foreach($order->items as $item)
                {
                    item_id: '{{ $item->variant->sku ?? $item->variant_id }}',
                    item_name: '{{ $item->variant->product->name }}',
                    price: {{ $item->price }},
                    quantity: {{ $item->quantity }}
                },
                @endforeach
            ]
        });
    }

    // Meta Purchase Event
    if (typeof fbq === 'function') {
        fbq('track', 'Purchase', {
            value: {{ $order->total }},
            currency: 'INR',
            content_type: 'product',
            content_ids: [
                @foreach($order->items as $item)
                '{{ $item->variant->sku ?? $item->variant_id }}',
                @endforeach
            ]
        });
    }
</script>
@endpush
