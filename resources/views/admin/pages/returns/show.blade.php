@extends('admin.layouts.app')

@section('title', 'Return #' . $returnRequest->return_number)
@section('page-title', 'Return & Exchange Management')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto" x-data="{ showRefundModal: false, refundAmount: '{{ $returnRequest->refund_amount }}', returnStatus: '{{ $returnRequest->status }}' }">

    {{-- Top Action Header Bar --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <a href="{{ route('admin.returns.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900 inline-flex items-center gap-1 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                    <span>Returns Ledger</span>
                </a>
                <span class="text-slate-300">•</span>
                <span class="text-xs font-mono font-bold text-blue-600">Request #{{ $returnRequest->return_number }}</span>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-xl sm:text-2xl font-heading font-black text-slate-900 tracking-tight flex items-center gap-2">
                    <span>Return Request</span>
                    <span class="text-xs font-mono px-2 py-0.5 rounded-md bg-slate-100 font-bold uppercase text-slate-700">
                        {{ $returnRequest->type === 'exchange' ? '🔄 Size Exchange' : '↩ Return & Refund' }}
                    </span>
                </h1>

                {{-- Status Badge --}}
                @php $badge = $returnRequest->status_badge; @endphp
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold capitalize border {{ $badge['bg'] }} {{ $badge['text'] }} {{ $badge['border'] }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $badge['dot'] }}"></span>
                    <span>{{ $badge['label'] }}</span>
                </span>
            </div>

            <p class="text-xs text-slate-400 mt-1">
                Submitted on {{ $returnRequest->created_at->format('M d, Y • h:i A') }} ({{ $returnRequest->created_at->diffForHumans() }}) • Linked to 
                <a href="{{ route('admin.orders.show', $returnRequest->order_id) }}" class="font-bold text-slate-700 hover:underline">Order #{{ $returnRequest->order->order_number }}</a>
            </p>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.orders.show', $returnRequest->order_id) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold border border-slate-200 shadow-2xs transition-all">
                <span>📦 View Parent Order</span>
            </a>

            {{-- WhatsApp Contact --}}
            @php
                $phone = $returnRequest->order->address->phone ?? ($returnRequest->user->phone ?? null);
                $cleanPhone = $phone ? preg_replace('/[^0-9]/', '', $phone) : null;
                if ($cleanPhone && strlen($cleanPhone) === 10) {
                    $cleanPhone = '91' . $cleanPhone;
                }
            @endphp
            @if($cleanPhone)
                <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode('Hello ' . ($returnRequest->order->address->name ?? 'there') . ', regarding your ThreadAX Return Request #' . $returnRequest->return_number . ' for Order #' . $returnRequest->order->order_number . ': ') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 text-xs font-bold transition-all cursor-pointer">
                    <span>💬 WhatsApp Customer</span>
                </a>
            @endif
        </div>
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- LEFT COLUMN (2/3 width): Items, Proof Photos & Customer Address --}}
        <div class="xl:col-span-2 space-y-6">

            {{-- 1. Requested Return Items Table --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-heading font-extrabold text-slate-900">Return Drops & Inventory Status</h2>
                        <p class="text-xs text-slate-400">{{ $returnRequest->items->count() }} items under return</p>
                    </div>

                    {{-- Restock Items Button --}}
                    @if($returnRequest->items->where('is_restocked', false)->count() > 0)
                        <form action="{{ route('admin.returns.restock', $returnRequest->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer" title="Restock returned items back to variant stock">
                                <span>📥 Restock Items to Stock</span>
                            </button>
                        </form>
                    @else
                        <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                            ✓ Items Restocked
                        </span>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="py-3 px-5">Product Details</th>
                                <th class="py-3 px-4 text-center">Qty</th>
                                <th class="py-3 px-4 text-center">Current Size</th>
                                <th class="py-3 px-4 text-center">New Size (Exchange)</th>
                                <th class="py-3 px-5 text-right">Line Value</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @foreach($returnRequest->items as $item)
                                @php
                                    $product = $item->product;
                                    $primaryImg = $product?->primaryImage ?? $product?->images->first();
                                    $imgUrl = $primaryImg?->url;
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
                                                    <span class="font-bold text-slate-900">Streetwear Drop</span>
                                                @endif
                                                <div class="text-[11px] text-slate-400 mt-0.5">
                                                    @if($item->is_restocked)
                                                        <span class="text-emerald-700 font-semibold">✓ Restocked in inventory</span>
                                                    @else
                                                        <span class="text-amber-700 font-semibold">Pending physical restock</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="py-4 px-4 text-center">
                                        <span class="px-2 py-1 rounded-lg bg-slate-100 font-bold text-slate-900">
                                            {{ $item->quantity }}
                                        </span>
                                    </td>

                                    <td class="py-4 px-4 text-center font-bold text-slate-700">
                                        {{ $item->originalVariant?->size ?? 'N/A' }}
                                    </td>

                                    <td class="py-4 px-4 text-center">
                                        @if($item->exchangeVariant)
                                            <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 font-bold border border-blue-200 text-xs">
                                                🔄 {{ $item->exchangeVariant->size }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 font-normal">N/A (Refund)</span>
                                        @endif
                                    </td>

                                    <td class="py-4 px-5 text-right font-heading font-black text-slate-900 text-sm">
                                        ₹{{ number_format($item->total, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Refund Total Bar --}}
                <div class="p-5 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Return Value</span>
                    <span class="text-base font-heading font-black text-slate-900">₹{{ number_format($returnRequest->refund_amount, 2) }}</span>
                </div>
            </div>

            {{-- 2. Customer Reason & Notes Card --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs space-y-4">
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                    <span>📋</span>
                    <span>Customer Feedback & Return Reason</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <span class="text-xs font-semibold text-slate-400 block mb-1">Reason Selected</span>
                        <span class="font-bold text-slate-900 text-sm">{{ $returnRequest->reason_label }}</span>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-slate-400 block mb-1">Preferred Action</span>
                        <span class="font-bold text-slate-900 text-sm capitalize">{{ $returnRequest->type }}</span>
                    </div>
                </div>

                @if($returnRequest->customer_notes)
                    <div class="pt-2">
                        <span class="text-xs font-semibold text-slate-400 block mb-1.5">Customer Comment</span>
                        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-700 leading-relaxed italic">
                            "{{ $returnRequest->customer_notes }}"
                        </div>
                    </div>
                @endif
            </div>

            {{-- 3. Photo Proofs Gallery --}}
            @if($returnRequest->images->isNotEmpty())
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs space-y-3">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                        <span>📸</span>
                        <span>Attached Photo Proofs ({{ $returnRequest->images->count() }})</span>
                    </h3>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach($returnRequest->images as $img)
                            <a href="{{ $img->url }}" target="_blank" class="aspect-[3/4] rounded-xl border border-slate-200 overflow-hidden bg-slate-50 hover:scale-105 transition-transform shadow-2xs block">
                                <img src="{{ $img->url }}" alt="Proof Photo" class="w-full h-full object-cover">
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- 4. Customer Pickup Address Card --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs space-y-3">
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                    <span>📍</span>
                    <span>Doorstep Pickup Address</span>
                </h3>

                @if($returnRequest->order->address)
                    <div class="text-xs text-slate-600 space-y-1 leading-relaxed">
                        <p class="font-bold text-slate-900 text-sm">{{ $returnRequest->order->address->name }}</p>
                        <p>{{ $returnRequest->order->address->line1 }}</p>
                        @if($returnRequest->order->address->line2) <p>{{ $returnRequest->order->address->line2 }}</p> @endif
                        <p>{{ $returnRequest->order->address->city }}, {{ $returnRequest->order->address->state }} — {{ $returnRequest->order->address->pincode }}</p>
                        <p class="pt-1 text-slate-900 font-semibold">📞 Phone: {{ $returnRequest->order->address->phone }}</p>
                    </div>
                @else
                    <p class="text-xs text-slate-400">Address details unavailable.</p>
                @endif
            </div>

        </div>

        {{-- RIGHT COLUMN (1/3 width): Status Transitions, Reverse Pickup, Refund Controls --}}
        <div class="space-y-6">

            {{-- 1. Status & Reverse Pickup Management Form --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs">
                <h3 class="text-sm font-heading font-extrabold text-slate-900 mb-4 pb-3 border-b border-slate-100 flex items-center gap-2">
                    <span>⚙️</span>
                    <span>Manage Return Status</span>
                </h3>

                <form action="{{ route('admin.returns.status.update', $returnRequest->id) }}" method="POST" class="space-y-4">
                    @csrf

                    {{-- Return Status --}}
                    <div>
                        <label for="status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Status <span class="text-rose-500">*</span>
                        </label>
                        <select name="status" id="status" x-model="returnStatus" class="w-full py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 outline-none focus:border-slate-400">
                            <option value="requested">🟡 Review Pending</option>
                            <option value="approved">🔵 Approved (Schedule Pickup)</option>
                            <option value="pickup_scheduled">🚚 Reverse Pickup Scheduled</option>
                            <option value="picked_up">📦 Picked Up & In Transit</option>
                            <option value="received_at_hub">🔍 Received at Hub (QC Passed)</option>
                            <option value="refunded">💵 Refunded</option>
                            <option value="exchanged">🔄 Exchange Dispatched</option>
                            <option value="completed">✨ Completed</option>
                            <option value="rejected">🔴 Rejected</option>
                        </select>
                    </div>

                    {{-- Reverse Courier Name --}}
                    <div>
                        <label for="pickup_courier" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Reverse Pickup Courier
                        </label>
                        <input type="text" 
                               name="pickup_courier" 
                               id="pickup_courier" 
                               value="{{ $returnRequest->pickup_courier }}" 
                               placeholder="e.g. Delhivery Reverse, Shadowfax, BlueDart" 
                               class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 outline-none focus:border-slate-400">
                    </div>

                    {{-- Reverse AWB Number --}}
                    <div>
                        <label for="pickup_awb" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Reverse AWB / Tracking Code
                        </label>
                        <input type="text" 
                               name="pickup_awb" 
                               id="pickup_awb" 
                               value="{{ $returnRequest->pickup_awb }}" 
                               placeholder="e.g. 1928374650" 
                               class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-900 outline-none focus:border-slate-400">
                    </div>

                    {{-- Scheduled Pickup Date --}}
                    <div>
                        <label for="pickup_scheduled_date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Pickup Date
                        </label>
                        <input type="date" 
                               name="pickup_scheduled_date" 
                               id="pickup_scheduled_date" 
                               value="{{ $returnRequest->pickup_scheduled_date ? $returnRequest->pickup_scheduled_date->format('Y-m-d') : '' }}" 
                               class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 outline-none focus:border-slate-400">
                    </div>

                    {{-- Rejection Reason (Shown if Rejected) --}}
                    <div x-show="returnStatus === 'rejected'">
                        <label for="rejection_reason" class="block text-xs font-bold text-rose-700 uppercase tracking-wider mb-1.5">
                            Rejection Reason
                        </label>
                        <textarea name="rejection_reason" 
                                  id="rejection_reason" 
                                  rows="2" 
                                  placeholder="Why is this return request being rejected?" 
                                  class="w-full p-2.5 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-900 outline-none focus:border-rose-400">{{ $returnRequest->rejection_reason }}</textarea>
                    </div>

                    {{-- Internal Admin Notes --}}
                    <div>
                        <label for="admin_notes" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Internal Admin Notes
                        </label>
                        <textarea name="admin_notes" 
                                  id="admin_notes" 
                                  rows="2" 
                                  placeholder="Private notes for team..." 
                                  class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 outline-none focus:border-slate-400">{{ $returnRequest->admin_notes }}</textarea>
                    </div>

                    {{-- Auto-restock toggle --}}
                    @if($returnRequest->items->where('is_restocked', false)->count() > 0)
                        <label class="flex items-center gap-2 text-xs text-slate-700 cursor-pointer pt-1">
                            <input type="checkbox" name="restock_items" value="1" class="rounded text-slate-900 focus:ring-slate-900">
                            <span>Auto-restock returned items to inventory on save</span>
                        </label>
                    @endif

                    <div class="pt-2">
                        <button type="submit" class="w-full py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-md active:scale-95 transition-all cursor-pointer">
                            Save Changes & Notify Customer
                        </button>
                    </div>
                </form>
            </div>

            {{-- 2. Refund / Payout Processing Card (For Returns) --}}
            @if($returnRequest->type === 'return')
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-sm font-bold">
                                💵
                            </div>
                            <div>
                                <h3 class="text-sm font-heading font-extrabold text-slate-900">Process Refund</h3>
                                <p class="text-[11px] text-slate-400">Release payout to customer</p>
                            </div>
                        </div>

                        @if($returnRequest->status === 'refunded')
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 uppercase">
                                ✓ Refunded
                            </span>
                        @endif
                    </div>

                    {{-- COD Details or Online Method --}}
                    <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 text-xs space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500">Refund Destination:</span>
                            <span class="font-bold text-slate-900 uppercase">{{ $returnRequest->refund_mode }}</span>
                        </div>

                        @if($returnRequest->upi_id)
                            <div class="flex justify-between items-center">
                                <span class="text-slate-500">Customer UPI:</span>
                                <span class="font-mono font-bold text-emerald-700">{{ $returnRequest->upi_id }}</span>
                            </div>
                        @endif

                        @if($returnRequest->bank_account_number)
                            <div class="pt-1 border-t border-slate-200 space-y-0.5">
                                <p class="font-bold text-slate-900">{{ $returnRequest->bank_beneficiary_name }}</p>
                                <p class="font-mono text-slate-600">A/C: {{ $returnRequest->bank_account_number }}</p>
                                <p class="font-mono text-slate-600">IFSC: {{ $returnRequest->bank_ifsc }}</p>
                            </div>
                        @endif

                        @if($returnRequest->refund_transaction_id)
                            <div class="flex justify-between items-center pt-1 border-t border-slate-200">
                                <span class="text-slate-500">UTR / Ref:</span>
                                <span class="font-mono font-bold text-emerald-700">{{ $returnRequest->refund_transaction_id }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Refund Action Form --}}
                    @if($returnRequest->status !== 'refunded')
                        <form action="{{ route('admin.returns.refund', $returnRequest->id) }}" method="POST" class="space-y-3 pt-1">
                            @csrf
                            <div>
                                <label for="refund_amount" class="block text-[11px] font-bold text-slate-700 uppercase mb-1">
                                    Refund Amount (₹)
                                </label>
                                <input type="number" 
                                       step="0.01" 
                                       name="refund_amount" 
                                       id="refund_amount" 
                                       value="{{ $returnRequest->refund_amount }}" 
                                       class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-900 outline-none focus:border-slate-400">
                            </div>

                            @if($returnRequest->order->payment_method === 'cod')
                                <div>
                                    <label for="transaction_id" class="block text-[11px] font-bold text-slate-700 uppercase mb-1">
                                        Payout UTR / Ref Number <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="transaction_id" 
                                           id="transaction_id" 
                                           placeholder="e.g. UPI-UTR-982736412" 
                                           required 
                                           class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-900 outline-none focus:border-slate-400">
                                </div>
                            @endif

                            <button type="submit" onclick="return confirm('Are you sure you want to release the refund and notify the customer?')" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs active:scale-95 transition-all cursor-pointer">
                                <span>💵 Release & Mark Refunded</span>
                            </button>
                        </form>
                    @endif
                </div>
            @endif

        </div>

    </div>

</div>
@endsection
