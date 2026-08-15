@extends('admin.layouts.app')

@section('title', 'Customers CRM - Admin')
@section('page-title', 'Customer Relations & Directory')

@section('content')
<div class="space-y-6 sm:space-y-8" 
     x-data="{ 
         emailModalOpen: false,
         selectedCustomer: { id: null, name: '', email: '', initials: '' },
         mailSubject: 'ThreadAX Support & Account Update',
         mailMessage: '',
         copiedToast: false,

         openEmailModal(id, name, email, initials) {
             this.selectedCustomer = { id, name, email, initials };
             this.mailSubject = 'Important update regarding your ThreadAX account';
             this.mailMessage = 'Dear ' + name + ',\n\nThank you for being a valued part of the ThreadAX streetwear community. ';
             this.emailModalOpen = true;
         },

         setSubject(text) {
             this.mailSubject = text;
         },

         copyEmail() {
             navigator.clipboard.writeText(this.selectedCustomer.email);
             this.copiedToast = true;
             setTimeout(() => { this.copiedToast = false; }, 3000);
         }
     }">

    {{-- Executive Metric Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        
        {{-- Total Customers --}}
        <a href="{{ route('admin.customers.index') }}" class="group block bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs hover:border-slate-400 hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Directory</span>
                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    👥
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900">{{ number_format($stats['total']) }}</span>
                <span class="text-[11px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">
                    Registered
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Verified customer accounts</p>
        </a>

        {{-- Active Buyers --}}
        <a href="{{ route('admin.customers.index', ['filter' => 'buyers']) }}" class="group block bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs hover:border-emerald-400 hover:shadow-md transition-all {{ request('filter') === 'buyers' ? 'ring-2 ring-emerald-500 border-transparent' : '' }}">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Active Buyers</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    🛍️
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-emerald-950">{{ number_format($stats['buyers']) }}</span>
                <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                    {{ $stats['total'] > 0 ? round(($stats['buyers'] / $stats['total']) * 100) : 0 }}% Conversion
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Placed at least 1 order</p>
        </a>

        {{-- Repeat Loyal Buyers --}}
        <a href="{{ route('admin.customers.index', ['filter' => 'repeat']) }}" class="group block bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs hover:border-purple-400 hover:shadow-md transition-all {{ request('filter') === 'repeat' ? 'ring-2 ring-purple-500 border-transparent' : '' }}">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-purple-700 uppercase tracking-wider">Repeat Buyers</span>
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    🔁
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-purple-950">{{ number_format($stats['repeat']) }}</span>
                <span class="text-[11px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-full border border-purple-200">
                    VIPs (2+ orders)
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">High retention streetwear fans</p>
        </a>

        {{-- Customer LTV Revenue --}}
        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Customer LTV (Net)</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">
                    💰
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900">₹{{ number_format($stats['total_ltv']) }}</span>
                <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                    Revenue
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Total customer lifetime value</p>
        </div>

    </div>

    {{-- Main Customers Ledger Card --}}
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden">
        
        {{-- Filters & Search Toolbar --}}
        <div class="p-5 sm:p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            
            <form action="{{ route('admin.customers.index') }}" method="GET" class="flex flex-1 items-center gap-3 flex-wrap" x-data x-ref="filterForm">
                
                {{-- Search Bar --}}
                <div class="relative w-full sm:w-72">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                        🔍
                    </span>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search customer name, email, phone..." 
                           class="w-full pl-9 pr-4 py-2.5 bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all shadow-2xs">
                </div>

                {{-- Filter Segment --}}
                <select name="filter" class="px-3.5 py-2.5 bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-700 outline-none shadow-2xs cursor-pointer" @change="$refs.filterForm.submit()">
                    <option value="">All Customer Types</option>
                    <option value="buyers" {{ request('filter') === 'buyers' ? 'selected' : '' }}>Active Buyers (≥ 1 Order)</option>
                    <option value="repeat" {{ request('filter') === 'repeat' ? 'selected' : '' }}>Repeat VIPs (≥ 2 Orders)</option>
                    <option value="non_buyers" {{ request('filter') === 'non_buyers' ? 'selected' : '' }}>Leads (0 Orders)</option>
                </select>

                {{-- Sort --}}
                <select name="sort" class="px-3.5 py-2.5 bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-700 outline-none shadow-2xs cursor-pointer" @change="$refs.filterForm.submit()">
                    <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Newest Registered</option>
                    <option value="spent_high" {{ request('sort') === 'spent_high' ? 'selected' : '' }}>Highest Total Spend (LTV)</option>
                    <option value="orders_high" {{ request('sort') === 'orders_high' ? 'selected' : '' }}>Most Orders Placed</option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest Registered</option>
                </select>
                
                <button type="submit" class="hidden">Search</button>

                @if(request()->hasAny(['search', 'filter', 'sort']))
                    <a href="{{ route('admin.customers.index') }}" class="text-xs font-bold text-rose-600 hover:text-rose-700 hover:underline px-2 py-1 transition-colors">
                        ✕ Clear Filters
                    </a>
                @endif
            </form>
            
            <div class="text-xs text-slate-400 font-bold">
                Showing {{ $customers->total() }} Customers
            </div>
        </div>

        {{-- Customers Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-[11px] uppercase font-extrabold text-slate-400 border-b border-slate-100 tracking-wider">
                    <tr>
                        <th scope="col" class="px-6 py-4">Customer Profile</th>
                        <th scope="col" class="px-5 py-4">Contact Details</th>
                        <th scope="col" class="px-5 py-4">Orders Placed</th>
                        <th scope="col" class="px-5 py-4">Lifetime Spend (LTV)</th>
                        <th scope="col" class="px-5 py-4">Registered Date</th>
                        <th scope="col" class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($customers as $customer)
                        @php
                            $initials = collect(explode(' ', $customer->name))->map(fn($part) => strtoupper(substr($part, 0, 1)))->take(2)->join('');
                            $totalSpent = $customer->total_spent ?? 0;
                            $ordersCount = $customer->orders_count ?? 0;
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors group">
                            
                            {{-- Customer Profile --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-slate-900 to-slate-700 text-white font-extrabold text-xs flex items-center justify-center shrink-0 shadow-2xs">
                                        {{ $initials ?: 'TX' }}
                                    </div>
                                    <div>
                                        <div class="font-extrabold text-slate-900 text-sm">
                                            {{ $customer->name }}
                                        </div>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            @if($ordersCount >= 2)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.2 rounded-md text-[9px] font-black bg-purple-50 text-purple-700 border border-purple-200">
                                                    ★ VIP REPEAT
                                                </span>
                                            @elseif($ordersCount == 1)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.2 rounded-md text-[9px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    BUYER
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2 py-0.2 rounded-md text-[9px] font-black bg-slate-100 text-slate-600 border border-slate-200">
                                                    LEAD
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Contact Details --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="text-slate-900 font-bold text-xs">{{ $customer->email }}</div>
                                <div class="text-slate-400 text-[11px] mt-0.5 font-mono">{{ $customer->phone ?? 'No phone' }}</div>
                            </td>

                            {{-- Orders Placed --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($ordersCount > 0)
                                    <a href="{{ route('admin.orders.index', ['search' => $customer->email]) }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-extrabold bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-100 transition-colors">
                                        <span>📦 {{ $ordersCount }} {{ Str::plural('Order', $ordersCount) }}</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 text-xs">0 Orders</span>
                                @endif
                            </td>

                            {{-- Total Spend --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="font-extrabold text-slate-900 text-sm">
                                    ₹{{ number_format($totalSpent, 2) }}
                                </div>
                                @if($ordersCount > 0)
                                    <div class="text-[10px] text-slate-400 font-bold">
                                        Avg: ₹{{ number_format($totalSpent / $ordersCount) }}/order
                                    </div>
                                @endif
                            </td>

                            {{-- Registered Date --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="text-slate-800 font-bold text-xs">{{ $customer->created_at->format('M d, Y') }}</div>
                                <div class="text-[10px] text-slate-400">{{ $customer->created_at->diffForHumans() }}</div>
                            </td>

                            {{-- Actions & Impersonation --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    
                                    {{-- 🎭 Impersonation Mode Button --}}
                                    <form action="{{ route('admin.customers.impersonate', $customer) }}" method="POST" onsubmit="return confirm('Do you want to log into storefront as customer {{ addslashes($customer->name) }}?');" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                title="Log in as Customer (Impersonate)" 
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-black bg-purple-50 text-purple-700 hover:bg-purple-600 hover:text-white border border-purple-200 transition-all shadow-2xs cursor-pointer">
                                            <span>🎭 Login as User</span>
                                        </button>
                                    </form>

                                    {{-- ✉️ Direct Email Modal Trigger --}}
                                    <button type="button" 
                                            @click="openEmailModal({{ $customer->id }}, '{{ addslashes($customer->name) }}', '{{ addslashes($customer->email) }}', '{{ $initials ?: 'TX' }}')"
                                            title="Send Direct Email" 
                                            class="p-2 rounded-xl text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </button>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 mb-3 text-2xl">
                                    👥
                                </div>
                                <h3 class="text-sm font-extrabold text-slate-900 mb-1">No customers found</h3>
                                <p class="text-xs text-slate-400">No customer records match your filter criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Footer --}}
        @if($customers->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="text-xs text-slate-500 font-medium">
                    Showing <strong>{{ $customers->firstItem() }}</strong> to <strong>{{ $customers->lastItem() }}</strong> of <strong>{{ $customers->total() }}</strong> customers
                </div>
                <div>
                    {{ $customers->links() }}
                </div>
            </div>
        @endif

    </div>

    {{-- ════════════ DIRECT EMAIL POPUP MODAL ════════════ --}}
    <div x-show="emailModalOpen" 
         style="display: none;" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-xs p-4" 
         @keydown.escape.window="emailModalOpen = false" 
         x-cloak>
        
        <div class="w-full max-w-xl bg-white rounded-2xl overflow-hidden shadow-2xl border border-slate-200 animate-in fade-in zoom-in-95 duration-200" 
             @click.away="emailModalOpen = false">
            
            {{-- Modal Header --}}
            <div class="p-5 sm:p-6 bg-slate-900 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/10 text-white font-extrabold flex items-center justify-center text-xs" x-text="selectedCustomer.initials"></div>
                    <div>
                        <h3 class="text-sm font-heading font-extrabold">Send Email to Customer</h3>
                        <p class="text-xs text-slate-400 font-medium">Recipient: <span class="text-white font-bold" x-text="selectedCustomer.name"></span> (<span class="font-mono text-[11px]" x-text="selectedCustomer.email"></span>)</p>
                    </div>
                </div>
                <button type="button" @click="emailModalOpen = false" class="text-slate-400 hover:text-white p-1 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Modal Body Form --}}
            <form :action="`/admin/customers/${selectedCustomer.id}/send-email`" method="POST" class="p-5 sm:p-6 space-y-4">
                @csrf
                
                {{-- Subject Presets --}}
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Quick Subject Templates</label>
                    <div class="flex flex-wrap gap-1.5">
                        <button type="button" @click="setSubject('Exclusive VIP Offer for you — ThreadAX')" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-bold rounded-lg transition-colors cursor-pointer">
                            🎁 VIP Offer
                        </button>
                        <button type="button" @click="setSubject('Important update regarding your ThreadAX Account')" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-bold rounded-lg transition-colors cursor-pointer">
                            🔔 Account Notice
                        </button>
                        <button type="button" @click="setSubject('Order & Shipment Assistance — ThreadAX Support')" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-bold rounded-lg transition-colors cursor-pointer">
                            📦 Order Support
                        </button>
                    </div>
                </div>

                {{-- Subject Input --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Subject <span class="text-rose-500">*</span></label>
                    <input type="text" 
                           name="subject" 
                           x-model="mailSubject" 
                           required 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-slate-400 bg-slate-50 hover:bg-slate-100/50 focus:bg-white text-xs font-bold text-slate-900 outline-none transition-all">
                </div>

                {{-- Message Body --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Message Body <span class="text-rose-500">*</span></label>
                    <textarea name="message" 
                              x-model="mailMessage" 
                              rows="6" 
                              required 
                              class="w-full p-3.5 rounded-xl border border-slate-200 focus:border-slate-400 bg-slate-50 hover:bg-slate-100/50 focus:bg-white text-xs font-medium text-slate-900 outline-none transition-all leading-relaxed"></textarea>
                </div>

                {{-- Toast Alert for Copy --}}
                <div x-show="copiedToast" x-transition class="p-2 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold rounded-xl text-center">
                    ✓ Email address copied to clipboard!
                </div>

                {{-- Modal Footer Actions --}}
                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                    <button type="button" @click="copyEmail()" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        <span>Copy Email</span>
                    </button>

                    <div class="flex items-center gap-2">
                        <button type="button" @click="emailModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-500 hover:text-slate-900 transition-colors cursor-pointer">
                            Cancel
                        </button>
                        
                        <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-extrabold rounded-xl transition-all shadow-sm hover:shadow-md flex items-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            <span>Send Email</span>
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
