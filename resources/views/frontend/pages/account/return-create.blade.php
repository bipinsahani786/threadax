@extends('frontend.layouts.account')

@section('account_content')
<div class="bg-white border border-brand-border rounded-2xl p-4 sm:p-6 lg:p-8 shadow-xs"
     x-data="{
         requestType: 'return',
         refundMode: '{{ $order->payment_method === 'cod' ? 'upi' : 'original_source' }}',
         selectedItems: [{{ $order->items->pluck('id')->implode(',') }}],
         toggleItem(id) {
             if (this.selectedItems.includes(id)) {
                 if (this.selectedItems.length > 1) {
                     this.selectedItems = this.selectedItems.filter(i => i !== id);
                 }
             } else {
                 this.selectedItems.push(id);
             }
         }
     }">

    {{-- Top Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6 pb-5 border-b border-brand-border/60">
        <div>
            <a href="{{ route('account.orders.show', $order->id) }}" class="text-xs font-bold text-brand-muted hover:text-brand-dark mb-2 inline-flex items-center gap-1.5 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                <span>Back to Order #{{ $order->order_number }}</span>
            </a>
            <h2 class="text-lg sm:text-2xl font-heading font-extrabold text-brand-dark">Request Return / Size Exchange</h2>
            <p class="text-xs text-brand-muted mt-0.5">7-Day Hassle-Free Return Policy • Free Doorstep Pickup & Fast Processing</p>
        </div>

        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>{{ $order->return_days_left }} Days Left to Return</span>
            </span>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('account.orders.return.store', $order->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        {{-- 1. Choose Request Type (Return vs Exchange) --}}
        <div>
            <label class="block text-xs font-extrabold uppercase tracking-wider text-brand-dark mb-3 flex items-center gap-1.5">
                <span>1. Choose Action Type</span>
                <span class="text-rose-500">*</span>
            </label>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                {{-- Return & Refund Card --}}
                <label class="relative flex items-start gap-3.5 p-4 sm:p-5 rounded-2xl border-2 cursor-pointer transition-all duration-200"
                       :class="requestType === 'return' ? 'border-brand-dark bg-brand-light/50 shadow-xs' : 'border-brand-border bg-white hover:border-brand-muted'">
                    <input type="radio" name="type" value="return" x-model="requestType" class="mt-1 text-brand-dark focus:ring-brand-dark cursor-pointer">
                    <div>
                        <span class="block text-sm font-heading font-extrabold text-brand-dark">↩ Return & Full Refund</span>
                        <span class="block text-xs text-brand-muted mt-1 leading-relaxed">
                            Return the item(s) to our warehouse and receive 100% refund back to your original payment method or UPI.
                        </span>
                    </div>
                </label>

                {{-- Size / Variant Exchange Card --}}
                <label class="relative flex items-start gap-3.5 p-4 sm:p-5 rounded-2xl border-2 cursor-pointer transition-all duration-200"
                       :class="requestType === 'exchange' ? 'border-brand-dark bg-brand-light/50 shadow-xs' : 'border-brand-border bg-white hover:border-brand-muted'">
                    <input type="radio" name="type" value="exchange" x-model="requestType" class="mt-1 text-brand-dark focus:ring-brand-dark cursor-pointer">
                    <div>
                        <span class="block text-sm font-heading font-extrabold text-brand-dark">🔄 Size / Product Exchange</span>
                        <span class="block text-xs text-brand-muted mt-1 leading-relaxed">
                            Swap for a different size or fit with zero extra shipping charges. Fast doorstep exchange available.
                        </span>
                    </div>
                </label>
            </div>
        </div>

        {{-- 2. Select Items to Return / Exchange --}}
        <div>
            <label class="block text-xs font-extrabold uppercase tracking-wider text-brand-dark mb-3 flex items-center justify-between">
                <span class="flex items-center gap-1.5">
                    <span>2. Select Items to Return / Exchange</span>
                    <span class="text-rose-500">*</span>
                </span>
                <span class="text-xs font-normal text-brand-muted">Select at least one item</span>
            </label>

            <div class="border border-brand-border rounded-2xl divide-y divide-brand-border overflow-hidden">
                @foreach($order->items as $item)
                    @php
                        $product = $item->variant?->product;
                        $primaryImg = $product?->primaryImage ?? $product?->images->first();
                        $imgUrl = $primaryImg?->url;
                    @endphp
                    <div class="p-4 sm:p-5 bg-white hover:bg-brand-off-white/40 transition-colors flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4"
                         :class="selectedItems.includes({{ $item->id }}) ? 'bg-brand-light/30' : ''">
                        
                        <div class="flex items-start gap-3.5 flex-1">
                            {{-- Checkbox --}}
                            <input type="checkbox" 
                                   name="selected_items[]" 
                                   value="{{ $item->id }}" 
                                   :checked="selectedItems.includes({{ $item->id }})"
                                   @change="toggleItem({{ $item->id }})"
                                   class="mt-1.5 rounded text-brand-dark focus:ring-brand-dark cursor-pointer h-4 w-4">

                            {{-- Product Thumb --}}
                            <div class="w-14 h-16 sm:w-16 sm:h-20 rounded-xl border border-brand-border bg-brand-off-white overflow-hidden shrink-0">
                                @if($imgUrl)
                                    <img src="{{ $imgUrl }}" alt="{{ $product->name ?? 'Drop' }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center font-bold text-slate-400 text-xs">TX</div>
                                @endif
                            </div>

                            {{-- Product Info --}}
                            <div>
                                <h4 class="text-xs sm:text-sm font-bold text-brand-dark">
                                    {{ $product->name ?? 'Streetwear Drop' }}
                                </h4>
                                <div class="flex flex-wrap items-center gap-1.5 mt-1 text-[11px] text-brand-muted">
                                    @if($item->variant?->size)
                                        <span class="bg-brand-off-white border border-brand-border px-2 py-0.5 rounded-md font-semibold text-brand-dark">
                                            Size: {{ $item->variant->size }}
                                        </span>
                                    @endif
                                    @if($item->variant?->color)
                                        <span class="bg-brand-off-white border border-brand-border px-2 py-0.5 rounded-md font-semibold text-brand-dark">
                                            Color: {{ $item->variant->color }}
                                        </span>
                                    @endif
                                    <span>• Qty: {{ $item->quantity }}</span>
                                </div>
                                <p class="text-xs font-extrabold text-brand-dark mt-1">₹{{ number_format($item->price, 2) }} each</p>
                            </div>
                        </div>

                        {{-- Item Controls (Qty & Size Selector for Exchange) --}}
                        <div class="w-full sm:w-auto flex flex-wrap items-center gap-3 pl-8 sm:pl-0" x-show="selectedItems.includes({{ $item->id }})">
                            {{-- Qty Selector if qty > 1 --}}
                            @if($item->quantity > 1)
                                <div>
                                    <label class="block text-[10px] font-bold text-brand-muted uppercase mb-1">Return Qty</label>
                                    <select name="quantities[{{ $item->id }}]" class="text-xs font-semibold py-1.5 px-2.5 bg-brand-off-white border border-brand-border rounded-lg outline-none focus:border-brand-dark">
                                        @for($q = 1; $q <= $item->quantity; $q++)
                                            <option value="{{ $q }}">{{ $q }}</option>
                                        @endfor
                                    </select>
                                </div>
                            @else
                                <input type="hidden" name="quantities[{{ $item->id }}]" value="1">
                            @endif

                            {{-- Replacement Size Selector (Shown when Exchange is active) --}}
                            <div x-show="requestType === 'exchange'">
                                <label class="block text-[10px] font-bold text-brand-muted uppercase mb-1">New Size Wanted <span class="text-rose-500">*</span></label>
                                @if(isset($item->available_variants) && $item->available_variants->count() > 0)
                                    <select name="exchange_variants[{{ $item->id }}]" class="text-xs font-bold py-1.5 px-3 bg-brand-off-white border border-brand-border rounded-lg outline-none focus:border-brand-dark text-brand-dark">
                                        <option value="">Select Replacement Size</option>
                                        @foreach($item->available_variants as $availVar)
                                            <option value="{{ $availVar->id }}">
                                                {{ $availVar->size ?? 'Standard' }} @if($availVar->color) ({{ $availVar->color }}) @endif — In Stock
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <span class="text-[11px] text-amber-700 bg-amber-50 px-2.5 py-1 rounded-md border border-amber-200">
                                        No alternative sizes available. (Choose Return & Refund)
                                    </span>
                                @endif
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>

        {{-- 3. Return Reason & Notes --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            {{-- Reason Dropdown --}}
            <div>
                <label for="reason" class="block text-xs font-extrabold uppercase tracking-wider text-brand-dark mb-1.5">
                    3. Reason for Return / Exchange <span class="text-rose-500">*</span>
                </label>
                <select name="reason" id="reason" required class="w-full py-2.5 px-3.5 bg-brand-off-white border border-brand-border rounded-xl text-xs font-semibold text-brand-dark outline-none focus:border-brand-dark">
                    <option value="">Select a reason...</option>
                    <option value="size_too_small">Size is too small / tight fit</option>
                    <option value="size_too_large">Size is too large / loose fit</option>
                    <option value="defective_product">Damaged / Defective item received</option>
                    <option value="wrong_item_received">Received wrong item / wrong color</option>
                    <option value="quality_not_expected">Fabric / Quality not as expected</option>
                    <option value="color_mismatch">Color looks different from website</option>
                    <option value="changed_mind">Changed mind / No longer needed</option>
                    <option value="other">Other / Custom reason</option>
                </select>
            </div>

            {{-- Photo Proof Upload --}}
            <div>
                <label for="images" class="block text-xs font-extrabold uppercase tracking-wider text-brand-dark mb-1.5 flex items-center justify-between">
                    <span>Upload Product Photos (Optional)</span>
                    <span class="text-[10px] text-brand-muted font-normal">Max 5 photos (JPEG, PNG, WEBP)</span>
                </label>
                <input type="file" 
                       name="images[]" 
                       id="images" 
                       multiple 
                       accept="image/*" 
                       class="w-full py-2 px-3 bg-brand-off-white border border-brand-border rounded-xl text-xs text-brand-dark file:mr-3 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-brand-dark file:text-white hover:file:bg-brand-text cursor-pointer">
                <p class="text-[10px] text-brand-muted mt-1">Please include photos of intact tags and packaging for faster approval.</p>
            </div>

            {{-- Customer Notes --}}
            <div class="md:col-span-2">
                <label for="customer_notes" class="block text-xs font-extrabold uppercase tracking-wider text-brand-dark mb-1.5">
                    Additional Comments / Feedback (Optional)
                </label>
                <textarea name="customer_notes" 
                          id="customer_notes" 
                          rows="3" 
                          placeholder="Tell us more about the fit issue or reason for exchange..." 
                          class="w-full p-3 bg-brand-off-white border border-brand-border rounded-xl text-xs font-normal text-brand-dark outline-none focus:border-brand-dark"></textarea>
            </div>
        </div>

        {{-- 4. Refund Payout Destination (Shown for Returns on COD Orders) --}}
        @if($order->payment_method === 'cod')
            <div x-show="requestType === 'return'" class="p-5 bg-brand-off-white/70 border border-brand-border rounded-2xl space-y-4">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-brand-dark text-white flex items-center justify-center text-xs font-bold">
                        ₹
                    </div>
                    <div>
                        <h4 class="text-xs font-extrabold text-brand-dark uppercase tracking-wider">COD Refund Payout Details</h4>
                        <p class="text-[11px] text-brand-muted">Since this was a Cash on Delivery order, choose where we should transfer your refund.</p>
                    </div>
                </div>

                {{-- Mode Tabs (UPI vs Bank) --}}
                <div class="flex items-center gap-3">
                    <label class="flex items-center gap-2 text-xs font-bold text-brand-dark cursor-pointer">
                        <input type="radio" name="refund_mode" value="upi" x-model="refundMode" class="text-brand-dark focus:ring-brand-dark">
                        <span>Instant UPI Transfer (Recommended)</span>
                    </label>
                    <label class="flex items-center gap-2 text-xs font-bold text-brand-dark cursor-pointer">
                        <input type="radio" name="refund_mode" value="bank_transfer" x-model="refundMode" class="text-brand-dark focus:ring-brand-dark">
                        <span>Direct Bank Account Transfer (NEFT/IMPS)</span>
                    </label>
                </div>

                {{-- UPI Field --}}
                <div x-show="refundMode === 'upi'" class="space-y-1">
                    <label for="upi_id" class="block text-[11px] font-bold text-brand-dark uppercase tracking-wider">
                        Enter UPI ID / VPA <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="upi_id" 
                           id="upi_id" 
                           placeholder="username@okhdfcbank / yourname@paytm" 
                           class="w-full sm:w-96 py-2.5 px-3 bg-white border border-brand-border rounded-xl text-xs font-mono font-bold text-brand-dark outline-none focus:border-brand-dark">
                    <p class="text-[10px] text-brand-muted">Refund will be transferred instantly once QC inspection is completed.</p>
                </div>

                {{-- Bank Details Fields --}}
                <div x-show="refundMode === 'bank_transfer'" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="bank_beneficiary_name" class="block text-[11px] font-bold text-brand-dark uppercase tracking-wider mb-1">
                            Account Holder Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="bank_beneficiary_name" id="bank_beneficiary_name" placeholder="Full name on bank account" class="w-full py-2 px-3 bg-white border border-brand-border rounded-xl text-xs text-brand-dark outline-none focus:border-brand-dark">
                    </div>
                    <div>
                        <label for="bank_account_number" class="block text-[11px] font-bold text-brand-dark uppercase tracking-wider mb-1">
                            Bank Account Number <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="bank_account_number" id="bank_account_number" placeholder="Account number" class="w-full py-2 px-3 bg-white border border-brand-border rounded-xl text-xs font-mono text-brand-dark outline-none focus:border-brand-dark">
                    </div>
                    <div>
                        <label for="bank_ifsc" class="block text-[11px] font-bold text-brand-dark uppercase tracking-wider mb-1">
                            IFSC Code <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="bank_ifsc" id="bank_ifsc" placeholder="e.g. HDFC0001234" class="w-full py-2 px-3 bg-white border border-brand-border rounded-xl text-xs font-mono uppercase text-brand-dark outline-none focus:border-brand-dark">
                    </div>
                    <div>
                        <label for="bank_name" class="block text-[11px] font-bold text-brand-dark uppercase tracking-wider mb-1">
                            Bank Name (Optional)
                        </label>
                        <input type="text" name="bank_name" id="bank_name" placeholder="e.g. HDFC Bank, ICICI Bank" class="w-full py-2 px-3 bg-white border border-brand-border rounded-xl text-xs text-brand-dark outline-none focus:border-brand-dark">
                    </div>
                </div>
            </div>
        @else
            {{-- Online Payment Notification --}}
            <div x-show="requestType === 'return'" class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 text-xs text-emerald-800 font-medium">
                <span class="text-lg">💳</span>
                <span>This order was paid online. The refund will be credited directly back to your original payment method (Card/UPI/NetBanking) within 3-5 business days of QC pass.</span>
            </div>
        @endif

        {{-- Pickup Address Confirmation --}}
        <div class="p-4 sm:p-5 bg-brand-off-white/50 border border-brand-border rounded-2xl">
            <h4 class="text-xs font-extrabold text-brand-dark uppercase tracking-wider mb-2 flex items-center gap-1.5">
                <span>📍 Doorstep Pickup Address</span>
            </h4>
            <p class="text-xs text-brand-dark font-semibold">
                {{ $order->address->name ?? auth()->user()->name }} • Phone: {{ $order->address->phone ?? auth()->user()->phone }}
            </p>
            <p class="text-xs text-brand-muted mt-0.5">
                {{ $order->address->line1 ?? '' }}, {{ $order->address->city ?? '' }}, {{ $order->address->state ?? '' }} - {{ $order->address->pincode ?? '' }}
            </p>
        </div>

        {{-- Terms & Submit --}}
        <div class="border-t border-brand-border pt-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <label class="flex items-center gap-2 text-xs text-brand-muted cursor-pointer">
                <input type="checkbox" required checked class="rounded text-brand-dark focus:ring-brand-dark">
                <span>I confirm that items are unused, unwashed, and in original packaging with tags intact.</span>
            </label>

            <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-brand-dark text-white text-xs font-extrabold uppercase tracking-wider rounded-xl hover:bg-brand-text transition-all shadow-md active:scale-95 cursor-pointer">
                Submit Request & Schedule Pickup ➔
            </button>
        </div>

    </form>
</div>
@endsection
