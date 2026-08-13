<div class="hidden lg:flex items-center justify-between mb-8">
    <p class="text-sm text-brand-muted">Showing {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} Products</p>
</div>

@if($products->count() > 0)
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-8">
        @foreach($products as $product)
            @include('frontend.pages.products.partials.card', ['product' => $product])
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-12" id="pagination-links">
        {{ $products->links() }}
    </div>
@else
    <div class="text-center py-24 bg-brand-off-white rounded-xl border border-brand-border">
        <svg class="w-12 h-12 text-brand-muted mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <h3 class="text-lg font-bold text-brand-dark mb-2">No products found</h3>
        <p class="text-brand-muted text-sm">Try adjusting your filters or search criteria.</p>
        <button type="button" @click="resetFilters()" class="mt-6 inline-block border-b-2 border-brand-text pb-1 text-sm font-semibold uppercase tracking-widest hover:text-brand-muted transition-colors">Clear All Filters</button>
    </div>
@endif
