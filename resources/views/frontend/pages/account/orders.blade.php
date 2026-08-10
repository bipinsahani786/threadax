@extends('frontend.layouts.account')

@section('account_content')
<div class="bg-white border border-brand-border p-6 sm:p-10 shadow-sm">
    <h2 class="text-2xl font-heading font-black text-brand-dark uppercase tracking-tight mb-8">My Orders</h2>

    @if($orders->count() > 0)
        <div class="space-y-6">
            @foreach($orders as $order)
                <div class="border border-brand-border rounded-lg overflow-hidden">
                    <div class="bg-brand-light px-4 sm:px-6 py-4 border-b border-brand-border flex flex-wrap justify-between items-center gap-4">
                        <div class="flex flex-wrap gap-6 sm:gap-10">
                            <div>
                                <span class="block text-[10px] font-bold uppercase tracking-widest text-brand-muted mb-1">Order Placed</span>
                                <span class="text-sm font-medium">{{ $order->created_at->format('M d, Y') }}</span>
                            </div>
                            <div>
                                <span class="block text-[10px] font-bold uppercase tracking-widest text-brand-muted mb-1">Total</span>
                                <span class="text-sm font-medium">₹{{ number_format($order->total, 2) }}</span>
                            </div>
                            <div>
                                <span class="block text-[10px] font-bold uppercase tracking-widest text-brand-muted mb-1">Order #</span>
                                <span class="text-sm font-medium">{{ $order->order_number ?? 'TX-'.$order->id }}</span>
                            </div>
                        </div>
                        <div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize
                                @if($order->status === 'delivered') bg-green-100 text-green-800
                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif
                            ">
                                {{ $order->status }}
                            </span>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div class="flex -space-x-2 overflow-hidden">
                            @foreach($order->items->take(4) as $item)
                                @if($item->variant && $item->variant->product && $item->variant->product->primaryImage)
                                    <img class="inline-block h-12 w-12 sm:h-16 sm:w-16 rounded-full ring-2 ring-white object-cover" src="{{ $item->variant->product->primaryImage->url }}" alt="{{ $item->name }}">
                                @else
                                    <div class="inline-flex items-center justify-center h-12 w-12 sm:h-16 sm:w-16 rounded-full ring-2 ring-white bg-gray-200">
                                        <span class="text-xs font-medium text-gray-500">Img</span>
                                    </div>
                                @endif
                            @endforeach
                            @if($order->items->count() > 4)
                                <div class="inline-flex items-center justify-center h-12 w-12 sm:h-16 sm:w-16 rounded-full ring-2 ring-white bg-brand-light">
                                    <span class="text-xs font-bold text-brand-text">+{{ $order->items->count() - 4 }}</span>
                                </div>
                            @endif
                        </div>
                        <a href="{{ route('account.orders.show', $order->id) }}" class="btn-primary py-2 px-6 text-xs whitespace-nowrap">View Details</a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    @else
        <div class="text-center py-16 border-2 border-dashed border-brand-border rounded-xl">
            <svg class="mx-auto h-12 w-12 text-brand-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <h3 class="mt-2 text-sm font-bold text-brand-dark uppercase tracking-wider">No orders</h3>
            <p class="mt-1 text-sm text-brand-muted">You haven't placed any orders yet.</p>
            <div class="mt-6">
                <a href="{{ route('frontend.products.index') }}" class="btn-primary">
                    Start Shopping
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
