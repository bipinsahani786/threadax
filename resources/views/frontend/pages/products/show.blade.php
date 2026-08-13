@extends('frontend.layouts.app')

@section('title', $product->name . ' - ThreadAx')
@section('meta_description', Str::limit(strip_tags($product->description), 150))
@section('meta_image', $product->primaryImage ? $product->primaryImage->url : asset('images/banner-men.png'))

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
                    <div class="mb-8" x-show="availableSizes.length > 0">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-bold uppercase tracking-widest text-brand-text">Select Size</span>
                            @if($product->size_guide_url)
                                <button type="button" @click="sizeGuideOpen = true" class="text-xs text-brand-muted underline hover:text-brand-text">Size Guide</button>
                            @endif
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <template x-for="size in availableSizes" :key="size">
                                <button type="button" 
                                    @click="selectSize(size)"
                                    class="h-12 px-6 border text-sm font-semibold transition-colors"
                                    :class="selectedSize === size ? 'border-brand-text bg-brand-text text-white' : 'border-brand-border bg-white text-brand-text hover:border-brand-muted'"
                                    x-text="size">
                                </button>
                            </template>
                        </div>
                        <p x-show="showSizeError" class="text-red-500 text-xs mt-2 font-bold transition-opacity">Please select a size.</p>
                    </div>

                    {{-- Color Selector --}}
                    <div class="mb-8" x-show="availableColors.length > 0">
                        <span class="text-xs font-bold uppercase tracking-widest text-brand-text block mb-4">Select Color <span x-show="selectedColor" class="text-brand-muted normal-case ml-2" x-text="'- ' + selectedColor"></span></span>
                        <div class="flex flex-wrap gap-3">
                            <template x-for="color in availableColors" :key="color">
                                <button type="button" 
                                    @click="selectColor(color)"
                                    class="h-12 px-6 border text-sm font-semibold transition-colors"
                                    :class="selectedColor === color ? 'border-brand-text bg-brand-text text-white' : 'border-brand-border bg-white text-brand-text hover:border-brand-muted'"
                                    x-text="color">
                                </button>
                            </template>
                        </div>
                        <p x-show="showColorError" class="text-red-500 text-xs mt-2 font-bold transition-opacity">Please select a color.</p>
                    </div>
                @endif

                {{-- Stock Status --}}
                <div class="mb-8 h-6 flex items-center">
                    <template x-if="selectedVariant">
                        <div>
                            <span x-show="selectedVariant.stock > 10" class="text-sm font-bold text-green-600 flex items-center gap-2"><div class="w-2 h-2 rounded-full bg-green-500"></div> In Stock</span>
                            <span x-show="selectedVariant.stock > 0 && selectedVariant.stock <= 10" class="text-sm font-bold text-orange-500 flex items-center gap-2"><div class="w-2 h-2 rounded-full bg-orange-500"></div> Only <span x-text="selectedVariant.stock"></span> left in stock!</span>
                            <span x-show="selectedVariant.stock <= 0" class="text-sm font-bold text-red-500 flex items-center gap-2"><div class="w-2 h-2 rounded-full bg-red-500"></div> Out of Stock</span>
                        </div>
                    </template>
                    <template x-if="!selectedVariant && variants.length === 0">
                        <span class="text-sm font-bold text-green-600 flex items-center gap-2"><div class="w-2 h-2 rounded-full bg-green-500"></div> In Stock</span>
                    </template>
                </div>

                {{-- Action Buttons Row --}}
                <div class="flex gap-4 mb-10 border-t border-brand-border pt-8">
                    {{-- Add to Cart Form --}}
                    <form action="#" method="POST" @submit.prevent="addToCart" class="flex flex-1 gap-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="variant_id" :value="selectedVariant ? selectedVariant.id : ''">
                        
                        <div class="w-24 h-14 border border-brand-border flex items-center justify-between px-4 shrink-0 bg-white">
                            <button type="button" @click="quantity > 1 ? quantity-- : null" class="text-brand-muted hover:text-brand-text transition-colors font-bold">&minus;</button>
                            <span class="text-sm font-bold" x-text="quantity"></span>
                            <button type="button" @click="quantity++" class="text-brand-muted hover:text-brand-text transition-colors font-bold">&plus;</button>
                            <input type="hidden" name="quantity" :value="quantity">
                        </div>
                        
                        <button type="submit" 
                            class="flex-1 h-14 bg-brand-text text-white text-sm font-bold uppercase tracking-widest hover:bg-brand-dark transition-colors flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="!canAddToCart">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            <span x-text="buttonText"></span>
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
                            <button type="submit" class="w-14 h-14 border flex items-center justify-center transition-colors bg-white {{ $inWishlist ? 'border-red-500 text-red-500 hover:bg-red-50' : 'border-brand-border text-brand-muted hover:text-red-500 hover:border-red-500' }}">
                                <svg class="w-5 h-5 {{ $inWishlist ? 'fill-current' : 'fill-none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('auth.login') }}" class="w-14 h-14 border border-brand-border flex items-center justify-center text-brand-muted hover:text-red-500 hover:border-red-500 transition-colors bg-white shrink-0">
                            <svg class="w-5 h-5 fill-none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                        </a>
                    @endauth
                </div>

                {{-- Accordions for details --}}
                <div class="border-t border-brand-border divide-y divide-brand-border" x-data="{ activeTab: 1 }">
                    {{-- Description --}}
                    <div>
                        <button @click="activeTab = activeTab === 1 ? null : 1" class="w-full py-6 flex items-center justify-between text-left focus:outline-none group">
                            <span class="text-sm font-bold uppercase tracking-widest text-brand-text">Description</span>
                            <svg class="w-5 h-5 text-brand-muted group-hover:text-brand-text transition-transform duration-300" :class="activeTab === 1 ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="activeTab === 1" x-collapse>
                            <div class="pb-6 text-sm text-brand-muted prose prose-sm max-w-none">
                                {!! nl2br(e($product->description)) !!}
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

    {{-- Size Guide Modal --}}
    @if($product->size_guide_url)
        <div x-show="sizeGuideOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4" @keydown.escape.window="sizeGuideOpen = false">
            <button @click="sizeGuideOpen = false" class="absolute top-6 right-6 text-white hover:text-gray-300 transition-colors z-50">
                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <div class="w-full max-w-3xl max-h-[90vh] bg-white rounded overflow-y-auto relative p-6 md:p-10 shadow-2xl" @click.away="sizeGuideOpen = false">
                <h3 class="text-2xl font-bold font-heading mb-6 border-b pb-4">Size Guide</h3>
                <img src="{{ $product->size_guide_url }}" alt="Size Guide" class="w-full h-auto">
            </div>
        </div>
    @endif

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
