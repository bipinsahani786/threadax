@extends('frontend.layouts.app')

@section('title', $product->name . ' - ThreadAx')
@section('meta_description', Str::limit(strip_tags($product->description), 150))
@section('meta_image', $product->primaryImage ? $product->primaryImage->url : asset('images/banner-men.png'))

@push('head')
    {{-- Schema.org Product Structured Data for Google Rich Snippets --}}
    @php
        $primaryImg = $product->primaryImage ?? $product->images->first();
        $imagesList = $product->images->map(fn($img) => $img->url)->filter()->values()->all();
        if (empty($imagesList) && $primaryImg?->url) {
            $imagesList = [$primaryImg->url];
        }
        $inStock = $product->variants->sum('stock') > 0;
        $minPrice = (float) ($product->price ?? 0);
        
        $productSchema = [
            '@context' => 'https://schema.org/',
            '@type' => 'Product',
            'name' => $product->name,
            'image' => !empty($imagesList) ? $imagesList : [asset('images/hero-full.png')],
            'description' => Str::limit(strip_tags($product->description ?? ''), 200),
            'sku' => $product->effective_sku,
            'brand' => [
                '@type' => 'Brand',
                'name' => 'ThreadAX',
            ],
            'offers' => [
                '@type' => 'Offer',
                'url' => route('frontend.products.show', $product->slug),
                'priceCurrency' => 'INR',
                'price' => number_format($minPrice, 2, '.', ''),
                'priceValidUntil' => now()->addMonths(6)->toDateString(),
                'itemCondition' => 'https://schema.org/NewCondition',
                'availability' => $inStock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'seller' => [
                    '@type' => 'Organization',
                    'name' => 'ThreadAX',
                ],
            ],
        ];

        $breadcrumbSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Home',
                    'item' => route('frontend.home'),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => 'Shop',
                    'item' => route('frontend.products.index'),
                ],
            ],
        ];

        if ($product->category) {
            $breadcrumbSchema['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $product->category->name,
                'item' => route('frontend.products.index', ['category' => $product->category->slug]),
            ];
            $breadcrumbSchema['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => 4,
                'name' => $product->name,
                'item' => route('frontend.products.show', $product->slug),
            ];
        } else {
            $breadcrumbSchema['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $product->name,
                'item' => route('frontend.products.show', $product->slug),
            ];
        }
    @endphp
    <script type="application/ld+json">
    {!! json_encode($productSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
    <script type="application/ld+json">
    {!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
@endpush

@push('styles')
<style>
    /* Premium Product Details Rich Typography */
    .product-details-content {
        color: #475569;
        font-size: 0.875rem;
        line-height: 1.75;
    }
    .product-details-content p {
        margin-bottom: 0.85rem;
    }
    .product-details-content p:last-child {
        margin-bottom: 0;
    }
    .product-details-content h2,
    .product-details-content h3,
    .product-details-content h4 {
        font-family: 'Space Grotesk', sans-serif;
        font-weight: 800;
        color: #0F172A;
        margin-top: 1.25rem;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        font-size: 0.85rem;
    }
    .product-details-content h2:first-child,
    .product-details-content h3:first-child,
    .product-details-content h4:first-child {
        margin-top: 0;
    }
    .product-details-content ul {
        list-style-type: disc !important;
        padding-left: 1.25rem !important;
        margin-top: 0.5rem;
        margin-bottom: 1rem;
    }
    .product-details-content ol {
        list-style-type: decimal !important;
        padding-left: 1.25rem !important;
        margin-top: 0.5rem;
        margin-bottom: 1rem;
    }
    .product-details-content li {
        margin-bottom: 0.35rem;
        line-height: 1.6;
        color: #334155;
    }
    .product-details-content strong,
    .product-details-content b {
        font-weight: 700;
        color: #0F172A;
    }
    .product-details-content blockquote {
        border-left: 3px solid #0F172A;
        background-color: #F8FAFC;
        padding: 0.75rem 1rem;
        margin: 1rem 0;
        border-radius: 0 10px 10px 0;
        font-style: italic;
        color: #334155;
    }
    .product-details-content a {
        color: #0F172A;
        font-weight: 600;
        text-decoration: underline;
        text-underline-offset: 3px;
    }
    .product-details-content a:hover {
        color: #64748B;
    }
</style>
@endpush

@section('content')
    <div class="max-w-[1440px] mx-auto px-4 lg:px-8 py-10" x-data="productDetails({{ Js::from($product->variants) }}, {{ $product->price }})">
        
        {{-- Breadcrumbs --}}
        <nav class="text-xs font-bold uppercase tracking-widest text-brand-muted mb-8 flex flex-wrap gap-2">
            <a href="{{ route('frontend.home') }}" class="hover:text-brand-text transition-colors">Home</a>
            <span>/</span>
            <a href="{{ route('frontend.products.index') }}" class="hover:text-brand-text transition-colors">Shop</a>
            <span>/</span>
            @if($product->category)
                <a href="{{ route('frontend.products.index', ['category' => $product->category->id]) }}" class="hover:text-brand-text transition-colors">{{ $product->category->name }}</a>
                <span>/</span>
            @endif
            <span class="text-brand-text">{{ $product->name }}</span>
        </nav>

        <div class="flex flex-col lg:flex-row gap-12 lg:gap-20">
            
            {{-- Left: Image Gallery --}}
            <div class="w-full lg:w-[55%] flex flex-col-reverse md:flex-row gap-4" x-data="{ 
                activeMedia: 'image',
                mainImage: '{{ $product->primary_image ? $product->primary_image->url : '' }}',
                lightboxOpen: false
            }">
                
                {{-- Thumbnails --}}
                <div class="flex md:flex-col gap-4 overflow-x-auto md:overflow-y-auto md:w-24 shrink-0 no-scrollbar">
                    @foreach($product->images as $image)
                        <button @click="activeMedia = 'image'; mainImage = '{{ $image->url }}'" class="w-20 md:w-full aspect-[3/4] bg-brand-light cursor-pointer border-2 transition-colors shrink-0" :class="activeMedia === 'image' && mainImage === '{{ $image->url }}' ? 'border-brand-text' : 'border-transparent hover:border-brand-muted'">
                            <img src="{{ $image->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        </button>
                    @endforeach

                    @if($product->video_url)
                        <button @click="activeMedia = 'video'" class="w-20 md:w-full aspect-[3/4] cursor-pointer border-2 transition-colors shrink-0 relative group overflow-hidden" :class="activeMedia === 'video' ? 'border-brand-text' : 'border-transparent hover:border-brand-muted'">
                            <img src="{{ $product->primary_image ? $product->primary_image->url : asset('images/hero-full.png') }}" class="w-full h-full object-cover opacity-70 group-hover:opacity-50 transition-opacity">
                            <div class="absolute inset-0 flex flex-col items-center justify-center gap-1 text-white bg-black/30">
                                <svg class="w-8 h-8 drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                        </button>
                    @endif

                    @if($product->model_3d_url)
                        <button @click="activeMedia = '3d'" class="w-20 md:w-full aspect-[3/4] cursor-pointer border-2 transition-colors shrink-0 relative group overflow-hidden" :class="activeMedia === '3d' ? 'border-brand-text' : 'border-transparent hover:border-brand-muted'">
                            <img src="{{ $product->primary_image ? $product->primary_image->url : asset('images/hero-full.png') }}" class="w-full h-full object-cover opacity-70 group-hover:opacity-50 transition-opacity">
                            <div class="absolute inset-0 flex flex-col items-center justify-center gap-1 text-white bg-black/30">
                                <svg class="w-8 h-8 drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><path stroke-linecap="round" stroke-linejoin="round" d="M3.27 6.96L12 12.01l8.73-5.05M12 22.08V12"/></svg>
                            </div>
                        </button>
                    @endif
                </div>
                
                {{-- Main Media Area --}}
                <div class="w-full bg-brand-light aspect-[3/4] md:aspect-auto md:h-[800px] overflow-hidden relative">
                    
                    {{-- Main Image with Click for Lightbox --}}
                    <div x-show="activeMedia === 'image'" class="absolute inset-0 group">
                        
                        <template x-if="mainImage">
                            <div class="w-full h-full relative cursor-zoom-in" @click="lightboxOpen = true">
                                <img :src="mainImage" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                <div class="absolute bottom-4 right-4 bg-white/90 p-2 rounded-full shadow-lg opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                                    <svg class="w-5 h-5 text-brand-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                                </div>
                            </div>
                        </template>
                        <template x-if="!mainImage">
                            <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                <span class="text-gray-400">No Image Available</span>
                            </div>
                        </template>
                    </div>

                    {{-- Video Player --}}
                    @if($product->video_url)
                        <div x-show="activeMedia === 'video'" class="absolute inset-0 bg-black flex items-center justify-center">
                            @if(str_contains($product->video_url, 'youtube.com') || str_contains($product->video_url, 'youtu.be'))
                                @php 
                                    // Basic YouTube URL parsing
                                    $ytUrl = $product->video_url;
                                    if(str_contains($ytUrl, 'watch?v=')) $ytUrl = str_replace('watch?v=', 'embed/', $ytUrl);
                                    if(str_contains($ytUrl, 'youtu.be/')) $ytUrl = str_replace('youtu.be/', 'youtube.com/embed/', $ytUrl);
                                @endphp
                                <iframe src="{{ $ytUrl }}" class="w-full h-full" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            @else
                                <video controls class="w-full max-h-full">
                                    <source src="{{ $product->video_url }}" type="video/mp4">
                                </video>
                            @endif
                        </div>
                    @endif

                    {{-- 3D Model Viewer --}}
                    @if($product->model_3d_url)
                        <div x-show="activeMedia === '3d'" class="absolute inset-0 bg-brand-light flex items-center justify-center">
                            <model-viewer src="{{ $product->model_3d_url }}" auto-rotate camera-controls shadow-intensity="1" style="width: 100%; height: 100%; --poster-color: transparent;"></model-viewer>
                        </div>
                    @endif
                </div>

                {{-- Lightbox / Outer Zoom Modal --}}
                <div x-show="lightboxOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 backdrop-blur-sm p-4 md:p-8" @keydown.escape.window="lightboxOpen = false">
                    <button @click="lightboxOpen = false" class="absolute top-6 right-6 text-white hover:text-gray-300 transition-colors z-50">
                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    <div class="w-full h-full max-w-5xl max-h-screen relative flex items-center justify-center" @click.away="lightboxOpen = false">
                        <img :src="mainImage" alt="Zoomed Product Image" class="max-w-full max-h-full object-contain cursor-zoom-out" @click="lightboxOpen = false">
                    </div>
                </div>
            </div>

            {{-- Right: Product Info --}}
            <div class="w-full lg:w-[45%] flex flex-col pt-4">
                
                <h1 class="text-3xl md:text-4xl font-heading font-extrabold text-brand-dark mb-2">{{ $product->name }}</h1>
                @if($product->short_description)
                    <p class="text-xs sm:text-sm font-medium text-slate-500 mb-2.5 leading-relaxed tracking-wide">
                        {{ $product->short_description }}
                    </p>
                @endif
                <p class="text-sm text-brand-muted mb-6">SKU: <span x-text="currentSku || '{{ $product->sku ?? 'N/A' }}'"></span></p>
                
                <div class="flex items-end gap-4 mb-8">
                    <span class="text-2xl font-bold text-brand-text" x-text="formatPrice(currentPrice)">₹{{ number_format($product->price) }}</span>
                    @if($product->compare_price && $product->compare_price > $product->price)
                        <span class="text-lg text-brand-muted line-through mb-1">₹{{ number_format($product->compare_price) }}</span>
                        <span class="text-sm font-bold text-green-600 mb-1.5 uppercase tracking-wide">
                            {{ round((($product->compare_price - $product->price) / $product->compare_price) * 100) }}% Off
                        </span>
                    @endif
                </div>

                {{-- Variants Selection --}}
                @if($product->variants->count() > 0)
                    
                    {{-- Size Selector --}}
                    <div class="mb-6 sm:mb-8" x-show="availableSizes.length > 0">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold uppercase tracking-wider text-brand-dark">Select Size</span>
                            <button type="button" @click="sizeGuideOpen = true" class="text-xs font-bold text-brand-dark underline hover:text-brand-muted flex items-center gap-1 cursor-pointer">
                                <span>📏 Size Guide</span>
                            </button>
                        </div>
                        <div class="flex flex-wrap gap-2.5">
                            <template x-for="size in availableSizes" :key="size">
                                <button type="button" 
                                    @click="selectSize(size)"
                                    class="h-11 min-w-[3.2rem] px-4 rounded-xl border text-xs sm:text-sm font-bold transition-all shadow-xs active:scale-95 cursor-pointer"
                                    :class="selectedSize === size ? 'border-brand-dark bg-brand-dark text-white shadow-sm' : 'border-brand-border bg-white text-brand-dark hover:border-brand-dark/50'"
                                    x-text="size">
                                </button>
                            </template>
                        </div>
                        <p x-show="showSizeError" class="text-red-500 text-xs mt-2 font-bold transition-opacity">Please select a size.</p>
                    </div>

                    {{-- Color Selector --}}
                    <div class="mb-6 sm:mb-8" x-show="availableColors.length > 0">
                        <span class="text-xs font-bold uppercase tracking-wider text-brand-dark block mb-3">Select Color <span x-show="selectedColor" class="text-brand-muted normal-case ml-2" x-text="'- ' + selectedColor"></span></span>
                        <div class="flex flex-wrap gap-2.5">
                            <template x-for="color in availableColors" :key="color">
                                <button type="button" 
                                    @click="selectColor(color)"
                                    class="h-11 px-4 rounded-xl border text-xs sm:text-sm font-bold transition-all shadow-xs active:scale-95 cursor-pointer"
                                    :class="selectedColor === color ? 'border-brand-dark bg-brand-dark text-white shadow-sm' : 'border-brand-border bg-white text-brand-dark hover:border-brand-dark/50'"
                                    x-text="color">
                                </button>
                            </template>
                        </div>
                        <p x-show="showColorError" class="text-red-500 text-xs mt-2 font-bold transition-opacity">Please select a color.</p>
                    </div>
                @endif

                {{-- Stock Status & Urgency Banner --}}
                <div class="mb-6">
                    <template x-if="selectedVariant">
                        <div>
                            <template x-if="selectedVariant.stock > 5">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span>In Stock (Ready to dispatch)</span>
                                </div>
                            </template>
                            <template x-if="selectedVariant.stock > 0 && selectedVariant.stock <= 5">
                                <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-xs font-bold text-amber-800 flex items-center gap-2.5">
                                    <span class="text-base">⚡</span>
                                    <span>High Demand: Only <strong class="text-amber-950 underline" x-text="selectedVariant.stock"></strong> drops left in stock!</span>
                                </div>
                            </template>
                            <template x-if="selectedVariant.stock <= 0">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-50 text-rose-700 text-xs font-bold border border-rose-200">
                                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                    <span>Out of Stock in this variant</span>
                                </div>
                            </template>
                        </div>
                    </template>
                    <template x-if="!selectedVariant && variants.length === 0">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>In Stock (Ships in 24-48 Hours)</span>
                        </div>
                    </template>
                </div>

                {{-- Action Buttons Row --}}
                <div id="main-buy-row" class="flex items-center gap-2.5 sm:gap-3.5 mb-8 border-t border-brand-border pt-6 sm:pt-8">
                    {{-- Add to Cart Form --}}
                    <form action="#" method="POST" @submit.prevent="addToCart" class="flex flex-1 items-center gap-2.5 sm:gap-3.5 min-w-0">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="variant_id" :value="selectedVariant ? selectedVariant.id : ''">
                        
                        {{-- Quantity Pill --}}
                        <div class="w-24 sm:w-28 h-12 bg-brand-off-white border border-brand-border rounded-xl flex items-center justify-between px-2.5 shrink-0 shadow-xs">
                            <button type="button" @click="quantity > 1 ? quantity-- : null" class="w-7 h-7 rounded-lg flex items-center justify-center text-brand-dark hover:bg-white hover:shadow-xs active:scale-95 transition-all text-base font-bold cursor-pointer" aria-label="Decrease quantity">&minus;</button>
                            <span class="text-xs sm:text-sm font-extrabold text-brand-dark font-heading w-6 text-center select-none" x-text="quantity"></span>
                            <button type="button" @click="quantity++" class="w-7 h-7 rounded-lg flex items-center justify-center text-brand-dark hover:bg-white hover:shadow-xs active:scale-95 transition-all text-base font-bold cursor-pointer" aria-label="Increase quantity">&plus;</button>
                            <input type="hidden" name="quantity" :value="quantity">
                        </div>
                        
                        {{-- Add to Bag Button --}}
                        <button type="submit" 
                            class="flex-1 h-12 bg-brand-dark text-white text-xs sm:text-sm font-bold uppercase tracking-wider hover:bg-brand-text active:scale-[0.99] rounded-xl transition-all shadow-sm hover:shadow-md flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed whitespace-nowrap px-4 sm:px-6 cursor-pointer min-w-0"
                            :disabled="!canAddToCart">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            <span x-text="buttonText" class="truncate"></span>
                        </button>
                    </form>

                    {{-- Wishlist Toggle Form --}}
                    @auth
                        @php
                            $inWishlist = auth()->user()->wishlists()->where('product_id', $product->id)->exists();
                        @endphp
                        <form action="{{ route('account.wishlist.toggle') }}" method="POST" class="shrink-0">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="w-12 h-12 rounded-xl border flex items-center justify-center transition-all shadow-xs hover:shadow-sm active:scale-95 cursor-pointer {{ $inWishlist ? 'border-red-200 bg-red-50/60 text-red-500 hover:bg-red-100/60' : 'border-brand-border bg-white text-brand-muted hover:text-red-500 hover:border-red-200 hover:bg-red-50/30' }}" title="{{ $inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
                                <svg class="w-5 h-5 {{ $inWishlist ? 'fill-current' : 'fill-none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('auth.login') }}" class="w-12 h-12 rounded-xl border border-brand-border flex items-center justify-center text-brand-muted hover:text-red-500 hover:border-red-200 hover:bg-red-50/30 transition-all bg-white shrink-0 shadow-xs hover:shadow-sm active:scale-95" title="Login to Add to Wishlist">
                            <svg class="w-5 h-5 fill-none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                        </a>
                    @endauth
                </div>

                {{-- Accordions for details --}}
                <div class="border-t border-brand-border divide-y divide-brand-border" x-data="{ activeTab: 1 }">
                    {{-- Description --}}
                    <div>
                        <button @click="activeTab = activeTab === 1 ? null : 1" class="w-full py-6 flex items-center justify-between text-left focus:outline-none group cursor-pointer">
                            <span class="text-sm font-bold uppercase tracking-widest text-brand-text">Description & Fit Details</span>
                            <svg class="w-5 h-5 text-brand-muted group-hover:text-brand-text transition-transform duration-300" :class="activeTab === 1 ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="activeTab === 1" x-collapse>
                            <div class="pb-6 text-sm text-brand-muted product-details-content leading-relaxed">
                                @if(strip_tags($product->description) === $product->description)
                                    {!! nl2br(e($product->description)) !!}
                                @else
                                    {!! $product->description !!}
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    {{-- Shipping & Returns --}}
                    <div>
                        <button @click="activeTab = activeTab === 2 ? null : 2" class="w-full py-6 flex items-center justify-between text-left focus:outline-none group">
                            <span class="text-sm font-bold uppercase tracking-widest text-brand-text">Shipping & Returns</span>
                            <svg class="w-5 h-5 text-brand-muted group-hover:text-brand-text transition-transform duration-300" :class="activeTab === 2 ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="activeTab === 2" x-collapse>
                            <div class="pb-6 text-sm text-brand-muted space-y-4 prose prose-sm max-w-none">
                                @php
                                    $shippingPolicy = \App\Models\Setting::where('key', 'shipping_policy')->value('value');
                                @endphp
                                @if($shippingPolicy)
                                    {!! nl2br($shippingPolicy) !!}
                                @else
                                    <p><strong class="text-brand-text">Free Shipping:</strong> On all orders above ₹999.</p>
                                    <p><strong class="text-brand-text">Delivery Time:</strong> Standard delivery within 3-5 business days. Metro cities within 1-2 business days.</p>
                                    <p><strong class="text-brand-text">Returns:</strong> Easy 7-day returns and exchanges. Product must be unwashed and unworn with original tags attached.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                </div>

            </div>
        </div>
    </div>

    {{-- ═══════════ CUSTOMER REVIEWS ═══════════ --}}
    <section class="max-w-[1440px] mx-auto px-4 lg:px-8 py-16 sm:py-24 border-t border-brand-border" id="reviews">
        <div class="flex flex-col md:flex-row gap-12">
            
            {{-- Review Summary & Form --}}
            <div class="md:w-1/3">
                <h2 class="text-2xl sm:text-3xl font-heading font-extrabold tracking-tight mb-4 uppercase">Customer Reviews</h2>
                <div class="flex items-center gap-4 mb-8">
                    <div class="text-4xl font-bold font-heading">{{ number_format($product->average_rating, 1) }}</div>
                    <div>
                        <div class="flex text-brand-dark mb-1">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= round($product->average_rating) ? 'fill-current' : 'text-gray-200 fill-current' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <p class="text-xs font-bold text-brand-muted uppercase tracking-widest">Based on {{ $product->review_count }} reviews</p>
                    </div>
                </div>

                @auth
                    @php
                        $hasPurchased = \App\Models\Order::where('user_id', Auth::id())
                            ->where('status', 'delivered')
                            ->whereHas('items', function($q) use ($product) {
                                $q->whereHas('variant', function($q2) use ($product) {
                                    $q2->where('product_id', $product->id);
                                });
                            })->exists();
                            
                        $hasReviewed = \App\Models\Review::where('user_id', Auth::id())
                            ->where('product_id', $product->id)->exists();
                    @endphp

                    @if($hasReviewed)
                        <div class="bg-brand-light border border-brand-border p-4 rounded text-sm text-brand-dark">
                            You have already submitted a review for this product. Thank you!
                        </div>
                    @elseif($hasPurchased)
                        <div class="bg-white border border-brand-border p-6" x-data="{ rating: 5, hoverRating: 0 }">
                            <h3 class="text-sm font-bold uppercase tracking-wider mb-4">Write a Review</h3>
                            <form action="{{ route('reviews.store', $product) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-xs font-bold text-brand-muted uppercase mb-2">Rating</label>
                                    <div class="flex gap-1 cursor-pointer" @mouseleave="hoverRating = 0">
                                        <template x-for="i in 5" :key="i">
                                            <svg @mouseenter="hoverRating = i" @click="rating = i"
                                                 class="w-6 h-6 transition-colors"
                                                 :class="(hoverRating ? hoverRating >= i : rating >= i) ? 'text-brand-dark fill-current' : 'text-gray-200 fill-current'"
                                                 viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        </template>
                                        <input type="hidden" name="rating" x-model="rating">
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-xs font-bold text-brand-muted uppercase mb-2">Title (Optional)</label>
                                    <input type="text" name="title" class="input-field w-full" placeholder="Summarize your experience">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-xs font-bold text-brand-muted uppercase mb-2">Review</label>
                                    <textarea name="body" rows="4" class="input-field w-full" required placeholder="What did you like or dislike?"></textarea>
                                </div>
                                <button type="submit" class="btn-primary w-full text-center block">Submit Review</button>
                            </form>
                        </div>
                    @else
                        <div class="bg-brand-light border border-brand-border p-4 rounded text-sm text-brand-dark">
                            You must purchase and receive this item before you can leave a review.
                        </div>
                    @endif
                @else
                    <div class="bg-brand-light border border-brand-border p-4 rounded text-sm text-brand-dark">
                        Please <a href="{{ route('auth.login') }}" class="underline font-bold">log in</a> to write a review.
                    </div>
                @endauth
            </div>

            {{-- Review List --}}
            <div class="md:w-2/3 md:pl-12">
                @if($product->approvedReviews->isNotEmpty())
                    <div class="divide-y divide-brand-border">
                        @foreach($product->approvedReviews as $review)
                            <div class="py-6 first:pt-0">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex text-brand-dark">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $review->rating ? 'fill-current' : 'text-gray-200 fill-current' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @endfor
                                    </div>
                                    <span class="text-xs text-brand-muted">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                @if($review->title)
                                    <h4 class="font-bold text-brand-dark text-sm uppercase tracking-wider mb-2">{{ $review->title }}</h4>
                                @endif
                                <p class="text-brand-muted text-sm leading-relaxed mb-3">{{ $review->body }}</p>
                                <p class="text-xs font-bold text-brand-dark uppercase tracking-widest">{{ $review->user->name }} <span class="text-brand-muted font-normal lowercase tracking-normal bg-brand-light px-2 py-0.5 rounded ml-2">Verified Buyer</span></p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="h-full flex flex-col justify-center items-center py-12 text-center border border-dashed border-brand-border rounded">
                        <svg class="w-12 h-12 text-brand-muted mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        <h3 class="text-lg font-bold text-brand-dark uppercase tracking-wider mb-1">No Reviews Yet</h3>
                        <p class="text-sm text-brand-muted max-w-sm">Be the first to share your experience with this product!</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- ═══════════ RELATED PRODUCTS ═══════════ --}}
    @if($relatedProducts->count() > 0)
        <section class="max-w-[1440px] mx-auto px-4 lg:px-8 py-16 sm:py-24 border-t border-brand-border">
            <h2 class="text-2xl sm:text-3xl font-heading font-extrabold tracking-tight mb-10">YOU MIGHT ALSO LIKE</h2>
            
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-8">
                @foreach($relatedProducts as $related)
                    <div class="group cursor-pointer" onclick="window.location='{{ route('frontend.products.show', $related->slug) }}'">
                        <div class="relative w-full aspect-[3/4] bg-brand-light overflow-hidden mb-4">
                            @if($related->primary_image)
                                <img src="{{ $related->primary_image->url }}" alt="{{ $related->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            @endif
                            @if($related->discount_percent > 0)
                                <div class="absolute top-3 left-3">
                                    <span class="inline-flex items-center gap-1 bg-gradient-to-r from-orange-500 to-red-500 text-white text-[10px] font-black px-2.5 py-1 uppercase tracking-wider rounded-sm shadow-lg">
                                        {{ $related->discount_percent }}% OFF
                                    </span>
                                </div>
                            @endif
                            <button class="absolute bottom-4 right-4 bg-white p-2 rounded-full shadow-lg opacity-0 translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 hover:bg-brand-text hover:text-white">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </button>
                        </div>
                        <h3 class="text-sm font-bold mb-1 line-clamp-1">{{ $related->name }}</h3>
                        <p class="font-semibold text-sm">₹{{ number_format($related->price) }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Universal Interactive Size Guide Modal --}}
    <div x-show="sizeGuideOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/85 backdrop-blur-md p-4" @keydown.escape.window="sizeGuideOpen = false" x-cloak>
        <div class="w-full max-w-2xl max-h-[90vh] bg-white rounded-2xl overflow-y-auto relative p-6 sm:p-8 shadow-2xl border border-slate-200" @click.away="sizeGuideOpen = false" x-data="{ unit: 'in' }">
            
            <div class="flex items-center justify-between border-b border-brand-border pb-4 mb-6">
                <div>
                    <h3 class="text-xl font-heading font-extrabold text-brand-dark uppercase">Streetwear Sizing Guide</h3>
                    <p class="text-xs text-brand-muted">Drop-shoulder relaxed oversized fit standard</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200 text-xs font-bold">
                        <button type="button" @click="unit = 'in'" class="px-3 py-1 rounded-lg transition-all" :class="unit === 'in' ? 'bg-black text-white shadow-xs' : 'text-slate-600 hover:text-black'">INCHES</button>
                        <button type="button" @click="unit = 'cm'" class="px-3 py-1 rounded-lg transition-all" :class="unit === 'cm' ? 'bg-black text-white shadow-xs' : 'text-slate-600 hover:text-black'">CM</button>
                    </div>
                    <button @click="sizeGuideOpen = false" class="text-slate-400 hover:text-black transition-colors p-1 cursor-pointer">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            @if($product->size_guide_url)
                <div class="mb-6 rounded-xl overflow-hidden border border-slate-200">
                    <img src="{{ $product->size_guide_url }}" alt="Size Guide" class="w-full h-auto">
                </div>
            @endif

            {{-- Interactive Measurement Table --}}
            <div class="overflow-x-auto rounded-xl border border-brand-border mb-6">
                <table class="w-full text-left text-xs">
                    <thead class="bg-brand-dark text-white uppercase text-[10px] font-black tracking-wider">
                        <tr>
                            <th class="p-3">Size</th>
                            <th class="p-3">Chest Width</th>
                            <th class="p-3">Body Length</th>
                            <th class="p-3">Shoulder Drop</th>
                            <th class="p-3">Sleeve Length</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-border font-medium text-brand-dark">
                        <tr class="hover:bg-brand-light/50 transition-colors">
                            <td class="p-3 font-extrabold">S (Small)</td>
                            <td class="p-3" x-text="unit === 'in' ? '42 in' : '107 cm'"></td>
                            <td class="p-3" x-text="unit === 'in' ? '28 in' : '71 cm'"></td>
                            <td class="p-3" x-text="unit === 'in' ? '21 in' : '53 cm'"></td>
                            <td class="p-3" x-text="unit === 'in' ? '8.5 in' : '22 cm'"></td>
                        </tr>
                        <tr class="hover:bg-brand-light/50 transition-colors bg-slate-50/50">
                            <td class="p-3 font-extrabold">M (Medium)</td>
                            <td class="p-3" x-text="unit === 'in' ? '44 in' : '112 cm'"></td>
                            <td class="p-3" x-text="unit === 'in' ? '29 in' : '74 cm'"></td>
                            <td class="p-3" x-text="unit === 'in' ? '22 in' : '56 cm'"></td>
                            <td class="p-3" x-text="unit === 'in' ? '9.0 in' : '23 cm'"></td>
                        </tr>
                        <tr class="hover:bg-brand-light/50 transition-colors">
                            <td class="p-3 font-extrabold">L (Large)</td>
                            <td class="p-3" x-text="unit === 'in' ? '46 in' : '117 cm'"></td>
                            <td class="p-3" x-text="unit === 'in' ? '30 in' : '76 cm'"></td>
                            <td class="p-3" x-text="unit === 'in' ? '23 in' : '58 cm'"></td>
                            <td class="p-3" x-text="unit === 'in' ? '9.5 in' : '24 cm'"></td>
                        </tr>
                        <tr class="hover:bg-brand-light/50 transition-colors bg-slate-50/50">
                            <td class="p-3 font-extrabold">XL (Extra Large)</td>
                            <td class="p-3" x-text="unit === 'in' ? '48 in' : '122 cm'"></td>
                            <td class="p-3" x-text="unit === 'in' ? '31 in' : '79 cm'"></td>
                            <td class="p-3" x-text="unit === 'in' ? '24 in' : '61 cm'"></td>
                            <td class="p-3" x-text="unit === 'in' ? '10.0 in' : '25 cm'"></td>
                        </tr>
                        <tr class="hover:bg-brand-light/50 transition-colors">
                            <td class="p-3 font-extrabold">XXL (Double XL)</td>
                            <td class="p-3" x-text="unit === 'in' ? '50 in' : '127 cm'"></td>
                            <td class="p-3" x-text="unit === 'in' ? '32 in' : '81 cm'"></td>
                            <td class="p-3" x-text="unit === 'in' ? '25 in' : '64 cm'"></td>
                            <td class="p-3" x-text="unit === 'in' ? '10.5 in' : '27 cm'"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-600 leading-relaxed">
                <strong class="text-black font-extrabold">💡 Fit Guide Note:</strong> Our cuts are tailored with an authentic oversized street silhouette. For a standard tailored fit, consider sizing down one size.
            </div>

        </div>
    </div>

    {{-- Floating Sticky Bottom Bar on Mobile & Desktop --}}
    <div x-data="{ showSticky: false }" 
         x-init="window.addEventListener('scroll', () => { 
             const buyRow = document.getElementById('main-buy-row');
             if (buyRow) {
                 const rect = buyRow.getBoundingClientRect();
                 showSticky = rect.bottom < 0;
             }
         })"
         x-show="showSticky" 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="translate-y-full opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="translate-y-0 opacity-100"
         x-transition:leave-end="translate-y-full opacity-0"
         style="display: none;"
         class="fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-md border-t border-brand-border/80 shadow-[0_-4px_25px_rgba(0,0,0,0.12)] p-3 sm:py-3.5 sm:px-6">
        
        <div class="max-w-[1440px] mx-auto flex items-center justify-between gap-4">
            
            {{-- Left: Item Thumbnail & Title --}}
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-11 h-11 rounded-lg bg-brand-light overflow-hidden shrink-0 border border-brand-border">
                    @if($product->primaryImage)
                        <img src="{{ $product->primaryImage->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @endif
                </div>
                <div class="min-w-0 hidden sm:block">
                    <h4 class="text-xs font-extrabold text-brand-dark truncate">{{ $product->name }}</h4>
                    <p class="text-[11px] font-extrabold text-brand-text" x-text="formatPrice(currentPrice)"></p>
                </div>
            </div>

            {{-- Right: Variant status + Quick Add Button --}}
            <div class="flex items-center gap-3 shrink-0">
                <div class="text-right sm:text-left">
                    <div class="text-xs font-black text-brand-dark sm:hidden" x-text="formatPrice(currentPrice)"></div>
                    <div class="text-[10px] text-brand-muted font-bold" x-show="selectedSize">
                        Size: <strong class="text-brand-dark" x-text="selectedSize"></strong>
                    </div>
                </div>

                <button type="button" 
                        @click="addToCart" 
                        class="h-11 px-6 bg-brand-dark hover:bg-brand-text text-white text-xs font-extrabold uppercase tracking-wider rounded-xl shadow-md transition-all active:scale-95 flex items-center gap-2 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="!canAddToCart">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span x-text="buttonText"></span>
                </button>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
{{-- Google Model Viewer Script --}}
<script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.3.0/model-viewer.min.js"></script>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('productDetails', (variants, basePrice) => ({
            variants: variants,
            basePrice: basePrice,
            
            sizeGuideOpen: false,

            selectedSize: null,
            selectedColor: null,
            quantity: 1,
            
            showSizeError: false,
            showColorError: false,

            get availableSizes() {
                const sizes = this.variants.map(v => v.size).filter(s => s);
                return [...new Set(sizes)];
            },
            
            get availableColors() {
                // If a size is selected, only show colors available for that size
                let relevantVariants = this.variants;
                if (this.selectedSize) {
                    relevantVariants = this.variants.filter(v => v.size === this.selectedSize);
                }
                const colors = relevantVariants.map(v => v.color).filter(c => c);
                return [...new Set(colors)];
            },

            get selectedVariant() {
                if (this.variants.length === 0) return null;
                
                return this.variants.find(v => {
                    const sizeMatch = !this.availableSizes.length || v.size === this.selectedSize;
                    const colorMatch = !this.availableColors.length || v.color === this.selectedColor;
                    return sizeMatch && colorMatch;
                }) || null;
            },

            get currentPrice() {
                if (this.selectedVariant && this.selectedVariant.price) {
                    return this.selectedVariant.price;
                }
                return this.basePrice;
            },
            
            get currentSku() {
                return this.selectedVariant ? this.selectedVariant.sku : null;
            },

            get canAddToCart() {
                if (this.variants.length === 0) return true; // Base product only
                if (this.availableSizes.length > 0 && !this.selectedSize) return false;
                if (this.availableColors.length > 0 && !this.selectedColor) return false;
                if (this.selectedVariant && this.selectedVariant.stock <= 0) return false;
                return true;
            },
            
            get buttonText() {
                if (this.selectedVariant && this.selectedVariant.stock <= 0) return 'Out of Stock';
                return 'Add to Bag';
            },

            selectSize(size) {
                this.selectedSize = size;
                this.showSizeError = false;
                // Auto-select color if only one is available for this size
                if (this.availableColors.length === 1) {
                    this.selectedColor = this.availableColors[0];
                } else if (!this.availableColors.includes(this.selectedColor)) {
                    this.selectedColor = null;
                }
            },
            
            selectColor(color) {
                this.selectedColor = color;
                this.showColorError = false;
                // Auto-select size if only one is available for this color
                if (this.availableSizes.length === 1 && !this.selectedSize) {
                    this.selectedSize = this.availableSizes[0];
                }
            },

            formatPrice(price) {
                return '₹' + new Intl.NumberFormat('en-IN').format(price);
            },

            addToCart() {
                if (this.availableSizes.length > 0 && !this.selectedSize) {
                    this.showSizeError = true;
                    return;
                }
                if (this.availableColors.length > 0 && !this.selectedColor) {
                    this.showColorError = true;
                    return;
                }
                
                // Dispatch custom event to global App component
                window.dispatchEvent(new CustomEvent('add-to-cart', {
                    detail: {
                        variant_id: this.selectedVariant.id,
                        quantity: this.quantity
                    }
                }));
            }
        }));
    });
</script>
@endpush
