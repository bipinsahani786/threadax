@extends('frontend.layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="bg-[#f1f2f6] min-h-screen py-6 sm:py-8 font-sans selection:bg-brand-dark selection:text-white">
    <div class="max-w-[1280px] mx-auto px-3 sm:px-6 lg:px-8">
        
        {{-- Top Bar --}}
        <div class="bg-white rounded-t-xl shadow-sm border border-gray-200 px-6 py-4 mb-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('frontend.cart.data') }}" class="text-xs font-bold uppercase tracking-wider text-gray-500 hover:text-black transition-colors flex items-center gap-1">
                    ← Back to Cart
                </a>
                <span class="text-gray-300">|</span>
                <h1 class="text-lg font-heading font-black uppercase tracking-tight text-black">Checkout</h1>
            </div>
            <div class="flex items-center gap-2 text-xs font-bold text-green-700 bg-green-50 px-3 py-1 rounded-full border border-green-200">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                100% SECURE CHECKOUT
            </div>
        </div>
        
        <div class="flex flex-col lg:flex-row gap-5" x-data="checkoutForm()">
            
            {{-- LEFT COLUMN: Clean Multi-Step Cards --}}
            <div class="flex-1 space-y-3">
                
                {{-- STEP 1: LOGIN --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 flex items-center justify-between border-b border-gray-200">
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded bg-black text-white font-black text-xs flex items-center justify-center">1</span>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-sm text-gray-500 uppercase tracking-wider">LOGIN</span>
                                <span class="text-xs font-bold text-black flex items-center gap-1 ml-2">
                                    ✓ {{ Auth::user()->name ?? 'Guest User' }}
                                    <span class="text-gray-400 font-normal">({{ Auth::user()->phone ?? Auth::user()->email ?? '' }})</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- STEP 2: DELIVERY ADDRESS --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 flex items-center justify-between border-b border-gray-200">
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded bg-black text-white font-black text-xs flex items-center justify-center">2</span>
                            <h2 class="font-heading font-black text-sm text-black uppercase tracking-wider">DELIVERY ADDRESS</h2>
                        </div>
                        @if($addresses->count() > 0)
                            <button @click="showNewAddress = !showNewAddress; if(showNewAddress) selectedAddressId = null;" 
                                    class="text-xs font-bold uppercase tracking-wider text-black hover:underline transition-all">
                                <span x-text="showNewAddress ? 'Select Saved Address' : '+ Add New Address'"></span>
                            </button>
                        @endif
                    </div>

                    <div class="p-6">
                        @if($addresses->count() > 0)
                            <div x-show="!showNewAddress" class="space-y-4">
                                <div class="grid grid-cols-1 gap-3">
                                    @foreach($addresses as $addr)
                                        <div @click="selectedAddressId = {{ $addr->id }}"
                                             class="border-2 rounded-lg p-4 cursor-pointer transition-all duration-200 flex items-start gap-4 relative"
                                             :class="selectedAddressId == {{ $addr->id }} ? 'border-black bg-gray-50/80 shadow-sm' : 'border-gray-200 hover:border-gray-300'">
                                            
                                            <input type="radio" x-model="selectedAddressId" value="{{ $addr->id }}" class="mt-1 text-black focus:ring-black h-4 w-4">

                                            <div class="flex-1 text-xs">
                                                <div class="flex items-center gap-3 mb-1">
                                                    <span class="font-bold text-sm text-black capitalize">{{ $addr->name }}</span>
                                                    <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-gray-200 text-gray-700">{{ $addr->type }}</span>
                                                    <span class="font-semibold text-black ml-auto">📞 {{ $addr->phone }}</span>
                                                </div>
                                                <p class="text-gray-600 leading-relaxed">
                                                    {{ $addr->line1 }}, @if($addr->line2){{ $addr->line2 }}, @endif{{ $addr->city }}, {{ $addr->state }} - <span class="font-bold text-black">{{ $addr->pincode }}</span>
                                                    @if($addr->landmark) <span class="text-gray-400"> (Landmark: {{ $addr->landmark }})</span> @endif
                                                </p>

                                                <div x-show="selectedAddressId == {{ $addr->id }}" class="mt-3 pt-3 border-t border-gray-200/80 flex items-center justify-between">
                                                    <span class="text-[11px] font-bold text-green-700 flex items-center gap-1">✓ Deliver to this address</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div x-init="showNewAddress = true"></div>
                        @endif

                        {{-- New Address Form --}}
                        <div x-show="showNewAddress || '{{ $addresses->count() }}' == '0'" x-cloak class="space-y-4 pt-1">
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">ADD A NEW ADDRESS</h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">Name <span class="text-red-500">*</span></label>
                                    <input type="text" x-model="form.name" class="w-full bg-white border border-gray-300 rounded px-3.5 py-2.5 text-xs focus:ring-1 focus:ring-black focus:border-black outline-none" placeholder="Full Name">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">10-digit Mobile Number <span class="text-red-500">*</span></label>
                                    <input type="text" x-model="form.phone" class="w-full bg-white border border-gray-300 rounded px-3.5 py-2.5 text-xs focus:ring-1 focus:ring-black focus:border-black outline-none" placeholder="Mobile Number">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">Flat, House No., Building <span class="text-red-500">*</span></label>
                                    <input type="text" x-model="form.line1" class="w-full bg-white border border-gray-300 rounded px-3.5 py-2.5 text-xs focus:ring-1 focus:ring-black focus:border-black outline-none" placeholder="House/Flat No., Building Name">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">Street, Area, Sector <span class="text-red-500">*</span></label>
                                    <input type="text" x-model="form.line2" class="w-full bg-white border border-gray-300 rounded px-3.5 py-2.5 text-xs focus:ring-1 focus:ring-black focus:border-black outline-none" placeholder="Area / Street Name">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">City <span class="text-red-500">*</span></label>
                                    <input type="text" x-model="form.city" class="w-full bg-white border border-gray-300 rounded px-3.5 py-2.5 text-xs focus:ring-1 focus:ring-black focus:border-black outline-none" placeholder="City / Town">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">State <span class="text-red-500">*</span></label>
                                    <select x-model="form.state" class="w-full bg-white border border-gray-300 rounded px-3.5 py-2.5 text-xs focus:ring-1 focus:ring-black focus:border-black outline-none">
                                        <option value="">Select State</option>
                                        @php
                                            $states = ['Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh', 'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jharkhand', 'Karnataka', 'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur', 'Meghalaya', 'Mizoram', 'Nagaland', 'Odisha', 'Punjab', 'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Telangana', 'Tripura', 'Uttar Pradesh', 'Uttarakhand', 'West Bengal', 'Delhi'];
                                        @endphp
                                        @foreach($states as $st)
                                            <option value="{{ $st }}">{{ $st }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">Pincode <span class="text-red-500">*</span></label>
                                    <input type="text" x-model="form.pincode" maxlength="6" class="w-full bg-white border border-gray-300 rounded px-3.5 py-2.5 text-xs focus:ring-1 focus:ring-black focus:border-black outline-none" placeholder="6-digit Pincode">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">Landmark <span class="font-normal text-gray-400 normal-case">(Optional)</span></label>
                                    <input type="text" x-model="form.landmark" class="w-full bg-white border border-gray-300 rounded px-3.5 py-2.5 text-xs focus:ring-1 focus:ring-black focus:border-black outline-none" placeholder="Nearby Landmark">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">Alternate Phone <span class="font-normal text-gray-400 normal-case">(Optional)</span></label>
                                    <input type="text" x-model="form.alternate_phone" class="w-full bg-white border border-gray-300 rounded px-3.5 py-2.5 text-xs focus:ring-1 focus:ring-black focus:border-black outline-none" placeholder="Alternate Phone">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- STEP 3: ORDER ITEMS --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 flex items-center justify-between border-b border-gray-200">
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded bg-black text-white font-black text-xs flex items-center justify-center">3</span>
                            <h2 class="font-heading font-black text-sm text-black uppercase tracking-wider">ORDER SUMMARY ({{ $cart->items->sum('quantity') }} ITEMS)</h2>
                        </div>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @foreach($cart->items as $item)
                            <div class="p-5 flex gap-4 sm:gap-6 items-start">
                                <div class="w-20 h-24 bg-gray-50 border border-gray-200 rounded overflow-hidden shrink-0">
                                    @if($item->variant->product->primaryImage)
                                        <img src="{{ $item->variant->product->primaryImage->url }}" alt="{{ $item->variant->product->name }}" class="w-full h-full object-cover">
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h3 class="font-bold text-sm text-black truncate">{{ $item->variant->product->name }}</h3>
                                    <p class="text-xs text-gray-500 mt-1">
                                        @if($item->variant->size) Size: <span class="font-bold text-black">{{ $item->variant->size }}</span> @endif
                                        @if($item->variant->color) | Color: <span class="font-bold text-black">{{ $item->variant->color }}</span> @endif
                                    </p>
                                    
                                    <div class="flex items-center gap-3 mt-3">
                                        <span class="text-base font-black text-black">₹{{ number_format(($item->variant->price ?? $item->variant->product->price) * $item->quantity) }}</span>
                                        @if($item->variant->product->compare_price > $item->variant->product->price)
                                            <span class="text-xs text-gray-400 line-through">₹{{ number_format($item->variant->product->compare_price * $item->quantity) }}</span>
                                            <span class="text-xs font-bold text-green-600">{{ $item->variant->product->discount_percent }}% Off</span>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-4 mt-4">
                                        {{-- Quantity Counter Widget --}}
                                        <div class="flex items-center border border-gray-300 rounded overflow-hidden shadow-xs">
                                            <form action="{{ route('frontend.cart.update') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="item_id" value="{{ $item->id }}">
                                                <input type="hidden" name="quantity" value="{{ $item->quantity - 1 }}">
                                                <button type="submit" 
                                                        class="w-7 h-7 bg-gray-100 hover:bg-gray-200 text-black font-black text-sm flex items-center justify-center transition-colors disabled:opacity-30 disabled:cursor-not-allowed"
                                                        {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                                    −
                                                </button>
                                            </form>

                                            <span class="w-8 text-center text-xs font-black text-black bg-white select-none">{{ $item->quantity }}</span>

                                            <form action="{{ route('frontend.cart.update') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="item_id" value="{{ $item->id }}">
                                                <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
                                                <button type="submit" 
                                                        class="w-7 h-7 bg-gray-100 hover:bg-gray-200 text-black font-black text-sm flex items-center justify-center transition-colors disabled:opacity-30 disabled:cursor-not-allowed"
                                                        {{ $item->quantity >= ($item->variant->stock ?? 99) ? 'disabled' : '' }}>
                                                    +
                                                </button>
                                            </form>
                                        </div>

                                        @if(($item->variant->stock ?? 99) <= 5)
                                            <span class="text-[10px] font-bold text-amber-600">Only {{ $item->variant->stock }} left!</span>
                                        @endif

                                        <form action="{{ route('frontend.cart.moveToWishlist', $item->id) }}" method="POST" class="ml-auto">
                                            @csrf
                                            <button type="submit" class="text-xs font-bold uppercase text-gray-500 hover:text-black transition-colors">
                                                MOVE TO WISHLIST
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- STEP 4: PAYMENT OPTIONS --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 flex items-center justify-between border-b border-gray-200">
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded bg-black text-white font-black text-xs flex items-center justify-center">4</span>
                            <h2 class="font-heading font-black text-sm text-black uppercase tracking-wider">PAYMENT OPTIONS</h2>
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        {{-- Online Payment --}}
                        <div @click="form.payment_method = 'razorpay'" 
                             class="border-2 rounded-lg p-4 cursor-pointer transition-all duration-200 flex items-center justify-between"
                             :class="form.payment_method === 'razorpay' ? 'border-black bg-gray-50/80 shadow-sm' : 'border-gray-200 hover:border-gray-300'">
                            <div class="flex items-center gap-3">
                                <input type="radio" x-model="form.payment_method" value="razorpay" class="text-black focus:ring-black h-4 w-4">
                                <div>
                                    <span class="font-bold text-sm text-black block">UPI / Online Payment (Razorpay)</span>
                                    <span class="text-xs text-gray-500">Google Pay, PhonePe, Paytm, Cards, NetBanking</span>
                                </div>
                            </div>
                            <img src="https://razorpay.com/assets/razorpay-logo.svg" alt="Razorpay" class="h-4 opacity-80 hidden sm:block">
                        </div>

                        {{-- COD --}}
                        <div @click="form.payment_method = 'cod'" 
                             class="border-2 rounded-lg p-4 cursor-pointer transition-all duration-200 flex items-center justify-between"
                             :class="form.payment_method === 'cod' ? 'border-black bg-gray-50/80 shadow-sm' : 'border-gray-200 hover:border-gray-300'">
                            <div class="flex items-center gap-3">
                                <input type="radio" x-model="form.payment_method" value="cod" class="text-black focus:ring-black h-4 w-4">
                                <div>
                                    <span class="font-bold text-sm text-black block">Cash on Delivery (COD)</span>
                                    <span class="text-xs text-gray-500">Pay cash upon delivery at your doorstep</span>
                                </div>
                            </div>
                            <span class="text-xl hidden sm:block">💵</span>
                        </div>
                    </div>
                </div>

                {{-- WISHLIST QUICK MOVE --}}
                @if($wishlistItems->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 mt-4">
                    <h3 class="text-xs font-bold text-black uppercase tracking-wider mb-3 flex items-center gap-2">
                        <span>❤️ ITEMS IN YOUR WISHLIST</span>
                        <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-[10px]">{{ $wishlistItems->count() }}</span>
                    </h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($wishlistItems as $wItem)
                            <div class="border border-gray-200 rounded p-3 flex items-center justify-between bg-gray-50/30">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-10 h-12 bg-gray-200 rounded overflow-hidden shrink-0">
                                        @if($wItem->product->primaryImage)
                                            <img src="{{ $wItem->product->primaryImage->url }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div class="min-w-0 text-xs">
                                        <h4 class="font-bold text-black truncate">{{ $wItem->product->name }}</h4>
                                        <p class="font-extrabold text-black">₹{{ number_format($wItem->product->price) }}</p>
                                    </div>
                                </div>
                                <form action="{{ route('frontend.cart.moveFromWishlist', $wItem->id) }}" method="POST" class="shrink-0 ml-2">
                                    @csrf
                                    <button type="submit" class="text-[10px] font-bold uppercase tracking-wider bg-black text-white px-3 py-1.5 rounded hover:bg-neutral-800 transition-colors">
                                        + MOVE TO CART
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>

            {{-- RIGHT COLUMN: PRICE DETAILS Panel with PROCEED CTA Right Below Total Amount --}}
            <div class="lg:w-[380px] shrink-0">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 sticky top-24 overflow-hidden">
                    
                    <h2 class="text-xs font-heading font-black text-gray-400 uppercase tracking-widest px-6 py-4 border-b border-gray-100">
                        PRICE DETAILS
                    </h2>

                    <div class="p-6 space-y-4 text-xs">
                        {{-- Item Price --}}
                        <div class="flex justify-between text-gray-700">
                            <span>Price ({{ $cart->items->sum('quantity') }} {{ Str::plural('item', $cart->items->sum('quantity')) }})</span>
                            <span class="font-bold text-black">₹{{ number_format($summary['subtotal'], 2) }}</span>
                        </div>

                        {{-- Discount --}}
                        <div x-show="appliedDiscount > 0" class="flex justify-between text-green-600 font-bold" x-cloak>
                            <span>Discount</span>
                            <span>- ₹<span x-text="appliedDiscount.toFixed(2)"></span></span>
                        </div>

                        {{-- Delivery --}}
                        <div class="flex justify-between text-gray-700">
                            <span>Delivery Charges</span>
                            <span class="text-green-600 font-extrabold uppercase">FREE</span>
                        </div>

                        {{-- Coupon Input --}}
                        <div class="pt-3 border-t border-gray-100 space-y-2">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider">Apply Coupon Code</label>
                            <div class="flex gap-2" x-show="!appliedCoupon">
                                <input type="text" x-model="couponCode" placeholder="COUPON CODE" class="flex-1 bg-white border border-gray-300 rounded px-3 py-2 text-xs text-black font-mono uppercase font-bold outline-none focus:border-black">
                                <button @click="applyCoupon" :disabled="couponLoading" class="bg-black text-white font-bold uppercase text-[11px] px-4 py-2 rounded hover:bg-neutral-800 transition-colors disabled:opacity-50">
                                    APPLY
                                </button>
                            </div>
                            <p x-show="couponError" x-text="couponError" class="text-red-500 text-[11px] font-medium" x-cloak></p>

                            <div x-show="appliedCoupon" x-cloak class="flex items-center justify-between bg-green-50 border border-green-200 rounded p-2.5">
                                <div class="text-xs">
                                    <span class="font-bold text-green-900 font-mono block" x-text="appliedCoupon"></span>
                                    <span class="text-[10px] text-green-700 font-semibold" x-text="couponSuccess"></span>
                                </div>
                                <button @click="removeCoupon" class="text-[10px] text-red-600 font-bold uppercase hover:underline">Remove</button>
                            </div>

                            @if($availableCoupons && $availableCoupons->count() > 0)
                            <div class="pt-2" x-show="!appliedCoupon">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1.5">Available Offers:</span>
                                <div class="space-y-1.5">
                                    @foreach($availableCoupons as $avCoupon)
                                        <div class="border border-dashed border-gray-300 rounded p-2 flex items-center justify-between bg-gray-50/50">
                                            <div class="text-[11px]">
                                                <span class="font-mono font-bold text-black uppercase">{{ $avCoupon->code }}</span>
                                                <p class="text-[9px] text-gray-500 leading-tight">{{ $avCoupon->description ?? ($avCoupon->type === 'percent' ? $avCoupon->value.'% Off' : '₹'.$avCoupon->value.' Off') }}</p>
                                            </div>
                                            <button @click="couponCode = '{{ $avCoupon->code }}'; applyCoupon()" class="text-[10px] font-extrabold text-black uppercase hover:underline">
                                                Apply
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        {{-- Total Amount --}}
                        <div class="pt-4 border-t border-dashed border-gray-300 flex justify-between items-baseline">
                            <span class="text-sm font-black text-black uppercase tracking-wider">TOTAL AMOUNT</span>
                            <span class="text-xl font-black text-black">₹<span x-text="finalTotal"></span></span>
                        </div>

                        {{-- Savings Callout --}}
                        <div x-show="appliedDiscount > 0" class="bg-green-50 border border-green-200 rounded p-2.5 text-center text-xs font-bold text-green-800" x-cloak>
                            🎉 You save ₹<span x-text="appliedDiscount.toFixed(2)"></span> on this order
                        </div>

                        {{-- PROCEED TO CHECKOUT / PLACE ORDER BUTTON (Right under Total Amount) --}}
                        <div class="pt-2">
                            <button @click="submitCheckout" :disabled="loading" 
                                    class="w-full bg-black text-white hover:bg-neutral-900 font-black uppercase tracking-wider text-xs py-3.5 px-4 rounded shadow-md hover:shadow-lg transition-all disabled:opacity-50 flex justify-center items-center gap-2 whitespace-nowrap">
                                <span x-show="!loading" class="flex items-center justify-center gap-1.5 whitespace-nowrap">
                                    <span>CONFIRM ORDER</span>
                                    <span>(₹<span x-text="finalTotal"></span>)</span>
                                    <svg class="w-3.5 h-3.5 shrink-0 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                                </span>
                                <span x-show="loading" class="flex items-center justify-center gap-2">
                                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    Processing...
                                </span>
                            </button>
                        </div>
                    </div>

                    {{-- Bottom Security Banner --}}
                    <div class="bg-gray-50 p-4 border-t border-gray-100 flex items-center justify-center gap-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                        <span>🔒 Safe & Secure Payments</span>
                        <span>•</span>
                        <span>100% Authentic</span>
                    </div>

                </div>
            </div>

        </div>

        {{-- RECOMMENDED PRODUCTS SECTION --}}
        @if(isset($recommendedProducts) && $recommendedProducts->count() > 0)
        <div class="mt-14 pt-10 border-t border-gray-200">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-heading font-black text-black uppercase tracking-wider">You Might Also Like</h2>
                <a href="{{ route('frontend.products.index') }}" class="text-xs font-bold uppercase tracking-wider text-black hover:underline">View All →</a>
            </div>
            
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
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
                // Validation
                if (!this.selectedAddressId && this.showNewAddress) {
                    if (!this.form.name || !this.form.phone || !this.form.line1 || !this.form.line2 || !this.form.city || !this.form.state || !this.form.pincode) {
                        alert('Please fill in all required shipping address fields (*)');
                        return;
                    }
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
                            alert('Order placed successfully.');
                            this.loading = false;
                        }
                    } else {
                        alert(data.message || 'Error processing checkout.');
                        this.loading = false;
                    }
                } catch(e) {
                    console.error(e);
                    alert('Something went wrong. Please try again.');
                    this.loading = false;
                }
            },

            openRazorpay(data) {
                var options = {
                    "key": "{{ env('RAZORPAY_KEY_ID', 'rzp_test_dummy') }}", 
                    "amount": data.amount,
                    "currency": data.currency,
                    "name": data.name,
                    "description": data.description,
                    "order_id": data.razorpay_order_id,
                    "prefill": {
                        "name": data.prefill.name,
                        "email": data.prefill.email,
                        "contact": data.prefill.contact
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

                        document.body.appendChild(form);
                        form.submit();
                    }
                };

                if (options.key === 'rzp_test_dummy') {
                    alert('TEST MODE: Simulating successful Razorpay payment!');
                    
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("frontend.checkout.callback") }}';
                    form.innerHTML = `
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="razorpay_signature" value="dummy_signature">
                        <input type="hidden" name="razorpay_payment_id" value="pay_dummy_${Math.floor(Math.random()*100000)}">
                        <input type="hidden" name="razorpay_order_id" value="${data.razorpay_order_id}">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                    return;
                }

                var rzp1 = new Razorpay(options);
                rzp1.on('payment.failed', function (response){
                    alert("Payment Failed: " + response.error.description);
                    this.loading = false;
                }.bind(this));
                
                rzp1.open();
            },

            async applyCoupon() {
                if (!this.couponCode.trim()) return;
                this.couponLoading = true;
                this.couponError = '';
                this.couponSuccess = '';
                try {
                    let res = await fetch('{{ route("frontend.checkout.coupon.apply") }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
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
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
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
