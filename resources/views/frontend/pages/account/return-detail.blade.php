@extends('frontend.layouts.account')

@section('account_content')
<div class="bg-white border border-brand-border rounded-2xl p-4 sm:p-6 lg:p-8 shadow-xs">
    @php
        $status = strtolower($returnRequest->status);
        $badge = $returnRequest->status_badge;
        
        // Calculate Step Progress
        $stepIndex = 1; // Requested
        if (in_array($status, ['approved'])) $stepIndex = 2;
        elseif (in_array($status, ['pickup_scheduled', 'picked_up'])) $stepIndex = 3;
        elseif (in_array($status, ['received_at_hub'])) $stepIndex = 4;
        elseif (in_array($status, ['refunded', 'exchanged', 'completed'])) $stepIndex = 5;
        elseif (in_array($status, ['rejected', 'cancelled'])) $stepIndex = 0;
    @endphp

    {{-- Top Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6 pb-5 border-b border-brand-border/60">
        <div>
            <a href="{{ route('account.returns') }}" class="text-xs font-bold text-brand-muted hover:text-brand-dark mb-2 inline-flex items-center gap-1.5 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                <span>Back to All Returns</span>
            </a>
            <h2 class="text-base sm:text-xl font-heading font-extrabold text-brand-dark flex items-center gap-2">
                <span>Request #{{ $returnRequest->return_number }}</span>
                <span class="text-xs font-bold uppercase px-2.5 py-0.5 rounded-md bg-brand-off-white border border-brand-border text-brand-dark">
                    {{ $returnRequest->type === 'exchange' ? '🔄 Exchange' : '↩ Return' }}
                </span>
            </h2>
            <p class="text-xs text-brand-muted mt-0.5">
                Submitted on {{ $returnRequest->created_at->format('F d, Y \a\t h:i A') }} • For Order 
                <a href="{{ route('account.orders.show', $returnRequest->order_id) }}" class="font-bold text-brand-dark hover:underline">#{{ $returnRequest->order->order_number }}</a>
            </p>
        </div>

        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold capitalize border {{ $badge['bg'] }} {{ $badge['text'] }} {{ $badge['border'] }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $badge['dot'] }}"></span>
                <span>{{ $badge['label'] }}</span>
            </span>
        </div>
    </div>

    {{-- Visual Progress Stepper (If not rejected/cancelled) --}}
    @if($stepIndex > 0)
        <div class="mb-6 p-4 sm:p-5 bg-brand-off-white/60 border border-brand-border rounded-2xl">
            <div class="flex items-center justify-between text-xs font-bold text-brand-muted uppercase tracking-wider mb-3">
                <span class="flex items-center gap-1.5 text-brand-dark font-extrabold">
                    <span>🔄</span>
                    <span>Return & Pickup Progress</span>
                </span>
                <span>
                    @if($status === 'refunded')
                        <span class="text-emerald-700 font-extrabold">Refund Completed</span>
                    @elseif($status === 'exchanged')
                        <span class="text-emerald-700 font-extrabold">Exchange Dispatched</span>
                    @elseif($status === 'pickup_scheduled')
                        <span class="text-indigo-700 font-extrabold">Pickup Assigned</span>
                    @elseif($status === 'approved')
                        <span class="text-sky-700 font-extrabold">Approved by Team</span>
                    @else
                        <span class="text-amber-700">Under Support Review</span>
                    @endif
                </span>
            </div>

            <div class="grid grid-cols-5 gap-1 sm:gap-3 relative pt-1">
                @php 
                    $steps = [
                        'Requested',
                        'Approved',
                        'Pickup',
                        'QC Check',
                        $returnRequest->type === 'exchange' ? 'Exchange Sent' : 'Refunded'
                    ]; 
                @endphp
                @foreach($steps as $idx => $step)
                    @php $currentStepNum = $idx + 1; @endphp
                    <div class="text-center">
                        <div class="h-2 rounded-full mb-1.5 transition-colors
                            {{ $currentStepNum <= $stepIndex ? ($stepIndex === 5 ? 'bg-emerald-500' : 'bg-brand-dark') : 'bg-brand-border' }}">
                        </div>
                        <span class="text-[10px] sm:text-xs font-bold block truncate
                            {{ $currentStepNum <= $stepIndex ? 'text-brand-dark' : 'text-brand-muted/70' }}">
                            {{ $step }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @elseif($status === 'rejected')
        <div class="mb-6 p-4 sm:p-5 bg-rose-50 border border-rose-200 rounded-2xl">
            <div class="flex items-start gap-3">
                <span class="text-xl">❌</span>
                <div>
                    <h3 class="text-xs sm:text-sm font-extrabold text-rose-800">Return Request Not Approved</h3>
                    <p class="text-xs text-rose-700 mt-1 leading-relaxed">
                        {{ $returnRequest->rejection_reason ?: 'Your request could not be processed as it did not fulfill our 7-day return policy condition or items were not eligible.' }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- Reverse Pickup Details Card (If scheduled) --}}
    @if($returnRequest->pickup_courier || $returnRequest->pickup_awb)
        <div class="mb-6 p-4 bg-slate-900 text-white rounded-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-lg">
                    🚚
                </div>
                <div>
                    <p class="text-xs text-slate-300 font-medium">Reverse Pickup Assigned: <strong class="text-white">{{ $returnRequest->pickup_courier ?? 'Express Reverse Logistics' }}</strong></p>
                    @if($returnRequest->pickup_awb)
                        <p class="text-xs font-mono font-bold text-amber-300 mt-0.5">Reverse AWB: {{ $returnRequest->pickup_awb }}</p>
                    @endif
                </div>
            </div>
            <span class="px-3 py-1 bg-white/10 rounded-xl text-xs font-bold text-slate-200">
                Please keep items ready with tags attached
            </span>
        </div>
    @endif

    {{-- Requested Items List --}}
    <div class="border border-brand-border rounded-2xl overflow-hidden mb-6">
        <div class="bg-brand-off-white/80 px-4 sm:px-5 py-3 border-b border-brand-border flex items-center justify-between">
            <h3 class="text-xs font-extrabold text-brand-dark uppercase tracking-wider">Items in this Request</h3>
            <span class="text-xs font-bold text-brand-muted">{{ $returnRequest->items->count() }} {{ Str::plural('Item', $returnRequest->items->count()) }}</span>
        </div>
        <ul role="list" class="divide-y divide-brand-border/60">
            @foreach($returnRequest->items as $item)
                @php
                    $product = $item->product;
                    $primaryImg = $product?->primaryImage ?? $product?->images->first();
                    $imgUrl = $primaryImg?->url;
                @endphp
                <li class="p-4 sm:p-5 flex gap-4 sm:gap-5 items-start">
                    <div class="flex-shrink-0 w-16 h-20 sm:w-20 sm:h-24 border border-brand-border bg-brand-off-white overflow-hidden rounded-xl shadow-2xs">
                        @if($imgUrl)
                            <img src="{{ $imgUrl }}" alt="{{ $product->name ?? 'Drop' }}" class="w-full h-full object-cover">
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
                            <p class="ml-4 shrink-0 font-heading font-extrabold text-brand-dark">₹{{ number_format($item->total, 2) }}</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-1.5 mt-1.5">
                            @if($item->originalVariant?->size)
                                <span class="bg-brand-off-white border border-brand-border px-2 py-0.5 rounded text-[10px] font-semibold text-brand-dark">
                                    Current Size: <strong>{{ $item->originalVariant->size }}</strong>
                                </span>
                            @endif
                            <span class="bg-brand-off-white border border-brand-border px-2 py-0.5 rounded text-[10px] font-semibold text-brand-dark">
                                Qty: <strong>{{ $item->quantity }}</strong>
                            </span>
                            @if($item->exchangeVariant)
                                <span class="bg-blue-50 text-blue-700 border border-blue-200 px-2.5 py-0.5 rounded text-[11px] font-bold">
                                    🔄 Requested Replacement Size: <strong>{{ $item->exchangeVariant->size }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Details Grid: Reason, Refund Info, Photos --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-6">
        
        {{-- Return Reason & Notes --}}
        <div class="p-4 sm:p-5 bg-brand-off-white/50 rounded-2xl border border-brand-border space-y-3">
            <h3 class="text-xs font-extrabold uppercase tracking-wider text-brand-dark pb-2 border-b border-brand-border flex items-center gap-1.5">
                <span>📋</span>
                <span>Reason & Customer Notes</span>
            </h3>
            <div class="text-xs sm:text-sm text-brand-dark space-y-1.5">
                <p class="text-xs text-brand-muted">Primary Reason:</p>
                <p class="font-bold text-brand-dark">{{ $returnRequest->reason_label }}</p>
                @if($returnRequest->customer_notes)
                    <p class="text-xs text-brand-muted pt-2">Notes:</p>
                    <p class="text-xs text-brand-dark italic bg-white p-3 rounded-xl border border-brand-border">"{{ $returnRequest->customer_notes }}"</p>
                @endif
            </div>
        </div>

        {{-- Refund / Payout Information --}}
        <div class="p-4 sm:p-5 bg-brand-off-white/50 rounded-2xl border border-brand-border space-y-3">
            <h3 class="text-xs font-extrabold uppercase tracking-wider text-brand-dark pb-2 border-b border-brand-border flex items-center gap-1.5">
                <span>💳</span>
                <span>Refund & Payout Method</span>
            </h3>
            <div class="text-xs sm:text-sm text-brand-muted space-y-2">
                @if($returnRequest->type === 'return')
                    <div class="flex justify-between items-center">
                        <span>Refund Amount:</span>
                        <span class="font-bold text-emerald-700 text-sm">₹{{ number_format($returnRequest->refund_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span>Payout Mode:</span>
                        <span class="font-bold text-brand-dark uppercase">{{ str_replace('_', ' ', $returnRequest->refund_mode) }}</span>
                    </div>
                    @if($returnRequest->upi_id)
                        <div class="flex justify-between items-center text-xs">
                            <span>UPI ID:</span>
                            <span class="font-mono font-bold text-brand-dark">{{ $returnRequest->upi_id }}</span>
                        </div>
                    @endif
                    @if($returnRequest->bank_account_number)
                        <div class="text-xs space-y-1 pt-1 bg-white p-2.5 rounded-xl border border-brand-border">
                            <p class="font-bold text-brand-dark">{{ $returnRequest->bank_beneficiary_name }}</p>
                            <p class="font-mono text-brand-muted">A/C: {{ $returnRequest->bank_account_number }} • IFSC: {{ $returnRequest->bank_ifsc }}</p>
                        </div>
                    @endif
                    @if($returnRequest->refund_transaction_id)
                        <div class="flex justify-between items-center text-xs pt-1 border-t border-brand-border">
                            <span>Transaction Ref / UTR:</span>
                            <span class="font-mono font-bold text-emerald-700">{{ $returnRequest->refund_transaction_id }}</span>
                        </div>
                    @endif
                @else
                    <p class="text-xs text-brand-dark font-medium">This is a size exchange request. No refund is required; replacement size will be shipped upon QC pass.</p>
                @endif
            </div>
        </div>

    </div>

    {{-- Attached Proof Photos --}}
    @if($returnRequest->images->isNotEmpty())
        <div class="p-4 sm:p-5 bg-brand-off-white/30 rounded-2xl border border-brand-border mb-6">
            <h3 class="text-xs font-extrabold uppercase tracking-wider text-brand-dark mb-3">Uploaded Photo Proofs</h3>
            <div class="flex flex-wrap gap-3">
                @foreach($returnRequest->images as $img)
                    <a href="{{ $img->url }}" target="_blank" class="w-20 h-24 sm:w-24 sm:h-28 rounded-xl border border-brand-border overflow-hidden bg-white hover:scale-105 transition-transform shadow-2xs block">
                        <img src="{{ $img->url }}" alt="Proof" class="w-full h-full object-cover">
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Need Help Banner --}}
    <div class="p-4 bg-brand-light rounded-2xl border border-brand-border flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <div class="flex items-center gap-2.5">
            <span class="text-xl">💬</span>
            <div>
                <h4 class="text-xs font-extrabold text-brand-dark">Need assistance with your return?</h4>
                <p class="text-[11px] text-brand-muted">Our customer support team is available Mon-Sat (10 AM - 7 PM).</p>
            </div>
        </div>
        <a href="{{ route('frontend.page.show', 'contact') }}" class="px-4 py-2 bg-brand-dark text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-brand-text transition-all">
            Contact Support
        </a>
    </div>

</div>
@endsection
