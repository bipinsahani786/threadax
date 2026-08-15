@extends('frontend.layouts.account')

@section('account_content')
<div class="bg-white border border-brand-border rounded-2xl p-4 sm:p-6 lg:p-7 shadow-xs">
    
    {{-- Header --}}
    <div class="mb-5 sm:mb-6 flex flex-wrap items-center justify-between gap-2 border-b border-brand-border/60 pb-4">
        <div>
            <h2 class="text-base sm:text-lg font-heading font-extrabold text-brand-dark flex items-center gap-2">
                <span>Payment & Transaction History</span>
                <span class="text-xs font-normal text-brand-muted">💳</span>
            </h2>
            <p class="text-xs sm:text-sm text-brand-muted mt-0.5">Audit log of all your online payments, refunds, and Cash on Delivery charges.</p>
        </div>
        @if($transactions->count() > 0)
            <span class="text-xs font-extrabold bg-brand-off-white border border-brand-border px-3 py-1 rounded-full text-brand-dark shadow-2xs">
                {{ $transactions->total() }} {{ Str::plural('Transaction', $transactions->total()) }}
            </span>
        @endif
    </div>

    @if($transactions->count() > 0)
        <div class="space-y-3">
            @foreach($transactions as $txn)
                @php $badge = $txn->status_badge; @endphp
                <div class="border border-brand-border rounded-2xl p-4 sm:p-5 bg-white hover:border-brand-dark/40 transition-all flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    
                    {{-- Left Details --}}
                    <div class="space-y-1.5 flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider border {{ $badge['bg'] }}">
                                <span>{{ $badge['icon'] }}</span>
                                <span>{{ $badge['label'] }}</span>
                            </span>

                            @if($txn->gateway === 'razorpay')
                                <span class="px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 font-bold border border-blue-200 text-[10px] uppercase">
                                    💳 Razorpay {{ $txn->method ? '• ' . strtoupper($txn->method) : '' }}
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-bold border border-slate-200 text-[10px] uppercase">
                                    💵 Cash on Delivery
                                </span>
                            @endif

                            @if($txn->order)
                                <a href="{{ route('account.orders.show', $txn->order_id) }}" class="text-xs font-mono font-bold text-brand-dark hover:underline">
                                    Order #{{ $txn->order->order_number }}
                                </a>
                            @endif
                        </div>

                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-brand-muted">
                            @if($txn->transaction_id)
                                <span>Ref: <strong class="text-brand-dark font-mono">{{ $txn->transaction_id }}</strong></span>
                            @endif
                            <span>{{ $txn->created_at->format('M d, Y • h:i A') }}</span>
                        </div>

                        @if($txn->error_description)
                            <p class="text-xs text-rose-600 font-medium">
                                ⚠️ {{ $txn->error_description }}
                            </p>
                        @endif
                    </div>

                    {{-- Right Amount & Action --}}
                    <div class="flex sm:flex-col items-center sm:items-end justify-between w-full sm:w-auto pt-2 sm:pt-0 border-t sm:border-t-0 border-brand-border/60">
                        <span class="text-base sm:text-lg font-heading font-black text-brand-dark">
                            @if($txn->status === 'refunded')
                                <span class="text-purple-700">+₹{{ number_format($txn->amount, 2) }}</span>
                            @else
                                ₹{{ number_format($txn->amount, 2) }}
                            @endif
                        </span>

                        @if($txn->order)
                            <a href="{{ route('account.orders.show', $txn->order_id) }}" class="mt-1 text-[11px] font-extrabold uppercase text-brand-dark hover:underline flex items-center gap-1">
                                <span>View Order</span>
                                <span>➔</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if($transactions->hasPages())
            <div class="mt-6">
                {{ $transactions->links() }}
            </div>
        @endif

    @else
        <div class="text-center py-12 px-4 border border-dashed border-brand-border rounded-2xl">
            <div class="w-16 h-16 rounded-2xl bg-brand-light flex items-center justify-center text-3xl mx-auto mb-3">
                💳
            </div>
            <h3 class="font-heading font-bold text-base text-brand-dark">No Payment Transactions Recorded</h3>
            <p class="text-xs text-brand-muted mt-1 max-w-sm mx-auto">Your online payment receipts and refund logs will appear here once you place orders.</p>
            <a href="{{ route('frontend.products.index') }}" class="inline-block mt-4 px-5 py-2.5 rounded-xl bg-brand-dark text-white text-xs font-bold uppercase tracking-wider hover:bg-brand-text transition-colors">
                Start Shopping
            </a>
        </div>
    @endif

</div>
@endsection
