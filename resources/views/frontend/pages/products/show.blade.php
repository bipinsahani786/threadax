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
            <div class="w-full lg:w-[55%] flex flex-col-reverse md:flex-row gap-4" x-data="{ mainImage: '{{ $product->primary_image ? $product->primary_image->url : '' }}' }">
                
                {{-- Thumbnails --}}
                <div class="flex md:flex-col gap-4 overflow-x-auto md:overflow-y-auto md:w-24 shrink-0 no-scrollbar">
                    @foreach($product->images as $image)
                        <button @click="mainImage = '{{ $image->url }}'" class="w-20 md:w-full aspect-[3/4] bg-brand-light cursor-pointer border-2 transition-colors shrink-0" :class="mainImage === '{{ $image->url }}' ? 'border-brand-text' : 'border-transparent hover:border-brand-muted'">
                            <img src="{{ $image->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
                
                {{-- Main Image --}}
                <div class="w-full bg-brand-light aspect-[3/4] md:aspect-auto md:h-[800px] overflow-hidden group">
                    <template x-if="mainImage">
                        <img :src="mainImage" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!mainImage">
                        <div class="w-full h-full flex items-center justify-center bg-gray-200">
                            <span class="text-gray-400">No Image Available</span>
                        </div>
                    </template>
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
                            <button type="button" class="text-xs text-brand-muted underline hover:text-brand-text">Size Guide</button>
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

                {{-- Add to Cart Form --}}
                <form action="#" method="POST" @submit.prevent="addToCart" class="flex gap-4 mb-10 border-t border-brand-border pt-8">
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
                    <form action="{{ route('account.wishlist.toggle') }}" method="POST" class="absolute right-0 bottom-24 -mt-20 -mr-2">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="w-14 h-14 border flex items-center justify-center transition-colors bg-white {{ $inWishlist ? 'border-red-500 text-red-500 hover:bg-red-50' : 'border-brand-border text-brand-muted hover:text-red-500 hover:border-red-500' }}">
                            <svg class="w-5 h-5 {{ $inWishlist ? 'fill-current' : 'fill-none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                        </button>
                    </form>
                @else
                    <a href="{{ route('auth.login') }}" class="absolute right-0 bottom-24 -mt-20 -mr-2 w-14 h-14 border border-brand-border flex items-center justify-center text-brand-muted hover:text-red-500 hover:border-red-500 transition-colors bg-white">
                        <svg class="w-5 h-5 fill-none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                    </a>
                @endauth

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
                            <div class="pb-6 text-sm text-brand-muted space-y-4">
                                <p><strong class="text-brand-text">Free Shipping:</strong> On all orders above ₹999.</p>
                                <p><strong class="text-brand-text">Delivery Time:</strong> Standard delivery within 3-5 business days. Metro cities within 1-2 business days.</p>
                                <p><strong class="text-brand-text">Returns:</strong> Easy 7-day returns and exchanges. Product must be unwashed and unworn with original tags attached.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

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

@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('productDetails', (variants, basePrice) => ({
            variants: variants,
            basePrice: basePrice,
            
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
