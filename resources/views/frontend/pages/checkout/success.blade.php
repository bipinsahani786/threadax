@extends('frontend.layouts.app')

@section('title', 'Order Successful')

@section('content')
<div class="bg-brand-off-white min-h-screen py-12 flex items-center justify-center">
    <div class="max-w-2xl w-full mx-auto px-4 lg:px-8">
        
        <div class="bg-white border border-brand-border rounded-lg p-8 sm:p-12 text-center shadow-lg">
            
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h1 class="text-3xl font-heading font-extrabold uppercase tracking-tight text-brand-dark mb-4">Order Confirmed!</h1>
            
            <p class="text-brand-muted mb-8 text-lg">Thank you for your purchase. We have received your order.</p>

            <div class="bg-brand-light border border-brand-border rounded-lg p-6 mb-8 inline-block text-left w-full sm:w-auto min-w-[300px]">
                <p class="text-xs font-bold uppercase tracking-widest text-brand-muted mb-1">Order Number</p>
                <p class="text-2xl font-bold text-brand-text mb-4">{{ $order->order_number }}</p>

                <p class="text-xs font-bold uppercase tracking-widest text-brand-muted mb-1">Payment Method</p>
                <p class="text-sm font-semibold text-brand-dark mb-4 uppercase">{{ $order->payment_method }}</p>
                
                <p class="text-xs font-bold uppercase tracking-widest text-brand-muted mb-1">Total Amount</p>
                <p class="text-sm font-semibold text-brand-dark">₹{{ number_format($order->total) }}</p>
            </div>

            <p class="text-sm text-brand-muted mb-8">
                We'll email you an order confirmation with details and tracking info once it ships.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('account.dashboard') }}" class="btn-secondary px-8 py-3 bg-white border border-brand-border text-brand-dark hover:bg-brand-light transition-colors rounded">View Order History</a>
                <a href="{{ route('frontend.products.index') }}" class="btn-primary px-8 py-3 bg-brand-text text-white hover:bg-brand-dark transition-colors rounded">Continue Shopping</a>
            </div>
            
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
