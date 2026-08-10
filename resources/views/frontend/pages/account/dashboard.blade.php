@extends('frontend.layouts.account')

@section('account_content')
<div class="bg-white border border-brand-border p-6 sm:p-10 shadow-sm relative overflow-hidden">
    
    <div class="relative z-10">
        <h2 class="text-2xl font-heading font-black text-brand-dark uppercase tracking-tight mb-2">Hello, {{ $user->name }}!</h2>
        <p class="text-brand-muted text-sm mb-8">From your account dashboard you can view your recent orders, manage your shipping addresses, and edit your password and account details.</p>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
            <div class="bg-brand-light border border-brand-border p-6 flex flex-col items-center justify-center text-center">
                <span class="text-4xl font-heading font-black text-brand-text">{{ $orderCount }}</span>
                <span class="text-xs font-bold uppercase tracking-widest text-brand-muted mt-2">Total Orders</span>
            </div>
            
            <div class="bg-brand-light border border-brand-border p-6 flex flex-col items-center justify-center text-center">
                <span class="text-4xl font-heading font-black text-brand-text">{{ $user->addresses()->count() }}</span>
                <span class="text-xs font-bold uppercase tracking-widest text-brand-muted mt-2">Saved Addresses</span>
            </div>

            <div class="bg-brand-light border border-brand-border p-6 flex flex-col items-center justify-center text-center">
                <span class="text-4xl font-heading font-black text-brand-text">0</span>
                <span class="text-xs font-bold uppercase tracking-widest text-brand-muted mt-2">Wishlist Items</span>
            </div>
        </div>

        @if($recentOrder)
            <div class="border-t border-brand-border pt-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-heading font-black text-brand-dark uppercase tracking-tight">Recent Order</h3>
                    <a href="{{ route('account.orders') }}" class="text-sm font-bold text-brand-text hover:underline uppercase tracking-wider">View All</a>
                </div>

                <div class="border border-brand-border rounded-lg overflow-hidden">
                    <div class="bg-brand-light px-6 py-4 border-b border-brand-border flex justify-between items-center">
                        <div>
                            <span class="block text-xs font-bold uppercase tracking-widest text-brand-muted mb-1">Order Placed</span>
                            <span class="font-medium">{{ $recentOrder->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="text-right">
                            <span class="block text-xs font-bold uppercase tracking-widest text-brand-muted mb-1">Status</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize
                                @if($recentOrder->status === 'delivered') bg-green-100 text-green-800
                                @elseif($recentOrder->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif
                            ">
                                {{ $recentOrder->status }}
                            </span>
                        </div>
                    </div>
                    <div class="p-6 flex justify-between items-center">
                        <div>
                            <p class="font-bold text-lg">{{ $recentOrder->order_number ?? 'TX-'.$recentOrder->id }}</p>
                            <p class="text-sm text-brand-muted mt-1">{{ $recentOrder->items()->count() }} items • ₹{{ number_format($recentOrder->total, 2) }}</p>
                        </div>
                        <a href="{{ route('account.orders.show', $recentOrder->id) }}" class="btn-primary py-2 px-6 text-xs">View Details</a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Decorative Background Logo --}}
    <div class="absolute -bottom-20 -right-20 text-brand-light/30 z-0 pointer-events-none">
        <svg width="400" height="400" viewBox="0 0 100 100" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path d="M10 90 L50 10 L90 90 Z" />
        </svg>
    </div>
</div>
@endsection
