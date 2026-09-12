@extends('frontend.layouts.account')

@section('account_content')
<div class="bg-white border border-brand-border rounded-2xl p-4 sm:p-6 lg:p-7 shadow-xs">
    
    {{-- Header --}}
    <div class="mb-5 sm:mb-6 flex flex-wrap items-center justify-between gap-2 border-b border-brand-border/60 pb-4">
        <div>
            <h2 class="text-base sm:text-lg font-heading font-extrabold text-brand-dark flex items-center gap-2">
                <span>My Wishlist</span>
                <span class="text-rose-500">❤️</span>
            </h2>
            <p class="text-xs sm:text-sm text-brand-muted mt-0.5">Saved items you can easily move to your bag anytime.</p>
        </div>
        @if($wishlists->count() > 0)
            <span class="text-xs font-extrabold bg-brand-off-white border border-brand-border px-3 py-1 rounded-full text-brand-dark shadow-2xs">
                {{ $wishlists->total() }} {{ Str::plural('Item', $wishlists->total()) }}
            </span>
        @endif
    </div>

    @if($wishlists->count() > 0)
        {{-- Responsive Grid: 1 col on mobile, 2 on sm, 3 on md/lg --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-3.5 sm:gap-5">
            @foreach($wishlists as $wishlist)
                @php
                    $product = $wishlist->product;
                    if (!$product) {
                        continue;
                    }
                    $variants = $product->variants ?? collect();
                    $defaultVariant = $variants->where('stock', '>', 0)->first() ?? $variants->first();
                    $primaryImg = $product->primaryImage ?? $product->images->first();
                    $productPrice = (float) ($product->price ?? 0);
                    $comparePrice = (float) ($product->compare_price ?? 0);
                    $discountPercent = (int) ($product->discount_percent ?? 0);
                    $sizeVariants = $variants->whereNotNull('size');
                @endphp
                <div class="group relative bg-white border border-brand-border/80 rounded-2xl overflow-hidden hover:border-brand-dark hover:shadow-md transition-all duration-200 flex flex-col justify-between"
                     x-data="{
                         selectedVariantId: '{{ $defaultVariant?->id ?? '' }}',
                         loading: false,
                         removed: false,
                         async moveToBag() {
                             if (!this.selectedVariantId) return;
                             this.loading = true;
                             try {
                                 let res = await fetch('{{ route('frontend.cart.moveFromWishlist', $wishlist->id) }}', {
                                     method: 'POST',
                                     headers: {
                                         'Content-Type': 'application/json',
                                         'Accept': 'application/json',
                                         'X-Requested-With': 'XMLHttpRequest',
                                         'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                     },
                                     body: JSON.stringify({ variant_id: this.selectedVariantId })
                                 });
                                 let data = await res.json();
                                 if (data.success) {
                                     if (window.showToast) window.showToast('✓ Added to Bag!', 'success');
                                     this.removed = true;
                                     
                                     // Dispatch global cart event to refresh cart drawer and counter
                                     window.dispatchEvent(new CustomEvent('cart-updated'));
                                     
                                     // Reload after short delay if entire page needs sync
                                     setTimeout(() => {
                                         window.location.reload();
                                     }, 500);
                                 } else {
                                     if (window.showToast) window.showToast(data.message || 'Could not add to bag', 'error');
                                 }
                             } catch(e) {
                                 console.error(e);
                                 if (window.showToast) window.showToast('Something went wrong', 'error');
                             }
                             this.loading = false;
                         }
                     }"
                     x-show="!removed"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95">
                    
                    {{-- Product Image & Floating Badges --}}
                    <div class="aspect-[4/5] w-full overflow-hidden bg-brand-off-white relative">
                        <a href="{{ route('frontend.products.show', $product->slug) }}" class="block w-full h-full">
                            @if($primaryImg)
                                <img src="{{ $primaryImg->url }}" alt="{{ $product->name }}" class="h-full w-full object-cover object-center group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="h-full w-full flex items-center justify-center text-xs text-brand-muted">No Image</div>
                            @endif
                        </a>

                        {{-- Floating Discount Pill --}}
                        @if($discountPercent > 0)
                            <span class="absolute top-2.5 left-2.5 bg-brand-dark text-white text-[10px] font-extrabold px-2 py-0.5 rounded-md shadow-sm uppercase tracking-wider">
                                {{ $discountPercent }}% OFF
                            </span>
                        @endif

                        {{-- Floating Remove Button --}}
                        <form action="{{ route('account.wishlist.toggle') }}" method="POST" class="absolute top-2.5 right-2.5 z-10">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $wishlist->product_id }}">
                            <button type="submit" class="w-8 h-8 bg-white/95 backdrop-blur-sm rounded-full text-red-500 shadow-sm hover:bg-white flex items-center justify-center transition-transform active:scale-90 hover:scale-105 cursor-pointer" title="Remove from wishlist">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z"/></svg>
                            </button>
                        </form>
                    </div>

                    {{-- Product Body --}}
                    <div class="p-3.5 sm:p-4 flex flex-col justify-between flex-1 bg-white">
                        <div>
                            {{-- Title --}}
                            <h3 class="text-xs sm:text-sm font-bold text-brand-dark line-clamp-1 leading-snug">
                                <a href="{{ route('frontend.products.show', $product->slug) }}" class="hover:underline">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            
                            {{-- Price & Savings --}}
                            <div class="flex items-baseline gap-2 mt-1.5">
                                <span class="text-sm sm:text-base font-extrabold font-heading text-brand-dark">₹{{ number_format($productPrice) }}</span>
                                @if($comparePrice > $productPrice)
                                    <span class="text-xs text-brand-muted line-through">₹{{ number_format($comparePrice) }}</span>
                                    <span class="text-[10px] font-extrabold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">
                                        Save ₹{{ number_format($comparePrice - $productPrice) }}
                                    </span>
                                @endif
                            </div>

                            {{-- Size Selector (if multiple sizes exist) --}}
                            @if($sizeVariants->count() > 1)
                                <div class="mt-3 pt-2.5 border-t border-brand-border/60">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <span class="text-[10px] font-bold text-brand-muted uppercase tracking-wider">Select Size</span>
                                    </div>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($sizeVariants as $v)
                                            <button type="button" 
                                                    @click="selectedVariantId = '{{ $v->id }}'"
                                                    class="text-[11px] font-bold min-w-[30px] sm:min-w-[34px] h-7 sm:h-7.5 px-2 rounded-lg border transition-all flex items-center justify-center cursor-pointer active:scale-95"
                                                    :class="selectedVariantId == '{{ $v->id }}' ? 'bg-brand-dark text-white border-brand-dark shadow-xs' : 'bg-brand-off-white text-brand-dark border-brand-border hover:border-brand-muted hover:bg-white'">
                                                {{ $v->size }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Move to Bag CTA --}}
                        <div class="mt-3.5 pt-2 border-t border-brand-border/40">
                            <button type="button" 
                                    @click="moveToBag()" 
                                    :disabled="loading || !selectedVariantId"
                                    class="w-full bg-brand-dark text-white hover:bg-brand-text font-bold uppercase tracking-wider text-xs py-2.5 sm:py-3 px-3 rounded-xl flex items-center justify-center gap-2 shadow-sm hover:shadow active:scale-[0.98] transition-all cursor-pointer disabled:opacity-50">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/></svg>
                                <span x-text="loading ? 'Adding to Bag...' : 'Move to Bag'"></span>
                            </button>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($wishlists->hasPages())
            <div class="mt-8 pt-4 border-t border-brand-border/60">
                {{ $wishlists->links() }}
            </div>
        @endif

    @else
        <div class="text-center py-12 sm:py-16 border border-dashed border-brand-border rounded-2xl bg-brand-off-white/30">
            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-white border border-brand-border flex items-center justify-center mx-auto mb-3 shadow-2xs">
                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-brand-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                </svg>
            </div>
            <h3 class="text-sm sm:text-base font-bold text-brand-dark">Your Wishlist is Empty</h3>
            <p class="mt-1 text-xs text-brand-muted max-w-xs mx-auto">Explore our newest streetwear collections and save your favorite styles.</p>
            <div class="mt-5">
                <a href="{{ route('frontend.products.index') }}" class="inline-flex items-center gap-2 bg-brand-dark text-white text-xs font-bold uppercase tracking-wider px-5 py-2.5 rounded-xl hover:bg-brand-text transition-all shadow-xs active:scale-95">
                    <span>Discover Styles</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
