@extends('admin.layouts.app')

@section('title', 'Create Coupon - Admin')
@section('page-title')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.coupons.index') }}" class="text-slate-400 hover:text-slate-900 transition-colors p-1.5 rounded-xl hover:bg-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <span class="text-lg font-extrabold text-slate-900">Create Discount Coupon</span>
            <p class="text-xs text-slate-400">Launch a new promotional campaign or VIP customer discount</p>
        </div>
    </div>
@endsection

@section('content')
<div class="max-w-5xl mx-auto space-y-6 sm:space-y-8"
     x-data="{
         code: '{{ addslashes(old('code', '')) }}',
         type: '{{ old('type', 'percent') }}',
         value: '{{ old('value', '20') }}',
         maxDiscount: '{{ old('max_discount_amount', '') }}',
         minOrder: '{{ old('min_order_amount', '999') }}',
         isActive: {{ old('is_active', true) ? 'true' : 'false' }},

         generateRandomCode() {
             const prefixes = ['THREADAX', 'VIP', 'STREET', 'DROP', 'FLASH', 'WINTER', 'SUMMER'];
             const prefix = prefixes[Math.floor(Math.random() * prefixes.length)];
             const num = [10, 15, 20, 25, 30, 50][Math.floor(Math.random() * 6)];
             this.code = prefix + num;
         },

         get simulatedSavings() {
             const sampleCart = 2500;
             if (this.type === 'percent') {
                 let disc = (sampleCart * (parseFloat(this.value) || 0)) / 100;
                 if (this.maxDiscount && parseFloat(this.maxDiscount) > 0) {
                     disc = Math.min(disc, parseFloat(this.maxDiscount));
                 }
                 return Math.round(disc);
             } else {
                 return Math.min(sampleCart, parseFloat(this.value) || 0);
             }
         }
     }">

    {{-- Form --}}
    <form method="POST" action="{{ route('admin.coupons.store') }}">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
            
            {{-- Main Form Column (2/3 width) --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Code & Discount Value Card --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-7 shadow-xs space-y-5">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-base">
                            🎟️
                        </div>
                        <div>
                            <h3 class="text-sm font-heading font-extrabold text-slate-900">Discount Configuration</h3>
                            <p class="text-xs text-slate-400">Coupon code, format, and discount values</p>
                        </div>
                    </div>

                    {{-- Coupon Code with Auto-Generator --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="code" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Coupon Code <span class="text-rose-500">*</span>
                            </label>
                            <button type="button" @click="generateRandomCode()" class="text-xs font-black text-purple-600 hover:text-purple-700 hover:underline cursor-pointer flex items-center gap-1">
                                <span>⚡ Auto-Generate Code</span>
                            </button>
                        </div>
                        <div class="relative">
                            <input type="text" 
                                   id="code" 
                                   name="code" 
                                   x-model="code" 
                                   value="{{ old('code') }}" 
                                   placeholder="e.g. SUMMER25" 
                                   required 
                                   class="w-full px-4 py-3 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-sm font-mono font-extrabold tracking-wider text-slate-900 outline-none uppercase transition-all @error('code') border-rose-500 @enderror">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Codes will be automatically converted to UPPERCASE.</p>
                        @error('code') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Discount Type & Value Grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        {{-- Type Selector --}}
                        <div>
                            <label for="type" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Discount Type <span class="text-rose-500">*</span>
                            </label>
                            <select id="type" 
                                    name="type" 
                                    x-model="type"
                                    required 
                                    class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-900 outline-none transition-all cursor-pointer">
                                <option value="percent">Percentage Discount (%)</option>
                                <option value="flat">Flat Amount Discount (₹)</option>
                            </select>
                        </div>

                        {{-- Value Input --}}
                        <div>
                            <label for="value" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Discount Rate / Amount <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center font-bold text-slate-400 text-xs" x-text="type === 'percent' ? '%' : '₹'"></span>
                                <input type="number" 
                                       id="value" 
                                       name="value" 
                                       x-model="value"
                                       value="{{ old('value') }}" 
                                       step="0.01" 
                                       min="0.01" 
                                       required 
                                       placeholder="e.g. 20" 
                                       class="w-full pl-8 pr-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-extrabold text-slate-900 outline-none transition-all @error('value') border-rose-500 @enderror">
                            </div>
                            @error('value') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                    </div>

                    {{-- Max Discount Cap (for percent type) & Min Cart Value --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        {{-- Max Discount Cap --}}
                        <div x-show="type === 'percent'" x-transition>
                            <label for="max_discount_amount" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Max Discount Cap (₹)
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center font-bold text-slate-400 text-xs">₹</span>
                                <input type="number" 
                                       id="max_discount_amount" 
                                       name="max_discount_amount" 
                                       x-model="maxDiscount"
                                       value="{{ old('max_discount_amount') }}" 
                                       step="0.01" 
                                       min="0" 
                                       placeholder="e.g. 500 (blank for no cap)" 
                                       class="w-full pl-8 pr-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-900 outline-none transition-all">
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1">Limits max savings on high-value carts.</p>
                        </div>

                        {{-- Minimum Order Amount --}}
                        <div>
                            <label for="min_order_amount" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Minimum Order Cart Value (₹)
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center font-bold text-slate-400 text-xs">₹</span>
                                <input type="number" 
                                       id="min_order_amount" 
                                       name="min_order_amount" 
                                       x-model="minOrder"
                                       value="{{ old('min_order_amount', 0) }}" 
                                       step="0.01" 
                                       min="0" 
                                       class="w-full pl-8 pr-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-900 outline-none transition-all">
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1">Coupon will only apply if cart meets this total.</p>
                        </div>

                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Campaign Description / Internal Note
                        </label>
                        <input type="text" 
                               id="description" 
                               name="description" 
                               value="{{ old('description') }}" 
                               placeholder="e.g. Summer sale promo for all orders over ₹999" 
                               class="w-full px-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all">
                    </div>
                </div>

                {{-- Usage Caps & Schedule Card --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-7 shadow-xs space-y-5">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-base">
                            ⏳
                        </div>
                        <div>
                            <h3 class="text-sm font-heading font-extrabold text-slate-900">Usage Limits & Schedule</h3>
                            <p class="text-xs text-slate-400">Total redemptions, customer frequency, and validity windows</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Max Total Uses --}}
                        <div>
                            <label for="max_uses" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Max Total Redemptions
                            </label>
                            <input type="number" 
                                   id="max_uses" 
                                   name="max_uses" 
                                   value="{{ old('max_uses') }}" 
                                   min="1" 
                                   placeholder="Blank for unlimited" 
                                   class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-900 outline-none transition-all">
                        </div>

                        {{-- Max Uses Per User --}}
                        <div>
                            <label for="max_uses_per_user" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Max Uses Per Customer
                            </label>
                            <input type="number" 
                                   id="max_uses_per_user" 
                                   name="max_uses_per_user" 
                                   value="{{ old('max_uses_per_user', 1) }}" 
                                   min="1" 
                                   class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-900 outline-none transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Starts At --}}
                        <div>
                            <label for="starts_at" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Campaign Start Date (Optional)
                            </label>
                            <input type="datetime-local" 
                                   id="starts_at" 
                                   name="starts_at" 
                                   value="{{ old('starts_at') }}" 
                                   class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all">
                        </div>

                        {{-- Expires At --}}
                        <div>
                            <label for="expires_at" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Expiration Date (Optional)
                            </label>
                            <input type="datetime-local" 
                                   id="expires_at" 
                                   name="expires_at" 
                                   value="{{ old('expires_at') }}" 
                                   class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all">
                        </div>
                    </div>
                </div>

            </div>

            {{-- Right Column (1/3 width) --}}
            <div class="space-y-6">
                
                {{-- Live Coupon Simulator Card --}}
                <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-950 rounded-2xl p-6 text-white shadow-md space-y-4">
                    <div class="flex items-center justify-between text-xs text-amber-400 font-bold uppercase tracking-wider">
                        <span>🏷️ Live Coupon Simulator</span>
                        <span>Sample ₹2,500 Cart</span>
                    </div>

                    <div class="p-4 rounded-xl bg-white/10 border border-white/10 backdrop-blur-xs text-center space-y-2">
                        <span class="block font-mono font-black text-xl tracking-widest text-white uppercase" x-text="code || 'YOURCODE'"></span>
                        <div class="inline-block px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 font-extrabold text-xs border border-emerald-400/30">
                            <span x-text="type === 'percent' ? value + '% OFF' : '₹' + value + ' FLAT OFF'"></span>
                        </div>
                    </div>

                    <div class="space-y-1.5 text-xs text-slate-300 pt-2 border-t border-white/10">
                        <div class="flex justify-between">
                            <span>Cart Subtotal:</span>
                            <span class="font-bold text-white">₹2,500.00</span>
                        </div>
                        <div class="flex justify-between text-emerald-400 font-bold">
                            <span>Discount Applied:</span>
                            <span>- ₹<span x-text="simulatedSavings"></span></span>
                        </div>
                        <div class="flex justify-between text-white font-extrabold text-sm pt-2 border-t border-white/10">
                            <span>Final Checkout Total:</span>
                            <span>₹<span x-text="2500 - simulatedSavings"></span></span>
                        </div>
                    </div>
                </div>

                {{-- Status & Visibility --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                            ⚙️
                        </div>
                        <h3 class="text-xs font-heading font-extrabold text-slate-900 uppercase tracking-wider">Publish Status</h3>
                    </div>

                    {{-- Active Toggle Switch --}}
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                        <div>
                            <span class="text-xs font-bold text-slate-900 block">Active Status</span>
                            <span class="text-[11px] text-slate-400">Enable on checkout</span>
                        </div>
                        <button type="button" 
                                @click="isActive = !isActive" 
                                :class="isActive ? 'bg-slate-900' : 'bg-slate-200'" 
                                class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none shadow-2xs">
                            <span :class="isActive ? 'translate-x-5' : 'translate-x-0'" 
                                  class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"></span>
                        </button>
                        <input type="hidden" name="is_active" :value="isActive ? '1' : '0'">
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs space-y-3">
                    <button type="submit" class="w-full py-3.5 px-6 rounded-xl text-xs font-extrabold text-white bg-slate-900 hover:bg-slate-800 transition-all shadow-sm hover:shadow-md flex justify-center items-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>Create & Launch Coupon</span>
                    </button>

                    <div class="text-center pt-1">
                        <a href="{{ route('admin.coupons.index') }}" class="text-xs font-bold text-slate-400 hover:text-slate-700 transition-colors">
                            Discard & Go Back
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </form>

</div>
@endsection
