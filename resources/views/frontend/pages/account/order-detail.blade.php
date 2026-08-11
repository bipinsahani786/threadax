@extends('frontend.layouts.account')

@section('account_content')
<div class="bg-white border border-brand-border p-6 sm:p-10 shadow-sm">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <a href="{{ route('account.orders') }}" class="text-sm font-bold text-brand-muted hover:text-brand-text mb-2 inline-block uppercase tracking-wider">&larr; Back to Orders</a>
            <h2 class="text-2xl font-heading font-black text-brand-dark uppercase tracking-tight">Order {{ $order->order_number ?? 'TX-'.$order->id }}</h2>
            <p class="text-sm text-brand-muted mt-1">Placed on {{ $order->created_at->format('F d, Y \a\t H:i A') }}</p>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('account.orders.invoice', $order->id) }}" class="inline-flex items-center gap-2 border border-brand-dark px-4 py-1.5 text-xs font-bold uppercase tracking-widest text-brand-dark hover:bg-brand-dark hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Invoice
            </a>
            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest
                @if($order->status === 'delivered') bg-green-100 text-green-800
                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                @else bg-yellow-100 text-yellow-800 @endif
            ">
                {{ $order->status }}
            </span>
        </div>
    </div>

    {{-- Items --}}
    <div class="border border-brand-border rounded-lg overflow-hidden mb-8">
        <div class="bg-brand-light px-6 py-3 border-b border-brand-border">
            <h3 class="text-xs font-black uppercase tracking-widest text-brand-dark">Order Items</h3>
        </div>
        <ul role="list" class="divide-y divide-brand-border">
            @foreach($order->items as $item)
                <li class="p-6 flex gap-6">
                    <div class="flex-shrink-0 w-20 h-24 sm:w-24 sm:h-32 border border-brand-border bg-brand-light overflow-hidden rounded-md">
                        @if($item->variant && $item->variant->product && $item->variant->product->primaryImage)
                            <img src="{{ $item->variant->product->primaryImage->url }}" alt="{{ $item->name }}" class="w-full h-full object-center object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">No Img</div>
                        @endif
                    </div>
                    <div class="flex-1 flex flex-col justify-center">
                        <div class="flex justify-between text-base font-bold text-brand-dark">
                            <h3>{{ $item->name }}</h3>
                            <p class="ml-4">₹{{ number_format($item->total_price, 2) }}</p>
                        </div>
                        <p class="mt-1 text-sm text-brand-muted">
                            @if($item->color) {{ $item->color }} @endif
                            @if($item->color && $item->size) / @endif
                            @if($item->size) {{ $item->size }} @endif
                        </p>
                        <p class="mt-1 text-sm text-brand-muted">Qty: {{ $item->quantity }} x ₹{{ number_format($item->unit_price, 2) }}</p>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Details Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        {{-- Shipping Address --}}
        <div>
            <h3 class="text-xs font-black uppercase tracking-widest text-brand-dark mb-4 border-b border-brand-border pb-2">Shipping Address</h3>
            @if($order->address)
                <address class="not-italic text-sm text-brand-muted space-y-1">
                    <span class="block font-bold text-brand-dark">{{ $order->address->name }}</span>
                    <span class="block">{{ $order->address->line1 }}</span>
                    @if($order->address->line2) <span class="block">{{ $order->address->line2 }}</span> @endif
                    <span class="block">{{ $order->address->city }}, {{ $order->address->state }} {{ $order->address->pincode }}</span>
                    <span class="block mt-2">Phone: {{ $order->address->phone }}</span>
                </address>
            @else
                <p class="text-sm text-brand-muted">Address not found.</p>
            @endif
        </div>

        {{-- Payment Info --}}
        <div>
            <h3 class="text-xs font-black uppercase tracking-widest text-brand-dark mb-4 border-b border-brand-border pb-2">Payment Information</h3>
            <div class="text-sm text-brand-muted space-y-2">
                <p><span class="font-bold text-brand-dark">Method:</span> {{ $order->payment_method === 'razorpay' ? 'Razorpay (Online)' : 'Cash on Delivery' }}</p>
                <p><span class="font-bold text-brand-dark">Status:</span> 
                    <span class="capitalize
                        @if($order->payment_status === 'paid') text-green-600
                        @elseif($order->payment_status === 'failed') text-red-600
                        @else text-yellow-600 @endif
                    ">
                        {{ $order->payment_status }}
                    </span>
                </p>
                @if($order->payment && $order->payment->gateway_payment_id)
                    <p><span class="font-bold text-brand-dark">Transaction ID:</span> {{ $order->payment->gateway_payment_id }}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Summary --}}
    <div class="border-t border-brand-border pt-8 flex justify-end">
        <dl class="w-full sm:w-1/2 lg:w-1/3 space-y-4 text-sm text-brand-muted">
            <div class="flex justify-between">
                <dt>Subtotal</dt>
                <dd class="text-brand-dark font-medium">₹{{ number_format($order->subtotal, 2) }}</dd>
            </div>
            @if($order->discount > 0)
            <div class="flex justify-between">
                <dt>Discount</dt>
                <dd class="text-green-600 font-medium">-₹{{ number_format($order->discount, 2) }}</dd>
            </div>
            @endif
            <div class="flex justify-between">
                <dt>Shipping</dt>
                <dd class="text-brand-dark font-medium">{{ $order->shipping == 0 ? 'Free' : '₹'.number_format($order->shipping, 2) }}</dd>
            </div>
            <div class="flex justify-between items-center border-t border-brand-border pt-4">
                <dt class="text-base font-bold text-brand-dark uppercase tracking-widest">Total</dt>
                <dd class="text-xl font-heading font-black text-brand-dark">₹{{ number_format($order->total, 2) }}</dd>
            </div>
        </dl>
    </div>

</div>
@endsection
