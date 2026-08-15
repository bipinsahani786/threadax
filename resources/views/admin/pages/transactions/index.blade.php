@extends('admin.layouts.app')

@section('title', 'Payment Transactions Ledger')
@section('page-title', 'Financial & Gateway Transactions')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto" x-data="{ selectedPayload: null, showPayloadModal: false }">

    {{-- Executive Financial Metric Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Successful Volume --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Settled Volume</p>
                    <h3 class="text-2xl font-heading font-black text-slate-900 mt-1">₹{{ number_format($totalVolume, 2) }}</h3>
                    <p class="text-[11px] text-emerald-600 font-bold mt-1">🟢 Successful Online Volume</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-xs">
                    💰
                </div>
            </div>
        </div>

        {{-- Successful Payments Count --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Successful Payments</p>
                    <h3 class="text-2xl font-heading font-black text-emerald-700 mt-1">{{ number_format($successCount) }}</h3>
                    <p class="text-[11px] text-slate-400 mt-1">Confirmed transactions</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-xs">
                    ✅
                </div>
            </div>
        </div>

        {{-- Failed / Dropped Transactions --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Failed / Dropped</p>
                    <h3 class="text-2xl font-heading font-black text-rose-600 mt-1">{{ number_format($failedCount) }}</h3>
                    <p class="text-[11px] text-rose-500 font-bold mt-1">Needs attention / abandoned</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl shadow-xs">
                    ⚠️
                </div>
            </div>
        </div>

        {{-- Total Refunded Volume --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Refunded Volume</p>
                    <h3 class="text-2xl font-heading font-black text-purple-700 mt-1">₹{{ number_format($refundedVolume, 2) }}</h3>
                    <p class="text-[11px] text-purple-600 font-bold mt-1">{{ $refundedCount }} refunds issued</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shadow-xs">
                    ↩️
                </div>
            </div>
        </div>
    </div>

    {{-- Filter & Search Toolbar --}}
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
        <form method="GET" action="{{ route('admin.transactions.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            {{-- Search input --}}
            <div class="lg:col-span-2">
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Search Reference</label>
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Txn ID, Pay ID, Order #, Name..." 
                           class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 outline-none focus:border-slate-900 focus:bg-white transition-colors">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                </div>
            </div>

            {{-- Status Filter --}}
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Status</label>
                <select name="status" class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 outline-none focus:border-slate-900">
                    <option value="">All Statuses</option>
                    <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>🟢 Success</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>🔴 Failed</option>
                    <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>↩️ Refunded</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>🟡 Pending</option>
                </select>
            </div>

            {{-- Gateway Filter --}}
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Gateway</label>
                <select name="gateway" class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 outline-none focus:border-slate-900">
                    <option value="">All Gateways</option>
                    <option value="razorpay" {{ request('gateway') === 'razorpay' ? 'selected' : '' }}>💳 Razorpay</option>
                    <option value="cod" {{ request('gateway') === 'cod' ? 'selected' : '' }}>💵 Cash on Delivery</option>
                </select>
            </div>

            {{-- Filter Actions --}}
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-xs transition-all cursor-pointer">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'gateway', 'event']))
                    <a href="{{ route('admin.transactions.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-all">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Transactions Table --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200/80 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        <th class="py-3.5 px-4">Transaction / Ref</th>
                        <th class="py-3.5 px-4">Order #</th>
                        <th class="py-3.5 px-4">Customer</th>
                        <th class="py-3.5 px-4">Amount</th>
                        <th class="py-3.5 px-4">Gateway & Method</th>
                        <th class="py-3.5 px-4">Event Type</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Timestamp</th>
                        <th class="py-3.5 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($transactions as $txn)
                        @php $badge = $txn->status_badge; @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            {{-- Transaction ID --}}
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-1.5">
                                    <span class="font-mono font-bold text-slate-900 select-all" title="{{ $txn->transaction_id }}">
                                        {{ $txn->transaction_id ? \Illuminate\Support\Str::limit($txn->transaction_id, 20) : 'N/A' }}
                                    </span>
                                    @if($txn->transaction_id)
                                        <button onclick="navigator.clipboard.writeText('{{ $txn->transaction_id }}'); if(window.showToast) window.showToast('Copied ID', 'info');" class="text-slate-300 hover:text-slate-600 cursor-pointer">
                                            📋
                                        </button>
                                    @endif
                                </div>
                                @if($txn->ip_address)
                                    <span class="text-[10px] text-slate-400 font-mono">IP: {{ $txn->ip_address }}</span>
                                @endif
                            </td>

                            {{-- Order Number --}}
                            <td class="py-3.5 px-4">
                                @if($txn->order)
                                    <a href="{{ route('admin.orders.show', $txn->order_id) }}" class="font-mono font-bold text-blue-600 hover:underline">
                                        {{ $txn->order->order_number }}
                                    </a>
                                @else
                                    <span class="text-slate-400 italic">No order</span>
                                @endif
                            </td>

                            {{-- Customer --}}
                            <td class="py-3.5 px-4">
                                @if($txn->user)
                                    <p class="font-bold text-slate-900">{{ $txn->user->name }}</p>
                                    <p class="text-[11px] text-slate-400">{{ $txn->user->email }}</p>
                                @elseif($txn->order)
                                    <p class="font-bold text-slate-900">{{ $txn->order->shipping_name }}</p>
                                    <p class="text-[11px] text-slate-400">{{ $txn->order->shipping_email }}</p>
                                @else
                                    <span class="text-slate-400 italic">Guest User</span>
                                @endif
                            </td>

                            {{-- Amount --}}
                            <td class="py-3.5 px-4">
                                <span class="font-heading font-extrabold text-slate-900 text-sm">
                                    ₹{{ number_format($txn->amount, 2) }}
                                </span>
                            </td>

                            {{-- Gateway & Method --}}
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-1.5">
                                    @if($txn->gateway === 'razorpay')
                                        <span class="px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 font-bold border border-blue-200 uppercase text-[10px]">
                                            💳 Razorpay
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-bold border border-slate-200 uppercase text-[10px]">
                                            💵 COD
                                        </span>
                                    @endif
                                </div>
                                @if($txn->method)
                                    <span class="text-[10px] text-slate-400 font-bold uppercase block mt-0.5">{{ $txn->method }}</span>
                                @endif
                            </td>

                            {{-- Event Type --}}
                            <td class="py-3.5 px-4">
                                <span class="font-mono text-[11px] font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded">
                                    {{ $txn->event }}
                                </span>
                                @if($txn->error_description)
                                    <p class="text-[10px] text-rose-500 mt-0.5 max-w-[200px] truncate" title="{{ $txn->error_description }}">
                                        {{ $txn->error_description }}
                                    </p>
                                @endif
                            </td>

                            {{-- Status Badge --}}
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wide border {{ $badge['bg'] }}">
                                    <span>{{ $badge['icon'] }}</span>
                                    <span>{{ $badge['label'] }}</span>
                                </span>
                            </td>

                            {{-- Timestamp --}}
                            <td class="py-3.5 px-4 text-slate-500 whitespace-nowrap">
                                <p class="font-bold text-slate-900">{{ $txn->created_at->format('M d, Y') }}</p>
                                <p class="text-[10px] text-slate-400">{{ $txn->created_at->format('h:i:s A') }}</p>
                            </td>

                            {{-- Action / Payload Inspector --}}
                            <td class="py-3.5 px-4 text-right">
                                <button type="button" 
                                        @click="selectedPayload = {{ json_encode($txn) }}; showPayloadModal = true;" 
                                        class="px-2.5 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-bold text-[11px] transition-colors cursor-pointer inline-flex items-center gap-1">
                                    <span>🔍 Raw Log</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-slate-400">
                                <div class="text-3xl mb-2">💳</div>
                                <p class="font-bold text-slate-700 text-sm">No payment transactions found</p>
                                <p class="text-xs">Incoming and processed gateway logs will appear here in real-time.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

    {{-- Raw Payload JSON Inspector Modal --}}
    <div x-show="showPayloadModal" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
         @keydown.escape.window="showPayloadModal = false">
        <div class="bg-white rounded-3xl border border-slate-200 max-w-2xl w-full p-6 shadow-2xl space-y-4" @click.outside="showPayloadModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="text-xl">🔍</span>
                    <div>
                        <h3 class="text-base font-heading font-black text-slate-900">Transaction Raw Audit Log</h3>
                        <p class="text-xs text-slate-400">Full gateway response payload and request metadata</p>
                    </div>
                </div>
                <button @click="showPayloadModal = false" class="text-slate-400 hover:text-slate-600 font-bold text-lg cursor-pointer">
                    ✕
                </button>
            </div>

            <div class="space-y-3 text-xs" x-show="selectedPayload">
                <div class="grid grid-cols-2 gap-2 bg-slate-50 p-3 rounded-xl border border-slate-200">
                    <div>
                        <span class="text-slate-400 text-[10px] uppercase font-bold block">Event</span>
                        <span class="font-mono font-bold text-slate-900" x-text="selectedPayload?.event"></span>
                    </div>
                    <div>
                        <span class="text-slate-400 text-[10px] uppercase font-bold block">Transaction ID</span>
                        <span class="font-mono font-bold text-blue-600 select-all" x-text="selectedPayload?.transaction_id || 'N/A'"></span>
                    </div>
                    <div>
                        <span class="text-slate-400 text-[10px] uppercase font-bold block">IP Address</span>
                        <span class="font-mono text-slate-700" x-text="selectedPayload?.ip_address || 'N/A'"></span>
                    </div>
                    <div>
                        <span class="text-slate-400 text-[10px] uppercase font-bold block">Created At</span>
                        <span class="font-mono text-slate-700" x-text="selectedPayload?.created_at"></span>
                    </div>
                </div>

                <div x-show="selectedPayload?.error_description" class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-rose-800">
                    <strong class="block font-bold">Error Description:</strong>
                    <span x-text="selectedPayload?.error_description"></span>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Gateway Payload (JSON)</label>
                    <pre class="bg-slate-900 text-emerald-400 p-4 rounded-xl font-mono text-[11px] overflow-x-auto max-h-60" x-text="JSON.stringify(selectedPayload?.payload, null, 2)"></pre>
                </div>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="button" @click="showPayloadModal = false" class="px-5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs cursor-pointer">
                    Close Inspector
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
