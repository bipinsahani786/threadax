@extends('frontend.layouts.app')

@section('title', 'Shop - ThreadAx')

@section('content')
    {{-- Page Header --}}
    <div class="bg-brand-light py-12 border-b border-brand-border">
        <div class="max-w-[1440px] mx-auto px-4 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-heading font-extrabold text-brand-dark mb-4">SHOP THE COLLECTION</h1>
            <p class="text-brand-muted text-sm max-w-lg mx-auto">Explore our full range of premium streetwear. High-quality fabrics, oversized fits, and bold designs.</p>
        </div>
    </div>

    <div class="max-w-[1440px] mx-auto px-4 lg:px-8 py-12" x-data="{ mobileFiltersOpen: false }">
        <div class="flex flex-col lg:flex-row gap-8">
            
            {{-- Mobile Filters Toggle --}}
            <div class="lg:hidden flex items-center justify-between border-b border-brand-border pb-4">
                <button @click="mobileFiltersOpen = true" class="flex items-center gap-2 text-sm font-bold text-brand-text">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                    Filters
                </button>
                <div class="text-sm text-brand-muted">{{ $products->total() }} Products</div>
            </div>

            {{-- Left Sidebar: Filters --}}
            <aside class="w-full lg:w-64 shrink-0 hidden lg:block" :class="mobileFiltersOpen ? '!block fixed inset-0 z-50 bg-white p-6 overflow-y-auto' : ''">
                
                <div class="flex items-center justify-between mb-8 lg:hidden">
                    <h2 class="text-lg font-bold">Filters</h2>
                    <button @click="mobileFiltersOpen = false" class="text-brand-muted"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>

                <form action="{{ route('frontend.products.index') }}" method="GET" id="filter-form">
                    {{-- Search --}}
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <div class="mb-8">
                            <h3 class="text-sm font-bold uppercase tracking-widest text-brand-text mb-4">Search Results For</h3>
                            <p class="text-sm text-brand-muted">"{{ request('search') }}"</p>
                        </div>
                    @endif

                    {{-- Sort --}}
                    <div class="mb-8">
                        <h3 class="text-sm font-bold uppercase tracking-widest text-brand-text mb-4">Sort By</h3>
                        <select name="sort" onchange="document.getElementById('filter-form').submit()" class="w-full bg-brand-light border-transparent focus:border-brand-text focus:ring-0 text-sm rounded p-2">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest Arrivals</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </div>

                    {{-- Categories --}}
                    <div class="mb-8 border-t border-brand-border pt-8">
                        <h3 class="text-sm font-bold uppercase tracking-widest text-brand-text mb-4">Category</h3>
                        <div class="space-y-3">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="category" value="" onchange="document.getElementById('filter-form').submit()" {{ !request('category') ? 'checked' : '' }} class="w-4 h-4 text-brand-text bg-brand-light border-brand-border focus:ring-brand-text">
                                <span class="text-sm text-brand-muted group-hover:text-brand-text transition-colors">All Products</span>
                            </label>
                            @foreach($categories as $category)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="radio" name="category" value="{{ $category->id }}" onchange="document.getElementById('filter-form').submit()" {{ request('category') == $category->id || request('category') == $category->slug ? 'checked' : '' }} class="w-4 h-4 text-brand-text bg-brand-light border-brand-border focus:ring-brand-text">
                                    <span class="text-sm text-brand-muted group-hover:text-brand-text transition-colors">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Price Range (Placeholder for future) --}}
                    {{-- 
                    <div class="mb-8 border-t border-brand-border pt-8">
                        <h3 class="text-sm font-bold uppercase tracking-widest text-brand-text mb-4">Price Range</h3>
                        ...
                    </div>
                    --}}
                    
                    <div class="lg:hidden mt-8">
                        <button type="submit" class="w-full btn-primary px-4 py-3 bg-brand-text text-white text-sm font-bold uppercase rounded">Apply Filters</button>
                    </div>
                </form>
            </aside>

            {{-- Right Area: Products Grid --}}
            <div class="flex-1">
                
                <div class="hidden lg:flex items-center justify-between mb-8">
                    <p class="text-sm text-brand-muted">Showing {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} Products</p>
                </div>

                @if($products->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-8">
                        @foreach($products as $product)
                            <div class="group cursor-pointer" onclick="window.location='{{ route('frontend.products.show', $product->slug) }}'">
                                <div class="relative w-full aspect-[3/4] bg-brand-light overflow-hidden mb-4">
                                    @if($product->primary_image)
                                        <img src="{{ $product->primary_image->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                            <span class="text-gray-400 text-sm">No Image</span>
                                        </div>
                                    @endif
                                    
                                    <a href="{{ route('frontend.products.show', $product->slug) }}" class="absolute bottom-4 right-4 bg-white p-2 rounded-full shadow-lg opacity-0 translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 hover:bg-brand-text hover:text-white flex items-center justify-center z-10">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    </a>
                                </div>
                                <h3 class="text-sm font-bold mb-1 line-clamp-1">{{ $product->name }}</h3>
                                <p class="text-brand-muted text-xs mb-2">{{ $product->category->name ?? 'Streetwear' }}</p>
                                <p class="font-semibold text-sm">₹{{ number_format($product->price) }}</p>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-12">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="text-center py-24 bg-brand-off-white rounded-xl border border-brand-border">
                        <svg class="w-12 h-12 text-brand-muted mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <h3 class="text-lg font-bold text-brand-dark mb-2">No products found</h3>
                        <p class="text-brand-muted text-sm">Try adjusting your filters or search criteria.</p>
                        <a href="{{ route('frontend.products.index') }}" class="mt-6 inline-block border-b-2 border-brand-text pb-1 text-sm font-semibold uppercase tracking-widest hover:text-brand-muted transition-colors">Clear All Filters</a>
                    </div>
                @endif
                
            </div>
        </div>
    </div>
@endsection
