@php
    $primaryImg = $product->primaryImage ?? $product->images->first();
    $secondaryImg = $product->images->where('id', '!=', $primaryImg?->id)->first();
    $totalStock = $product->variants->sum('stock');
    $availableSizes = $product->variants->where('stock', '>', 0)->pluck('size')->filter()->unique();
    $isSoldOut = $product->variants->count() > 0 && $totalStock == 0;
@endphp

<div class="group relative flex flex-col justify-between bg-white rounded-2xl p-2.5 sm:p-3 border border-brand-border/60 hover:border-brand-dark/40 hover:shadow-xl transition-all duration-500">
    
    {{-- Image Container --}}
    <div class="relative w-full aspect-[3/4] bg-brand-light rounded-xl overflow-hidden mb-3">
        
        <a href="{{ route('frontend.products.show', $product->slug) }}" class="block w-full h-full">
            {{-- Primary Image --}}
            @if($primaryImg)
                <img src="{{ $primaryImg->url }}" 
                     alt="{{ $product->name }}" 
                     class="w-full h-full object-cover object-center transition-all duration-700 ease-out {{ $secondaryImg ? 'group-hover:opacity-0 group-hover:scale-105' : 'group-hover:scale-105' }}" 
                     loading="lazy">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400 font-bold text-sm">
                    TX DROP
                </div>
            @endif

            {{-- Secondary Image Flip --}}
            @if($secondaryImg)
                <img src="{{ $secondaryImg->url }}" 
                     alt="{{ $product->name }} Alternate" 
                     class="absolute inset-0 w-full h-full object-cover object-center opacity-0 group-hover:opacity-100 group-hover:scale-105 transition-all duration-700 ease-out" 
                     loading="lazy">
            @endif
        </a>

        {{-- Badges on Top Left --}}
        <div class="absolute top-2.5 left-2.5 flex flex-col gap-1.5 z-10 pointer-events-none">
            @if($isSoldOut)
                <span class="bg-black/90 text-white text-[9px] font-black px-2 py-0.5 uppercase tracking-wider rounded-md backdrop-blur-xs shadow-md">
                    Sold Out
                </span>
            @elseif($product->discount_percent > 0)
                <span class="bg-rose-600 text-white text-[9px] font-black px-2 py-0.5 uppercase tracking-wider rounded-md shadow-md">
                    {{ $product->discount_percent }}% OFF
                </span>
            @elseif($product->is_featured)
                <span class="bg-amber-500 text-black text-[9px] font-black px-2 py-0.5 uppercase tracking-wider rounded-md shadow-md">
                    ★ Drop
                </span>
            @endif
        </div>

        {{-- Wishlist Quick Action (Top Right) --}}
        <div class="absolute top-2.5 right-2.5 z-20">
            @auth
                @php
                    $inWishlist = auth()->user()->wishlists()->where('product_id', $product->id)->exists();
                @endphp
                <form action="{{ route('account.wishlist.toggle') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" 
                            class="w-8 h-8 rounded-full bg-white/90 backdrop-blur-xs flex items-center justify-center transition-all shadow-md active:scale-90 hover:scale-110 cursor-pointer {{ $inWishlist ? 'text-rose-500' : 'text-slate-400 hover:text-rose-500' }}" 
                            title="{{ $inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
                        <svg class="w-4 h-4 {{ $inWishlist ? 'fill-current' : 'fill-none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                    </button>
                </form>
            @else
                <a href="{{ route('auth.login') }}" 
                   class="w-8 h-8 rounded-full bg-white/90 backdrop-blur-xs flex items-center justify-center text-slate-400 hover:text-rose-500 transition-all shadow-md active:scale-90 hover:scale-110" 
                   title="Login to Wishlist">
                    <svg class="w-4 h-4 fill-none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                </a>
            @endauth
        </div>

        {{-- Sizes Availability Strip on Bottom (Revealed on Hover) --}}
        @if($availableSizes->count() > 0)
            <div class="absolute bottom-2.5 inset-x-2.5 z-10 opacity-0 translate-y-2 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 pointer-events-none">
                <div class="bg-black/85 backdrop-blur-xs text-white text-[10px] font-extrabold py-1.5 px-2 rounded-lg text-center shadow-lg truncate">
                    Sizes: {{ $availableSizes->take(5)->join(' • ') }}
                </div>
            </div>
        @endif

    </div>

    {{-- Product Meta Details --}}
    <div class="flex-1 flex flex-col justify-between px-1">
        <div>
            <div class="flex items-center justify-between text-[11px] text-brand-muted font-bold uppercase tracking-wider mb-1">
                <span>{{ $product->category->name ?? 'Streetwear' }}</span>
                @if($product->review_count > 0)
                    <span class="flex items-center gap-0.5 text-amber-500 font-extrabold text-[10px]">
                        ★ {{ number_format($product->average_rating, 1) }}
                    </span>
                @endif
            </div>

            <a href="{{ route('frontend.products.show', $product->slug) }}" class="block">
                <h3 class="text-xs sm:text-sm font-extrabold text-brand-dark line-clamp-1 group-hover:text-brand-muted transition-colors">
                    {{ $product->name }}
                </h3>
            </a>
        </div>

        {{-- Pricing --}}
        <div class="flex items-center gap-2 mt-2 pt-2 border-t border-brand-border/40 flex-wrap">
            <span class="font-extrabold text-sm sm:text-base text-brand-dark">
                ₹{{ number_format($product->price) }}
            </span>
            @if($product->compare_price && $product->compare_price > $product->price)
                <span class="text-xs text-brand-muted line-through font-medium">
                    ₹{{ number_format($product->compare_price) }}
                </span>
            @endif
        </div>
    </div>

</div>
