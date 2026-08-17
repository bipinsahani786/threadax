@extends('frontend.layouts.app')

@section('title', 'My Account — ThreadAX')

@section('content')
<div class="bg-gradient-to-b from-brand-light via-white to-brand-light min-h-screen">

    {{-- Hero Banner with Background Image --}}
    <div class="bg-brand-text relative overflow-hidden">
        {{-- Background Image --}}
        <img src="{{ asset('images/banner-oversized.png') }}" alt="" class="absolute inset-0 w-full h-full object-cover object-top opacity-20 mix-blend-luminosity">
        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/60 to-black/40"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-14 relative z-10">
            <div class="flex items-center gap-4 sm:gap-6">
                {{-- Avatar --}}
                <div class="w-14 h-14 sm:w-20 sm:h-20 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center ring-2 ring-white/20 shrink-0 shadow-2xl">
                    <span class="text-white text-xl sm:text-3xl font-black font-heading">{{ strtoupper(substr(auth()->user()->name ?? auth()->user()->email, 0, 1)) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <h1 class="text-xl sm:text-3xl font-heading font-black text-white tracking-tight truncate">{{ auth()->user()->name ?? 'Member' }}</h1>
                    <p class="text-white/60 text-xs sm:text-sm mt-0.5 truncate">{{ auth()->user()->email }}</p>
                    <div class="flex flex-wrap items-center gap-3 mt-1.5 sm:mt-2">
                        <span class="inline-flex items-center gap-1.5 text-[11px] sm:text-xs text-white/40 font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                            Member since {{ auth()->user()->created_at->format('M Y') }}
                        </span>
                        @if(auth()->user()->phone)
                            <span class="inline-flex items-center gap-1.5 text-[11px] sm:text-xs text-white/40 font-medium">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                                {{ auth()->user()->phone }}
                            </span>
                        @endif
                    </div>
                </div>
                {{-- Edit Profile Button (Desktop) --}}
                <a href="{{ route('account.profile') }}" class="hidden sm:inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm text-white text-xs font-bold uppercase tracking-wider px-5 py-2.5 rounded-xl border border-white/20 hover:bg-white/20 transition-all shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                    Edit Profile
                </a>
            </div>
        </div>
    </div>

    {{-- Navigation Tabs with Scroll Indicators & Auto-Center --}}
    <div class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-brand-border shadow-sm"
         x-data="{
            canScrollLeft: false,
            canScrollRight: true,
            checkScroll() {
                const el = this.$refs.navTabs;
                if (!el) return;
                this.canScrollLeft = el.scrollLeft > 10;
                this.canScrollRight = el.scrollLeft < (el.scrollWidth - el.clientWidth - 10);
            },
            scroll(direction) {
                const el = this.$refs.navTabs;
                if (!el) return;
                el.scrollBy({ left: direction * 160, behavior: 'smooth' });
                setTimeout(() => this.checkScroll(), 300);
            }
         }"
         x-init="
            $nextTick(() => {
                const active = $refs.navTabs.querySelector('.active-account-tab');
                if (active) {
                    active.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                }
                checkScroll();
            });
         ">
        
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 relative">
            
            {{-- Left Scroll Button / Fade Indicator (Mobile) --}}
            <div x-show="canScrollLeft" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="absolute left-0 top-0 bottom-0 z-10 flex items-center pl-1 pr-4 bg-gradient-to-r from-white via-white/90 to-transparent pointer-events-none sm:hidden">
                <button type="button" @click="scroll(-1)" class="w-6 h-6 rounded-full bg-brand-dark text-white flex items-center justify-center shadow-md pointer-events-auto cursor-pointer" aria-label="Scroll Left">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
            </div>

            {{-- Tab Items --}}
            <nav x-ref="navTabs" 
                 @scroll.passive="checkScroll()" 
                 class="flex items-center gap-1 sm:gap-0 overflow-x-auto no-scrollbar -mb-px py-1 sm:py-0 scroll-smooth">
                @php
                    $tabs = [
                        ['route' => 'account.dashboard', 'label' => 'Dashboard', 'icon' => 'M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z', 'match' => 'account.dashboard'],
                        ['route' => 'account.orders', 'label' => 'Orders', 'icon' => 'M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12', 'match' => 'account.orders*'],
                        ['route' => 'account.returns', 'label' => 'Returns', 'icon' => 'M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99', 'match' => 'account.returns*'],
                        ['route' => 'account.transactions', 'label' => 'Payments', 'icon' => 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z', 'match' => 'account.transactions*'],
                        ['route' => 'account.addresses', 'label' => 'Addresses', 'icon' => 'M15 10.5a3 3 0 11-6 0 3 3 0 016 0z M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z', 'match' => 'account.addresses'],
                        ['route' => 'account.wishlist', 'label' => 'Wishlist', 'icon' => 'M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z', 'match' => 'account.wishlist'],
                        ['route' => 'account.reviews', 'label' => 'Reviews', 'icon' => 'M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z', 'match' => 'account.reviews'],
                        ['route' => 'account.profile', 'label' => 'Profile', 'icon' => 'M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z', 'match' => 'account.profile'],
                    ];
                @endphp
                @foreach($tabs as $tab)
                    @php $isActive = request()->routeIs($tab['match']); @endphp
                    <a href="{{ route($tab['route']) }}" 
                       class="flex items-center gap-1.5 sm:gap-2 px-3.5 sm:px-5 py-3 sm:py-4 text-xs sm:text-sm font-bold uppercase tracking-wider whitespace-nowrap border-b-2 transition-all shrink-0 rounded-lg sm:rounded-none {{ $isActive ? 'active-account-tab border-brand-text text-brand-text bg-brand-light/60 sm:bg-transparent font-extrabold' : 'border-transparent text-brand-muted hover:text-brand-text hover:border-brand-border' }}">
                        <svg class="w-4 h-4 shrink-0 {{ $isActive ? 'text-brand-text' : 'text-brand-muted' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ $isActive ? '2' : '1.5' }}"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $tab['icon'] }}"/></svg>
                        <span>{{ $tab['label'] }}</span>
                    </a>
                @endforeach
            </nav>

            {{-- Right Scroll Button / Fade Indicator (Mobile) --}}
            <div x-show="canScrollRight" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="absolute right-0 top-0 bottom-0 z-10 flex items-center pr-1 pl-4 bg-gradient-to-l from-white via-white/90 to-transparent pointer-events-none sm:hidden">
                <button type="button" @click="scroll(1)" class="w-6 h-6 rounded-full bg-brand-dark text-white flex items-center justify-center shadow-md pointer-events-auto cursor-pointer" aria-label="Scroll Right">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>

        </div>
    </div>

    {{-- Main Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10">
        @yield('account_content')

        {{-- Recommended Streetwear Drops Section (Appears on all account pages) --}}
        @php
            $accountRecommendedProducts = \App\Models\Product::with(['images', 'variants'])
                ->where('is_active', true)
                ->inRandomOrder()
                ->take(4)
                ->get();
        @endphp

        @if($accountRecommendedProducts->count() > 0)
        <div class="mt-8 sm:mt-12 bg-white border border-brand-border rounded-2xl p-4 sm:p-6 lg:p-7 shadow-xs">
            <div class="mb-4 sm:mb-6 flex flex-wrap items-center justify-between gap-2 border-b border-brand-border/60 pb-4">
                <div>
                    <h3 class="text-sm sm:text-base font-heading font-extrabold text-brand-dark flex items-center gap-2">
                        <span>🔥 Recommended Streetwear Drops</span>
                    </h3>
                    <p class="text-xs text-brand-muted mt-0.5">Handpicked oversized tees, cargo styles & urban drops for you.</p>
                </div>
                <a href="{{ route('frontend.products.index') }}" class="text-xs font-bold text-brand-dark hover:underline flex items-center gap-1 group">
                    <span>Explore All</span>
                    <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3.5 sm:gap-4.5">
                @foreach($accountRecommendedProducts as $recProduct)
                    @php
                        $defaultVariant = $recProduct->variants->where('stock', '>', 0)->first() ?? $recProduct->variants->first();
                    @endphp
                    <div class="group bg-white border border-brand-border/80 rounded-2xl overflow-hidden hover:border-brand-dark hover:shadow-md transition-all duration-200 flex flex-col justify-between"
                         x-data="{
                             selectedVariantId: '{{ $defaultVariant?->id ?? '' }}',
                             loading: false,
                             async addToBag() {
                                 if (!this.selectedVariantId) return;
                                 this.loading = true;
                                 try {
                                     let res = await fetch('{{ route('frontend.cart.add') }}', {
                                         method: 'POST',
                                         headers: {
                                             'Content-Type': 'application/json',
                                             'Accept': 'application/json',
                                             'X-Requested-With': 'XMLHttpRequest',
                                             'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                         },
                                         body: JSON.stringify({ variant_id: this.selectedVariantId, quantity: 1 })
                                     });
                                     let data = await res.json();
                                     if (data.success) {
                                         if (window.showToast) window.showToast('✓ Added to Bag!', 'success');
                                         window.dispatchEvent(new CustomEvent('cart-updated'));
                                     } else {
                                         if (window.showToast) window.showToast(data.message || 'Could not add to bag', 'error');
                                     }
                                 } catch(e) {
                                     if (window.showToast) window.showToast('Something went wrong', 'error');
                                 }
                                 this.loading = false;
                             }
                         }">
                        
                        {{-- Product Image --}}
                        <div class="aspect-[4/5] w-full overflow-hidden bg-brand-off-white relative">
                            <a href="{{ route('frontend.products.show', $recProduct->slug) }}" class="block w-full h-full">
                                @if($recProduct->primaryImage)
                                    <img src="{{ $recProduct->primaryImage->url }}" alt="{{ $recProduct->name }}" class="h-full w-full object-cover object-center group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="h-full w-full flex items-center justify-center text-xs text-brand-muted">No Image</div>
                                @endif
                            </a>

                            @if($recProduct->discount_percent > 0)
                                <span class="absolute top-2.5 left-2.5 bg-brand-dark text-white text-[10px] font-extrabold px-2 py-0.5 rounded-md shadow-sm uppercase tracking-wider">
                                    {{ $recProduct->discount_percent }}% OFF
                                </span>
                            @endif
                        </div>

                        {{-- Product Body & Direct Add CTA --}}
                        <div class="p-3 sm:p-3.5 flex flex-col justify-between flex-1 bg-white">
                            <div>
                                <h4 class="text-xs sm:text-sm font-bold text-brand-dark line-clamp-1 leading-snug">
                                    <a href="{{ route('frontend.products.show', $recProduct->slug) }}" class="hover:underline">
                                        {{ $recProduct->name }}
                                    </a>
                                </h4>
                                
                                <div class="flex items-baseline gap-1.5 mt-1">
                                    <span class="text-xs sm:text-sm font-extrabold text-brand-dark">₹{{ number_format($recProduct->price) }}</span>
                                    @if($recProduct->compare_price > $recProduct->price)
                                        <span class="text-[10px] text-brand-muted line-through">₹{{ number_format($recProduct->compare_price) }}</span>
                                    @endif
                                </div>

                                {{-- Size Selector --}}
                                @if($recProduct->variants->whereNotNull('size')->count() > 1)
                                    <div class="mt-2.5 pt-2 border-t border-brand-border/60">
                                        <span class="text-[9px] font-bold text-brand-muted uppercase tracking-wider block mb-1">Select Size:</span>
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($recProduct->variants->whereNotNull('size') as $v)
                                                <button type="button" 
                                                        @click="selectedVariantId = '{{ $v->id }}'"
                                                        class="text-[10px] font-bold min-w-[24px] h-6 px-1 rounded-md border transition-all flex items-center justify-center cursor-pointer"
                                                        :class="selectedVariantId == '{{ $v->id }}' ? 'bg-brand-dark text-white border-brand-dark shadow-2xs' : 'bg-brand-off-white text-brand-dark border-brand-border hover:border-brand-muted'">
                                                    {{ $v->size }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- 1-Tap Add to Bag --}}
                            <div class="mt-3 pt-2 border-t border-brand-border/40">
                                <button type="button" 
                                        @click="addToBag()" 
                                        :disabled="loading || !selectedVariantId"
                                        class="w-full bg-brand-dark text-white hover:bg-brand-text font-bold uppercase tracking-wider text-[10px] sm:text-[11px] py-2 sm:py-2.5 px-2 rounded-xl flex items-center justify-center gap-1.5 shadow-2xs active:scale-[0.98] transition-all cursor-pointer disabled:opacity-50">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                    <span x-text="loading ? 'Adding...' : 'Add to Bag'"></span>
                                </button>
                            </div>

                        </div>

                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
