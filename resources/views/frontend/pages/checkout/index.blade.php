@extends('frontend.layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="bg-[#f6f7f9] min-h-screen py-4 sm:py-8 font-sans selection:bg-brand-dark selection:text-white pb-28 lg:pb-12">
    <div class="max-w-[1280px] mx-auto px-3 sm:px-6 lg:px-8">
        
        {{-- Top Bar --}}
        <div class="bg-white rounded-2xl shadow-xs border border-brand-border px-4 py-3.5 sm:px-6 sm:py-4 mb-4 sm:mb-6 flex items-center justify-between gap-3">
            <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                <a href="{{ route('frontend.products.index') }}" class="text-xs font-bold uppercase tracking-wider text-brand-muted hover:text-brand-dark transition-colors flex items-center gap-1 shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                    <span class="hidden sm:inline">Back to Shop</span>
                </a>
                <span class="text-brand-border">|</span>
                <h1 class="text-sm sm:text-base font-heading font-extrabold uppercase tracking-tight text-brand-dark truncate">Checkout</h1>
            </div>
            <div class="flex items-center gap-1.5 text-[11px] sm:text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 sm:px-3 py-1 rounded-full border border-emerald-200 shrink-0">
                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span class="hidden xs:inline">100% SECURE</span>
                <span class="xs:hidden">SECURE</span>
            </div>
        </div>
        
        @if($cart->items->count() > 0)
            <div class="flex flex-col lg:flex-row gap-5" x-data="checkoutForm()">
                
                {{-- LEFT COLUMN: Multi-Step Cards --}}
                <div class="flex-1 space-y-4">
                    
                    {{-- STEP 1: LOGIN STATUS --}}
                    <div class="bg-white rounded-2xl shadow-xs border border-brand-border overflow-hidden">
                        <div class="px-4 py-3.5 sm:px-6 sm:py-4 bg-brand-off-white flex items-center justify-between border-b border-brand-border">
                            <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
                                <span class="w-6 h-6 rounded-lg bg-brand-dark text-white font-extrabold text-xs flex items-center justify-center shrink-0">1</span>
                                <div class="flex flex-wrap items-center gap-x-2 gap-y-0.5 min-w-0">
                                    <span class="font-bold text-xs sm:text-sm text-brand-dark uppercase tracking-wider">LOGIN</span>
                                    <span class="text-xs font-semibold text-brand-dark flex items-center gap-1 truncate">
                                        <span class="text-emerald-600 font-bold">✓</span> {{ Auth::user()->name ?? 'Guest User' }}
                                        <span class="text-brand-muted font-normal text-[11px]">({{ Auth::user()->phone ?? Auth::user()->email ?? '' }})</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                  {{-- STEP 2: DELIVERY ADDRESS --}}
                <div id="delivery-address-step" class="bg-white rounded-2xl shadow-xs border border-brand-border overflow-hidden scroll-mt-24">
                    <div class="px-4 py-3.5 sm:px-6 sm:py-4 bg-brand-off-white flex items-center justify-between border-b border-brand-border">
                        <div class="flex items-center gap-2.5 sm:gap-3">
                            <span class="w-6 h-6 rounded-lg bg-brand-dark text-white font-extrabold text-xs flex items-center justify-center shrink-0">2</span>
                            <h2 class="font-heading font-bold text-xs sm:text-sm text-brand-dark uppercase tracking-wider">DELIVERY ADDRESS</h2>
                        </div>
                        @if($addresses->count() > 0)
                            <button @click="showNewAddress = !showNewAddress; if(showNewAddress) { selectedAddressId = null; } else { selectedAddressId = {{ $addresses->first()->id }}; errors = {}; }" 
                                    class="text-xs font-bold uppercase tracking-wider text-brand-dark hover:underline transition-all cursor-pointer">
                                <span x-text="showNewAddress ? 'Select Saved Address' : '+ Add New Address'"></span>
                            </button>
                        @endif
                    </div>

                    <div class="p-4 sm:p-6">
                        @if($addresses->count() > 0)
                            <div x-show="!showNewAddress" class="space-y-3">
                                <div class="grid grid-cols-1 gap-3">
                                    @foreach($addresses as $addr)
                                        <div @click="selectedAddressId = {{ $addr->id }}; errors = {};"
                                             class="border-2 rounded-xl p-3.5 sm:p-4 cursor-pointer transition-all duration-200 flex items-start gap-3.5 relative"
                                             :class="selectedAddressId == {{ $addr->id }} ? 'border-brand-dark bg-brand-light/30 shadow-xs' : 'border-brand-border hover:border-brand-muted/70 bg-white'">
                                            
                                            <input type="radio" x-model="selectedAddressId" value="{{ $addr->id }}" class="mt-1 text-brand-dark focus:ring-brand-dark h-4 w-4">

                                            <div class="flex-1 text-xs min-w-0">
                                                <div class="flex flex-wrap items-center justify-between gap-1.5 mb-1.5">
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-bold text-xs sm:text-sm text-brand-dark capitalize">{{ $addr->name }}</span>
                                                        <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-md bg-brand-off-white border border-brand-border text-brand-dark">{{ $addr->type }}</span>
                                                    </div>
                                                    <span class="font-semibold text-brand-dark text-xs">📞 {{ $addr->phone }}</span>
                                                </div>
                                                <p class="text-brand-muted leading-relaxed text-xs">
                                                    {{ $addr->line1 }}@if($addr->line2), {{ $addr->line2 }}@endif, {{ $addr->city }}, {{ $addr->state }} - <span class="font-bold text-brand-dark">{{ $addr->pincode }}</span>
                                                    @if($addr->landmark) <span class="text-brand-muted/80 block mt-0.5">(Landmark: {{ $addr->landmark }})</span> @endif
                                                </p>

                                                <div x-show="selectedAddressId == {{ $addr->id }}" class="mt-2.5 pt-2.5 border-t border-brand-border/60 flex items-center justify-between">
                                                    <span class="text-[11px] font-bold text-emerald-700 flex items-center gap-1">✓ Deliver to this address</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div x-init="showNewAddress = true"></div>
                        @endif

                        {{-- New Address Form with Inline Validation --}}
                        <div x-show="showNewAddress || '{{ $addresses->count() }}' == '0'" x-cloak class="space-y-3.5 pt-1">
                            <h3 class="text-xs font-bold text-brand-dark uppercase tracking-wider mb-2">ADD A NEW ADDRESS</h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider mb-1" :class="errors.name ? 'text-red-600' : 'text-brand-dark'">Name <span class="text-red-500">*</span></label>
                                    <input type="text" x-model="form.name" @input="delete errors.name" 
                                           class="w-full rounded-xl px-3.5 py-2.5 text-xs text-brand-dark outline-none transition-all border"
                                           :class="errors.name ? 'border-red-400 bg-red-50/25 ring-1 ring-red-400' : 'border-brand-border bg-white focus:ring-1 focus:ring-brand-dark focus:border-brand-dark'" 
                                           placeholder="Full Name">
                                    <p x-show="errors.name" x-text="errors.name" class="text-[11px] text-red-500 font-semibold mt-1" x-cloak></p>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider mb-1" :class="errors.phone ? 'text-red-600' : 'text-brand-dark'">10-digit Mobile Number <span class="text-red-500">*</span></label>
                                    <input type="tel" maxlength="10" x-model="form.phone" @input="delete errors.phone" 
                                           class="w-full rounded-xl px-3.5 py-2.5 text-xs text-brand-dark outline-none transition-all border"
                                           :class="errors.phone ? 'border-red-400 bg-red-50/25 ring-1 ring-red-400' : 'border-brand-border bg-white focus:ring-1 focus:ring-brand-dark focus:border-brand-dark'" 
                                           placeholder="Mobile Number">
                                    <p x-show="errors.phone" x-text="errors.phone" class="text-[11px] text-red-500 font-semibold mt-1" x-cloak></p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider mb-1" :class="errors.line1 ? 'text-red-600' : 'text-brand-dark'">Flat, House No., Building <span class="text-red-500">*</span></label>
                                    <input type="text" x-model="form.line1" @input="delete errors.line1" 
                                           class="w-full rounded-xl px-3.5 py-2.5 text-xs text-brand-dark outline-none transition-all border"
                                           :class="errors.line1 ? 'border-red-400 bg-red-50/25 ring-1 ring-red-400' : 'border-brand-border bg-white focus:ring-1 focus:ring-brand-dark focus:border-brand-dark'" 
                                           placeholder="House/Flat No., Building Name">
                                    <p x-show="errors.line1" x-text="errors.line1" class="text-[11px] text-red-500 font-semibold mt-1" x-cloak></p>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider mb-1" :class="errors.line2 ? 'text-red-600' : 'text-brand-dark'">Street, Area, Sector <span class="text-red-500">*</span></label>
                                    <input type="text" x-model="form.line2" @input="delete errors.line2" 
                                           class="w-full rounded-xl px-3.5 py-2.5 text-xs text-brand-dark outline-none transition-all border"
                                           :class="errors.line2 ? 'border-red-400 bg-red-50/25 ring-1 ring-red-400' : 'border-brand-border bg-white focus:ring-1 focus:ring-brand-dark focus:border-brand-dark'" 
                                           placeholder="Area / Street Name">
                                    <p x-show="errors.line2" x-text="errors.line2" class="text-[11px] text-red-500 font-semibold mt-1" x-cloak></p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider mb-1" :class="errors.city ? 'text-red-600' : 'text-brand-dark'">City <span class="text-red-500">*</span></label>
                                    <input type="text" x-model="form.city" @input="delete errors.city" 
                                           class="w-full rounded-xl px-3.5 py-2.5 text-xs text-brand-dark outline-none transition-all border"
                                           :class="errors.city ? 'border-red-400 bg-red-50/25 ring-1 ring-red-400' : 'border-brand-border bg-white focus:ring-1 focus:ring-brand-dark focus:border-brand-dark'" 
                                           placeholder="City / Town">
                                    <p x-show="errors.city" x-text="errors.city" class="text-[11px] text-red-500 font-semibold mt-1" x-cloak></p>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider mb-1" :class="errors.state ? 'text-red-600' : 'text-brand-dark'">State <span class="text-red-500">*</span></label>
                                    <select x-model="form.state" @change="delete errors.state" 
                                            class="w-full rounded-xl px-3.5 py-2.5 text-xs text-brand-dark outline-none transition-all border"
                                            :class="errors.state ? 'border-red-400 bg-red-50/25 ring-1 ring-red-400' : 'border-brand-border bg-white focus:ring-1 focus:ring-brand-dark focus:border-brand-dark'">
                                        <option value="">Select State</option>
                                        @php
                                            $states = ['Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh', 'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jharkhand', 'Karnataka', 'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur', 'Meghalaya', 'Mizoram', 'Nagaland', 'Odisha', 'Punjab', 'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Telangana', 'Tripura', 'Uttar Pradesh', 'Uttarakhand', 'West Bengal', 'Delhi'];
                                        @endphp
                                        @foreach($states as $st)
                                            <option value="{{ $st }}">{{ $st }}</option>
                                        @endforeach
                                    </select>
                                    <p x-show="errors.state" x-text="errors.state" class="text-[11px] text-red-500 font-semibold mt-1" x-cloak></p>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider mb-1" :class="errors.pincode ? 'text-red-600' : 'text-brand-dark'">Pincode <span class="text-red-500">*</span></label>
                                    <input type="text" x-model="form.pincode" maxlength="6" @input="delete errors.pincode" 
                                           class="w-full rounded-xl px-3.5 py-2.5 text-xs text-brand-dark outline-none transition-all border"
                                           :class="errors.pincode ? 'border-red-400 bg-red-50/25 ring-1 ring-red-400' : 'border-brand-border bg-white focus:ring-1 focus:ring-brand-dark focus:border-brand-dark'" 
                                           placeholder="6-digit Pincode">
                                    <p x-show="errors.pincode" x-text="errors.pincode" class="text-[11px] text-red-500 font-semibold mt-1" x-cloak></p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                <div>
                                    <label class="block text-[11px] font-bold text-brand-dark uppercase tracking-wider mb-1">Landmark <span class="font-normal text-brand-muted normal-case">(Optional)</span></label>
                                    <input type="text" x-model="form.landmark" class="w-full bg-white border border-brand-border rounded-xl px-3.5 py-2.5 text-xs text-brand-dark focus:ring-1 focus:ring-brand-dark focus:border-brand-dark outline-none transition-all" placeholder="Nearby Landmark">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-brand-dark uppercase tracking-wider mb-1">Alternate Phone <span class="font-normal text-brand-muted normal-case">(Optional)</span></label>
                                    <input type="text" x-model="form.alternate_phone" class="w-full bg-white border border-brand-border rounded-xl px-3.5 py-2.5 text-xs text-brand-dark focus:ring-1 focus:ring-brand-dark focus:border-brand-dark outline-none transition-all" placeholder="Alternate Phone">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                    {{-- STEP 3: ORDER ITEMS --}}
                    <div class="bg-white rounded-2xl shadow-xs border border-brand-border overflow-hidden">
                        <div class="px-4 py-3.5 sm:px-6 sm:py-4 bg-brand-off-white flex items-center justify-between border-b border-brand-border">
                            <div class="flex items-center gap-2.5 sm:gap-3">
                                <span class="w-6 h-6 rounded-lg bg-brand-dark text-white font-extrabold text-xs flex items-center justify-center shrink-0">3</span>
                                <h2 class="font-heading font-bold text-xs sm:text-sm text-brand-dark uppercase tracking-wider">ORDER SUMMARY ({{ $cart->items->sum('quantity') }} ITEMS)</h2>
                            </div>
                        </div>

                        <div class="divide-y divide-brand-border/60">
                            @foreach($cart->items as $item)
                                <div class="p-3.5 sm:p-5 flex gap-3.5 sm:gap-5 items-start">
                                    <div class="w-20 h-24 sm:w-22 sm:h-28 bg-brand-off-white border border-brand-border rounded-xl overflow-hidden shrink-0 shadow-2xs">
                                        @if($item->variant->product->primaryImage)
                                            <img src="{{ $item->variant->product->primaryImage->url }}" alt="{{ $item->variant->product->name }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-bold text-xs sm:text-sm text-brand-dark line-clamp-2 leading-snug">{{ $item->variant->product->name }}</h3>
                                        
                                        {{-- Attributes --}}
                                        <div class="flex flex-wrap items-center gap-1.5 mt-1">
                                            @if($item->variant->size)
                                                <span class="inline-flex items-center gap-1 bg-brand-off-white border border-brand-border px-2 py-0.5 rounded-md text-[10px] font-semibold text-brand-dark">
                                                    Size: <strong>{{ $item->variant->size }}</strong>
                                                </span>
                                            @endif
                                            @if($item->variant->color)
                                                <span class="inline-flex items-center gap-1 bg-brand-off-white border border-brand-border px-2 py-0.5 rounded-md text-[10px] font-semibold text-brand-dark">
                                                    Color: <strong>{{ $item->variant->color }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        
                                        {{-- Price --}}
                                        <div class="flex items-center gap-2.5 mt-2">
                                            <span class="text-xs sm:text-sm font-extrabold text-brand-dark">₹{{ number_format(($item->variant->price ?? $item->variant->product->price) * $item->quantity) }}</span>
                                            @if($item->variant->product->compare_price > $item->variant->product->price)
                                                <span class="text-[11px] text-brand-muted line-through">₹{{ number_format($item->variant->product->compare_price * $item->quantity) }}</span>
                                                <span class="text-[10px] font-extrabold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100">{{ $item->variant->product->discount_percent }}% OFF</span>
                                            @endif
                                        </div>

                                        {{-- Bottom Actions --}}
                                        <div class="flex flex-wrap items-center justify-between gap-2 mt-3 pt-2 border-t border-brand-border/40">
                                            {{-- Quantity Counter Widget --}}
                                            <div class="flex items-center bg-brand-off-white border border-brand-border rounded-lg h-7 sm:h-8 shadow-2xs">
                                                <form action="{{ route('frontend.cart.update') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                                                    <input type="hidden" name="quantity" value="{{ $item->quantity - 1 }}">
                                                    <button type="submit" 
                                                            class="w-6 sm:w-7 h-full flex items-center justify-center text-brand-dark hover:bg-white active:scale-95 disabled:opacity-40 transition-all font-bold text-sm cursor-pointer"
                                                            {{ $item->quantity <= 1 ? 'disabled' : '' }} aria-label="Decrease quantity">
                                                        &minus;
                                                    </button>
                                                </form>

                                                <span class="w-6 sm:w-7 text-center font-bold text-xs text-brand-dark select-none">{{ $item->quantity }}</span>

                                                <form action="{{ route('frontend.cart.update') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                                                    <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
                                                    <button type="submit" 
                                                            class="w-6 sm:w-7 h-full flex items-center justify-center text-brand-dark hover:bg-white active:scale-95 disabled:opacity-40 transition-all font-bold text-sm cursor-pointer"
                                                            {{ $item->quantity >= ($item->variant->stock ?? 99) ? 'disabled' : '' }} aria-label="Increase quantity">
                                                        &plus;
                                                    </button>
                                                </form>
                                            </div>

                                            @if(($item->variant->stock ?? 99) <= 5)
                                                <span class="text-[10px] font-bold text-amber-600">Only {{ $item->variant->stock }} left!</span>
                                            @endif

                                            <form action="{{ route('frontend.cart.moveToWishlist', $item->id) }}" method="POST" class="ml-auto">
                                                @csrf
                                                <button type="submit" class="text-[11px] font-bold uppercase tracking-wider text-brand-muted hover:text-brand-dark transition-colors cursor-pointer">
                                                    Move to Wishlist
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- STEP 4: PAYMENT OPTIONS --}}
                    <div class="bg-white rounded-2xl shadow-xs border border-brand-border overflow-hidden">
                        <div class="px-4 py-3.5 sm:px-6 sm:py-4 bg-brand-off-white flex items-center justify-between border-b border-brand-border">
                            <div class="flex items-center gap-2.5 sm:gap-3">
                                <span class="w-6 h-6 rounded-lg bg-brand-dark text-white font-extrabold text-xs flex items-center justify-center shrink-0">4</span>
                                <h2 class="font-heading font-bold text-xs sm:text-sm text-brand-dark uppercase tracking-wider">PAYMENT OPTIONS</h2>
                            </div>
                        </div>

                        <div class="p-4 sm:p-6 space-y-3">
                            {{-- Online Payment --}}
                            <div @click="form.payment_method = 'razorpay'" 
                                 class="border-2 rounded-xl p-3.5 sm:p-4 cursor-pointer transition-all duration-200 flex items-center justify-between gap-3"
                                 :class="form.payment_method === 'razorpay' ? 'border-brand-dark bg-brand-light/30 shadow-xs' : 'border-brand-border hover:border-brand-muted/70 bg-white'">
                                <div class="flex items-center gap-3 min-w-0">
                                    <input type="radio" x-model="form.payment_method" value="razorpay" class="text-brand-dark focus:ring-brand-dark h-4 w-4 shrink-0">
                                    <div>
                                        <span class="font-bold text-xs sm:text-sm text-brand-dark block">UPI / Cards / NetBanking (Razorpay)</span>
                                        <span class="text-[11px] text-brand-muted">Google Pay, PhonePe, Paytm, All Cards & NetBanking</span>
                                    </div>
                                </div>
                                <span class="text-base sm:text-lg shrink-0">⚡</span>
                            </div>

                            {{-- COD --}}
                            <div @click="form.payment_method = 'cod'" 
                                 class="border-2 rounded-xl p-3.5 sm:p-4 cursor-pointer transition-all duration-200 flex items-center justify-between gap-3"
                                 :class="form.payment_method === 'cod' ? 'border-brand-dark bg-brand-light/30 shadow-xs' : 'border-brand-border hover:border-brand-muted/70 bg-white'">
                                <div class="flex items-center gap-3 min-w-0">
                                    <input type="radio" x-model="form.payment_method" value="cod" class="text-brand-dark focus:ring-brand-dark h-4 w-4 shrink-0">
                                    <div>
                                        <span class="font-bold text-xs sm:text-sm text-brand-dark block">Cash on Delivery (COD)</span>
                                        <span class="text-[11px] text-brand-muted">Pay cash at your doorstep upon delivery</span>
                                    </div>
                                </div>
                                <span class="text-base sm:text-lg shrink-0">💵</span>
                            </div>
                        </div>
                    </div>

                    {{-- WISHLIST QUICK MOVE --}}
                    @if($wishlistItems->count() > 0)
                    <div class="bg-white rounded-2xl shadow-xs border border-brand-border p-4 sm:p-5 mt-4">
                        <h3 class="text-xs font-bold text-brand-dark uppercase tracking-wider mb-3 flex items-center gap-2">
                            <span>❤️ ITEMS IN YOUR WISHLIST</span>
                            <span class="bg-brand-off-white border border-brand-border text-brand-dark px-2 py-0.5 rounded-md text-[10px] font-bold">{{ $wishlistItems->count() }}</span>
                        </h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            @foreach($wishlistItems as $wItem)
                                @php
                                    $wProd = $wItem->product;
                                    if (!$wProd) continue;
                                    $wImg = $wProd->primaryImage ?? $wProd->images->first();
                                @endphp
                                <div class="border border-brand-border rounded-xl p-2.5 flex items-center justify-between gap-2 bg-brand-off-white/40">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="w-10 h-12 bg-white border border-brand-border rounded-lg overflow-hidden shrink-0">
                                            @if($wImg)
                                                <img src="{{ $wImg->url }}" class="w-full h-full object-cover">
                                            @endif
                                        </div>
                                        <div class="min-w-0 text-xs">
                                            <h4 class="font-bold text-brand-dark truncate">{{ $wProd->name }}</h4>
                                            <p class="font-extrabold text-brand-dark">₹{{ number_format((float) ($wProd->price ?? 0)) }}</p>
                                        </div>
                                    </div>
                                    <form action="{{ route('frontend.cart.moveFromWishlist', $wItem->id) }}" method="POST" class="shrink-0">
                                        @csrf
                                        <button type="submit" class="text-[10px] font-bold uppercase tracking-wider bg-brand-dark text-white px-2.5 py-1.5 rounded-lg hover:bg-brand-text transition-colors cursor-pointer">
                                            + Add
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>

                {{-- RIGHT COLUMN: Price Details & Confirm CTA --}}
                <div class="lg:w-[380px] shrink-0">
                    <div class="bg-white rounded-2xl shadow-xs border border-brand-border lg:sticky lg:top-24 overflow-hidden">
                        
                        <h2 class="text-xs font-heading font-extrabold text-brand-dark uppercase tracking-wider px-5 py-3.5 sm:px-6 sm:py-4 border-b border-brand-border bg-brand-off-white">
                            PRICE DETAILS
                        </h2>

                        <div class="p-4 sm:p-6 space-y-3.5 text-xs">
                            {{-- Item Price --}}
                            <div class="flex justify-between text-brand-muted">
                                <span>Price ({{ $cart->items->sum('quantity') }} {{ Str::plural('item', $cart->items->sum('quantity')) }})</span>
                                <span class="font-bold text-brand-dark">₹{{ number_format($summary['subtotal'], 2) }}</span>
                            </div>

                            {{-- Discount --}}
                            <div x-show="appliedDiscount > 0" class="flex justify-between text-emerald-600 font-bold" x-cloak>
                                <span>Coupon Discount</span>
                                <span>- ₹<span x-text="appliedDiscount.toFixed(2)"></span></span>
                            </div>

                            {{-- Delivery Charges --}}
                            <div class="flex justify-between items-center text-brand-muted">
                                <div class="flex items-center gap-1.5">
                                    <span>Delivery Charges</span>
                                    @if(($summary['shipping'] ?? 0) > 0)
                                        <span class="text-[10px] text-brand-dark font-bold bg-brand-off-white border border-brand-border px-1.5 py-0.5 rounded">Standard</span>
                                    @endif
                                </div>
                                @if(($summary['shipping'] ?? 0) > 0)
                                    <div class="text-right">
                                        <span class="font-bold text-brand-dark">₹{{ number_format($summary['shipping'], 2) }}</span>
                                    </div>
                                @else
                                    <span class="text-emerald-700 font-extrabold uppercase text-[11px] bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">FREE</span>
                                @endif
                            </div>

                            {{-- Free Delivery Threshold Prompt (if below ₹999) --}}
                            @if(($summary['subtotal'] ?? 0) < 999)
                                <div class="bg-amber-50/90 border border-amber-200/90 rounded-xl p-2.5 text-[11px] text-amber-900 flex items-center justify-between gap-2">
                                    <span class="leading-tight">🚚 Add <strong>₹{{ number_format(999 - $summary['subtotal']) }}</strong> more for <strong>FREE Delivery</strong></span>
                                    <a href="{{ route('frontend.products.index') }}" class="font-extrabold text-[10px] uppercase text-brand-dark bg-white border border-amber-300 px-2 py-1 rounded-lg hover:bg-amber-100 transition-colors shrink-0">+ Add More</a>
                                </div>
                            @endif

                            {{-- Coupon Input --}}
                            <div class="pt-3 border-t border-brand-border/60 space-y-2">
                                <label class="block text-[10px] font-bold text-brand-dark uppercase tracking-wider">Apply Coupon Code</label>
                                <div class="flex gap-2" x-show="!appliedCoupon">
                                    <input type="text" x-model="couponCode" placeholder="COUPON CODE" class="flex-1 bg-white border border-brand-border rounded-xl px-3.5 py-2 text-xs text-brand-dark font-mono uppercase font-bold outline-none focus:ring-1 focus:ring-brand-dark focus:border-brand-dark">
                                    <button @click="applyCoupon" :disabled="couponLoading" class="bg-brand-dark text-white font-bold uppercase text-[11px] px-4 py-2 rounded-xl hover:bg-brand-text transition-colors disabled:opacity-50 cursor-pointer">
                                        APPLY
                                    </button>
                                </div>
                                <p x-show="couponError" x-text="couponError" class="text-red-500 text-[11px] font-medium" x-cloak></p>

                                <div x-show="appliedCoupon" x-cloak class="flex items-center justify-between bg-emerald-50 border border-emerald-200 rounded-xl p-2.5">
                                    <div class="text-xs">
                                        <span class="font-bold text-emerald-900 font-mono block" x-text="appliedCoupon"></span>
                                        <span class="text-[10px] text-emerald-700 font-semibold" x-text="couponSuccess"></span>
                                    </div>
                                    <button @click="removeCoupon" class="text-[10px] text-red-600 font-bold uppercase hover:underline cursor-pointer">Remove</button>
                                </div>

                                @if($availableCoupons && $availableCoupons->count() > 0)
                                <div class="pt-1.5" x-show="!appliedCoupon">
                                    <span class="text-[10px] font-bold text-brand-muted uppercase tracking-wider block mb-1.5">Available Offers:</span>
                                    <div class="space-y-1.5">
                                        @foreach($availableCoupons as $avCoupon)
                                            <div class="border border-dashed border-brand-border rounded-xl p-2 flex items-center justify-between bg-brand-off-white/50">
                                                <div class="text-[11px]">
                                                    <span class="font-mono font-bold text-brand-dark uppercase">{{ $avCoupon->code }}</span>
                                                    <p class="text-[9px] text-brand-muted leading-tight">{{ $avCoupon->description ?? ($avCoupon->type === 'percent' ? $avCoupon->value.'% Off' : '₹'.$avCoupon->value.' Off') }}</p>
                                                </div>
                                                <button @click="couponCode = '{{ $avCoupon->code }}'; applyCoupon()" class="text-[10px] font-extrabold text-brand-dark uppercase hover:underline cursor-pointer">
                                                    Apply
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>

                            {{-- Total Amount --}}
                            <div class="pt-3.5 border-t border-dashed border-brand-border flex justify-between items-baseline">
                                <span class="text-xs sm:text-sm font-extrabold text-brand-dark uppercase tracking-wider">TOTAL AMOUNT</span>
                                <span class="text-lg sm:text-xl font-extrabold font-heading text-brand-dark">₹<span x-text="finalTotal"></span></span>
                            </div>

                            {{-- Savings Callout --}}
                            <div x-show="appliedDiscount > 0" class="bg-emerald-50 border border-emerald-200 rounded-xl p-2.5 text-center text-xs font-bold text-emerald-800" x-cloak>
                                🎉 You save ₹<span x-text="appliedDiscount.toFixed(2)"></span> on this order
                            </div>

                            {{-- CONFIRM ORDER BUTTON --}}
                            <div class="pt-2">
                                <button @click="submitCheckout" :disabled="loading" 
                                        class="w-full bg-brand-dark text-white hover:bg-brand-text font-bold uppercase tracking-wider text-xs sm:text-sm py-4 px-4 rounded-xl shadow-sm hover:shadow-md active:scale-[0.99] transition-all disabled:opacity-50 flex justify-center items-center gap-2 cursor-pointer">
                                    <span x-show="!loading" class="flex items-center justify-center gap-2 whitespace-nowrap">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                        <span>CONFIRM ORDER</span>
                                        <span>(₹<span x-text="finalTotal"></span>)</span>
                                    </span>
                                    <span x-show="loading" class="flex items-center justify-center gap-2">
                                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        <span>Processing Order...</span>
                                    </span>
                                </button>
                            </div>
                        </div>

                        {{-- Bottom Security Banner --}}
                        <div class="bg-brand-off-white p-3.5 border-t border-brand-border flex items-center justify-center gap-3 text-[10px] font-bold text-brand-muted uppercase tracking-wider">
                            <span>🔒 256-Bit SSL</span>
                            <span>•</span>
                            <span>100% Authentic</span>
                        </div>

                    </div>
                </div>

            </div>
        @else
            {{-- EMPTY CHECKOUT BAG STATE --}}
            <div class="bg-white rounded-2xl shadow-xs border border-brand-border p-8 sm:p-12 text-center max-w-xl mx-auto my-4 sm:my-6">
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-brand-off-white border border-brand-border flex items-center justify-center mx-auto mb-4 shadow-2xs">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-brand-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z" />
                    </svg>
                </div>
                <h2 class="text-base sm:text-xl font-heading font-extrabold text-brand-dark mb-1.5">Your Bag is Empty</h2>
                <p class="text-xs sm:text-sm text-brand-muted max-w-sm mx-auto mb-6 leading-relaxed">
                    There are no items in your checkout bag right now. Re-add items from your wishlist or discover fresh streetwear styles.
                </p>
                <div class="flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('frontend.products.index') }}" class="inline-flex items-center gap-2 bg-brand-dark text-white text-xs sm:text-sm font-bold uppercase tracking-wider px-6 py-3.5 rounded-xl hover:bg-brand-text transition-all shadow-sm active:scale-98">
                        <span>Explore Collections</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </a>
                    @auth
                        <a href="{{ route('account.wishlist') }}" class="inline-flex items-center gap-2 bg-brand-off-white border border-brand-border text-brand-dark text-xs sm:text-sm font-bold uppercase tracking-wider px-5 py-3.5 rounded-xl hover:bg-white transition-all shadow-2xs">
                            <span>View Wishlist</span>
                        </a>
                    @endauth
                </div>
            </div>

            {{-- WISHLIST QUICK MOVE (IF ANY) --}}
            @if($wishlistItems->count() > 0)
            <div class="bg-white rounded-2xl shadow-xs border border-brand-border p-4 sm:p-6 mt-6 max-w-3xl mx-auto">
                <h3 class="text-xs sm:text-sm font-heading font-bold text-brand-dark uppercase tracking-wider mb-4 flex items-center justify-between">
                    <span class="flex items-center gap-2">
                        <span>❤️</span> Items in Your Wishlist
                    </span>
                    <span class="bg-brand-off-white border border-brand-border text-brand-dark px-2.5 py-0.5 rounded-md text-[11px] font-bold">{{ $wishlistItems->count() }} items</span>
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($wishlistItems as $wItem)
                        @php
                            $wProd = $wItem->product;
                            if (!$wProd) continue;
                            $wImg = $wProd->primaryImage ?? $wProd->images->first();
                        @endphp
                        <div class="border border-brand-border rounded-xl p-3 flex items-center justify-between gap-3 bg-brand-off-white/40 hover:bg-white transition-all">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-12 h-14 bg-white border border-brand-border rounded-lg overflow-hidden shrink-0 shadow-2xs">
                                    @if($wImg)
                                        <img src="{{ $wImg->url }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="min-w-0 text-xs">
                                    <h4 class="font-bold text-brand-dark truncate">{{ $wProd->name }}</h4>
                                    <p class="font-extrabold text-brand-dark mt-0.5">₹{{ number_format((float) ($wProd->price ?? 0)) }}</p>
                                </div>
                            </div>
                            <form action="{{ route('frontend.cart.moveFromWishlist', $wItem->id) }}" method="POST" class="shrink-0">
                                @csrf
                                <button type="submit" class="text-[11px] font-bold uppercase tracking-wider bg-brand-dark text-white px-3 py-2 rounded-lg hover:bg-brand-text transition-colors cursor-pointer shadow-xs active:scale-95">
                                    + Add to Bag
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        @endif

        {{-- RECOMMENDED PRODUCTS SECTION --}}
        @if(isset($recommendedProducts) && $recommendedProducts->count() > 0)
        <div class="mt-10 sm:mt-14 pt-8 sm:pt-10 border-t border-brand-border">
            <div class="flex items-center justify-between mb-4 sm:mb-6">
                <h2 class="text-sm sm:text-base font-heading font-extrabold text-brand-dark uppercase tracking-wider">You Might Also Like</h2>
                <a href="{{ route('frontend.products.index') }}" class="text-xs font-bold uppercase tracking-wider text-brand-dark hover:underline">View All →</a>
            </div>
            
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                @foreach($recommendedProducts as $product)
                    @include('frontend.pages.products.partials.card', ['product' => $product])
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('checkoutForm', () => ({
            selectedAddressId: {{ $addresses->count() > 0 ? $addresses->first()->id : 'null' }},
            showNewAddress: {{ $addresses->count() == 0 ? 'true' : 'false' }},
            form: {
                name: '',
                phone: '',
                alternate_phone: '',
                line1: '',
                line2: '',
                landmark: '',
                city: '',
                state: '',
                pincode: '',
                payment_method: 'razorpay'
            },
            
            errors: {},
            baseTotal: {{ $summary['total'] }},
            appliedCoupon: '{{ $coupon ? $coupon->code : "" }}',
            appliedDiscount: {{ $discount }},
            couponCode: '',
            couponError: '',
            couponSuccess: '{{ $coupon ? "Coupon applied" : "" }}',
            loading: false,
            couponLoading: false,
            
            init() {
                // GA4 begin_checkout
                if (typeof gtag === 'function') {
                    gtag('event', 'begin_checkout', {
                        currency: 'INR',
                        value: this.baseTotal,
                        items: [
                            @foreach($cart->items as $item)
                            {
                                item_id: '{{ $item->variant->sku ?? $item->variant_id }}',
                                item_name: '{{ $item->variant->product->name }}',
                                price: {{ $item->variant->price ?? $item->variant->product->price }},
                                quantity: {{ $item->quantity }}
                            },
                            @endforeach
                        ]
                    });
                }
            },

            get finalTotal() {
                let total = (this.baseTotal - this.appliedDiscount);
                return (total > 0 ? total : 0).toFixed(2);
            },

            async submitCheckout() {
                // Address Validation
                this.errors = {};
                let hasErrors = false;

                if (!this.selectedAddressId && (this.showNewAddress || '{{ $addresses->count() }}' === '0')) {
                    if (!this.form.name || !this.form.name.trim()) {
                        this.errors.name = 'Please enter your full name';
                        hasErrors = true;
                    }
                    if (!this.form.phone || !this.form.phone.trim() || this.form.phone.trim().length < 10) {
                        this.errors.phone = 'Please enter a valid 10-digit mobile number';
                        hasErrors = true;
                    }
                    if (!this.form.line1 || !this.form.line1.trim()) {
                        this.errors.line1 = 'Please enter house / building details';
                        hasErrors = true;
                    }
                    if (!this.form.line2 || !this.form.line2.trim()) {
                        this.errors.line2 = 'Please enter street / area details';
                        hasErrors = true;
                    }
                    if (!this.form.city || !this.form.city.trim()) {
                        this.errors.city = 'Please enter city';
                        hasErrors = true;
                    }
                    if (!this.form.state) {
                        this.errors.state = 'Please select your state';
                        hasErrors = true;
                    }
                    if (!this.form.pincode || !this.form.pincode.trim() || this.form.pincode.trim().length < 6) {
                        this.errors.pincode = 'Please enter a valid 6-digit pincode';
                        hasErrors = true;
                    }
                } else if (!this.selectedAddressId && '{{ $addresses->count() }}' > 0) {
                    if (window.showToast) window.showToast('Please select a delivery address to proceed.', 'error');
                    document.getElementById('delivery-address-step')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return;
                }

                if (hasErrors) {
                    document.getElementById('delivery-address-step')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    this.$nextTick(() => {
                        const firstErr = document.querySelector('#delivery-address-step input.border-red-400, #delivery-address-step select.border-red-400');
                        if (firstErr) firstErr.focus();
                    });
                    return;
                }

                this.loading = true;

                let payload = {
                    payment_method: this.form.payment_method
                };

                if (this.selectedAddressId && !this.showNewAddress) {
                    payload.address_id = this.selectedAddressId;
                } else {
                    payload = { ...payload, ...this.form };
                }

                try {
                    let res = await fetch('{{ route("frontend.checkout.process") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(payload)
                    });

                    let data = await res.json();

                    if (data.success) {
                        let targetUrl = data.redirect || data.redirect_url;
                        if (targetUrl) {
                            window.location.href = targetUrl;
                        } else if (data.razorpay) {
                            this.openRazorpay(data.razorpay);
                        } else if (data.razorpay_order_id) {
                            this.openRazorpay(data);
                        } else {
                            if (window.showToast) window.showToast('Order placed successfully.', 'success');
                            this.loading = false;
                        }
                    } else {
                        if (window.showToast) window.showToast(data.message || 'Error processing checkout.', 'error');
                        this.loading = false;
                    }
                } catch(e) {
                    console.error(e);
                    if (window.showToast) window.showToast('Something went wrong. Please try again.', 'error');
                    this.loading = false;
                }
            },

            openRazorpay(data) {
                var options = {
                    "key": data.key || "{{ config('services.razorpay.key_id') ?: env('RAZORPAY_KEY_ID', env('RAZORPAY_KEY', 'rzp_test_dummy')) }}", 
                    "amount": data.amount,
                    "currency": data.currency || "INR",
                    "name": data.name || "ThreadAX Streetwear",
                    "description": data.description || "Order Payment",
                    "order_id": data.razorpay_order_id,
                    "prefill": {
                        "name": data.prefill?.name || "",
                        "email": data.prefill?.email || "",
                        "contact": data.prefill?.contact || ""
                    },
                    "theme": {
                        "color": "#000000"
                    },
                    "handler": function (response){
                        let form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ route("frontend.checkout.callback") }}';
                        
                        let csrf = document.createElement('input');
                        csrf.type = 'hidden';
                        csrf.name = '_token';
                        csrf.value = '{{ csrf_token() }}';
                        form.appendChild(csrf);

                        let sig = document.createElement('input');
                        sig.type = 'hidden';
                        sig.name = 'razorpay_signature';
                        sig.value = response.razorpay_signature;
                        form.appendChild(sig);

                        let pid = document.createElement('input');
                        pid.type = 'hidden';
                        pid.name = 'razorpay_payment_id';
                        pid.value = response.razorpay_payment_id;
                        form.appendChild(pid);

                        let oid = document.createElement('input');
                        oid.type = 'hidden';
                        oid.name = 'razorpay_order_id';
                        oid.value = response.razorpay_order_id;
                        form.appendChild(oid);

                        let dbId = document.createElement('input');
                        dbId.type = 'hidden';
                        dbId.name = 'order_id_db';
                        dbId.value = data.order_id_db || '';
                        form.appendChild(dbId);

                        document.body.appendChild(form);
                        form.submit();
                    }
                };

                if (options.key === 'rzp_test_dummy' || !window.Razorpay) {
                    if (window.showToast) window.showToast('Connecting to payment gateway...', 'info');
                    
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("frontend.checkout.callback") }}';
                    form.innerHTML = `
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="razorpay_signature" value="dummy_signature">
                        <input type="hidden" name="razorpay_payment_id" value="pay_dummy_${Math.floor(Math.random()*100000)}">
                        <input type="hidden" name="razorpay_order_id" value="${data.razorpay_order_id}">
                        <input type="hidden" name="order_id_db" value="${data.order_id_db || ''}">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                    return;
                }

                var rzp = new Razorpay(options);
                rzp.on('payment.failed', (function (resp){
                    console.error('Payment failed:', resp.error);
                    if (window.showToast) window.showToast(resp.error?.description || 'Payment was cancelled or failed.', 'error');
                    this.loading = false;
                }).bind(this));
                rzp.open();
            },

            async applyCoupon() {
                if (!this.couponCode.trim()) return;
                this.couponLoading = true;
                this.couponError = '';
                this.couponSuccess = '';
                try {
                    let res = await fetch('{{ route("frontend.checkout.coupon.apply") }}', {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                        },
                        body: JSON.stringify({ coupon_code: this.couponCode })
                    });
                    let data = await res.json();
                    if (data.success) {
                        this.appliedCoupon = data.coupon;
                        this.appliedDiscount = parseFloat(data.discount.replace(',', ''));
                        this.couponSuccess = data.message;
                    } else {
                        this.couponError = data.message || 'Invalid coupon code.';
                    }
                } catch(e) {
                    this.couponError = 'Something went wrong.';
                } finally {
                    this.couponLoading = false;
                }
            },

            async removeCoupon() {
                await fetch('{{ route("frontend.checkout.coupon.remove") }}', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                    }
                });
                this.appliedCoupon = '';
                this.appliedDiscount = 0;
                this.couponCode = '';
                this.couponSuccess = '';
                this.couponError = '';
            }
        }));
    });
</script>
@endpush
