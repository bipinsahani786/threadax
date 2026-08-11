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

    <div class="max-w-[1440px] mx-auto px-4 lg:px-8 py-12" x-data="shopFilters()">
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

                <form @submit.prevent id="filter-form">
                    {{-- Search --}}
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}" x-model="filters.search">
                        <div class="mb-8">
                            <h3 class="text-sm font-bold uppercase tracking-widest text-brand-text mb-4">Search Results For</h3>
                            <p class="text-sm text-brand-muted">"{{ request('search') }}"</p>
                        </div>
                    @endif

                    {{-- Sort --}}
                    <div class="mb-8">
                        <h3 class="text-sm font-bold uppercase tracking-widest text-brand-text mb-4">Sort By</h3>
                        <select name="sort" x-model="filters.sort" class="w-full bg-brand-light border-transparent focus:border-brand-text focus:ring-0 text-sm rounded p-2">
                            <option value="newest">Newest Arrivals</option>
                            <option value="price_low">Price: Low to High</option>
                            <option value="price_high">Price: High to Low</option>
                        </select>
                    </div>

                    {{-- Categories --}}
                    <div class="mb-8 border-t border-brand-border pt-8">
                        <h3 class="text-sm font-bold uppercase tracking-widest text-brand-text mb-4">Category</h3>
                        <div class="space-y-3">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="category" value="" x-model="filters.category" class="w-4 h-4 text-brand-text bg-brand-light border-brand-border focus:ring-brand-text">
                                <span class="text-sm text-brand-muted group-hover:text-brand-text transition-colors">All Products</span>
                            </label>
                            @foreach($categories as $category)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="radio" name="category" value="{{ $category->id }}" x-model="filters.category" class="w-4 h-4 text-brand-text bg-brand-light border-brand-border focus:ring-brand-text">
                                    <span class="text-sm text-brand-muted group-hover:text-brand-text transition-colors">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Sizes --}}
                    @if(isset($availableSizes) && $availableSizes->count() > 0)
                    <div class="mb-8 border-t border-brand-border pt-8">
                        <h3 class="text-sm font-bold uppercase tracking-widest text-brand-text mb-4">Size</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($availableSizes as $size)
                                <label class="cursor-pointer">
                                    <input type="checkbox" value="{{ $size }}" x-model="filters.sizes" class="peer hidden">
                                    <div class="h-10 px-4 flex items-center justify-center border border-brand-border text-sm font-semibold transition-colors peer-checked:bg-brand-text peer-checked:text-white peer-checked:border-brand-text hover:border-brand-muted">
                                        {{ $size }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Colors --}}
                    @if(isset($availableColors) && $availableColors->count() > 0)
                    <div class="mb-8 border-t border-brand-border pt-8">
                        <h3 class="text-sm font-bold uppercase tracking-widest text-brand-text mb-4">Color</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($availableColors as $color)
                                <label class="cursor-pointer">
                                    <input type="checkbox" value="{{ $color }}" x-model="filters.colors" class="peer hidden">
                                    <div class="h-10 px-4 flex items-center justify-center border border-brand-border text-sm font-semibold transition-colors peer-checked:bg-brand-text peer-checked:text-white peer-checked:border-brand-text hover:border-brand-muted capitalize">
                                        {{ $color }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Price Range --}}
                    <div class="mb-8 border-t border-brand-border pt-8">
                        <h3 class="text-sm font-bold uppercase tracking-widest text-brand-text mb-4">Price Range</h3>
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex-1">
                                <label class="text-xs text-brand-muted uppercase">Min (₹)</label>
                                <input type="number" min="0" :max="filters.max_price" x-model.debounce.500ms="filters.min_price" class="w-full bg-brand-light border-transparent focus:border-brand-text focus:ring-0 text-sm rounded p-2">
                            </div>
                            <div class="flex-1">
                                <label class="text-xs text-brand-muted uppercase">Max (₹)</label>
                                <input type="number" :min="filters.min_price" max="{{ $maxPrice }}" x-model.debounce.500ms="filters.max_price" class="w-full bg-brand-light border-transparent focus:border-brand-text focus:ring-0 text-sm rounded p-2">
                            </div>
                        </div>
                    </div>
                    
                    <div class="lg:hidden mt-8">
                        <button type="button" @click="mobileFiltersOpen = false" class="w-full btn-primary px-4 py-3 bg-brand-text text-white text-sm font-bold uppercase rounded">View Results</button>
                    </div>
                </form>
            </aside>

            {{-- Right Area: Products Grid --}}
            <div class="flex-1 relative">
                
                {{-- Loading Overlay --}}
                <div x-show="loading" class="absolute inset-0 bg-white/60 z-20 flex items-start justify-center pt-20 transition-opacity">
                    <div class="w-10 h-10 border-4 border-brand-light border-t-brand-text rounded-full animate-spin"></div>
                </div>

                {{-- Products Grid Container --}}
                <div id="products-grid">
                    @include('frontend.pages.products.partials.grid')
                </div>
                
            </div>
                
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('shopFilters', () => ({
            mobileFiltersOpen: false,
            loading: false,
            filters: {
                search: '{{ request("search", "") }}',
                sort: '{{ request("sort", "newest") }}',
                category: '{{ request("category", "") }}',
                min_price: '{{ request("min_price", 0) }}',
                max_price: '{{ request("max_price", $maxPrice ?? 10000) }}',
                sizes: [],
                colors: []
            },
            
            init() {
                // Initialize array filters from URL if present
                const urlParams = new URLSearchParams(window.location.search);
                if(urlParams.getAll('sizes[]').length) this.filters.sizes = urlParams.getAll('sizes[]');
                if(urlParams.getAll('colors[]').length) this.filters.colors = urlParams.getAll('colors[]');

                // Watch for changes deeply on filters object
                this.$watch('filters', (value) => {
                    this.fetchProducts();
                }, { deep: true });

                // Handle pagination clicks within the grid area
                document.getElementById('products-grid')?.addEventListener('click', (e) => {
                    if(e.target.closest('a') && e.target.closest('#pagination-links')) {
                        e.preventDefault();
                        const url = e.target.closest('a').href;
                        this.fetchProducts(url);
                    }
                });
            },
            
            resetFilters() {
                this.filters.search = '';
                this.filters.sort = 'newest';
                this.filters.category = '';
                this.filters.min_price = 0;
                this.filters.max_price = {{ $maxPrice ?? 10000 }};
                this.filters.sizes = [];
                this.filters.colors = [];
            },

            fetchProducts(url = null) {
                this.loading = true;
                
                // Build query string
                const params = new URLSearchParams();
                
                if (this.filters.search) params.append('search', this.filters.search);
                if (this.filters.sort !== 'newest') params.append('sort', this.filters.sort);
                if (this.filters.category) params.append('category', this.filters.category);
                if (this.filters.min_price > 0) params.append('min_price', this.filters.min_price);
                if (this.filters.max_price < {{ $maxPrice ?? 10000 }}) params.append('max_price', this.filters.max_price);
                
                this.filters.sizes.forEach(size => params.append('sizes[]', size));
                this.filters.colors.forEach(color => params.append('colors[]', color));

                const fetchUrl = url || `${window.location.pathname}?${params.toString()}`;
                
                // Update browser URL silently
                window.history.pushState({}, '', fetchUrl);

                fetch(fetchUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    document.getElementById('products-grid').innerHTML = html;
                })
                .catch(error => console.error('Error fetching products:', error))
                .finally(() => {
                    this.loading = false;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            }
        }));
    });
</script>
@endpush
