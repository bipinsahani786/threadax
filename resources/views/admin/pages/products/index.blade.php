@extends('admin.layouts.app')

@section('title', 'Product Catalog - Admin')
@section('page-title', 'Product Catalog & Inventory')

@section('content')
<div class="space-y-6 sm:space-y-8">

    {{-- Top Executive Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        
        {{-- Card 1: Total Catalog & Valuation --}}
        <a href="{{ route('admin.products.index') }}" class="group block bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs hover:border-slate-400 hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Catalog</span>
                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    👕
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900">{{ number_format($stats['total']) }}</span>
                <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                    ₹{{ number_format($stats['total_valuation']) }} Val.
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Total products & drops listed</p>
        </a>

        {{-- Card 2: Active Live Drops --}}
        <a href="{{ route('admin.products.index', ['is_active' => '1']) }}" class="group block bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs hover:border-emerald-400 hover:shadow-md transition-all {{ request('is_active') === '1' ? 'ring-2 ring-emerald-500 border-transparent' : '' }}">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Live & Active</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    🟢
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-emerald-950">{{ number_format($stats['active']) }}</span>
                <span class="text-[11px] font-bold text-slate-500">
                    {{ $stats['total'] > 0 ? round(($stats['active'] / $stats['total']) * 100) : 0 }}% of Catalog
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Visible to customers on storefront</p>
        </a>

        {{-- Card 3: Low Stock Warning --}}
        <a href="{{ route('admin.products.index', ['stock_status' => 'low_stock']) }}" class="group block bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs hover:border-amber-400 hover:shadow-md transition-all {{ request('stock_status') === 'low_stock' ? 'ring-2 ring-amber-500 border-transparent' : '' }}">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-amber-700 uppercase tracking-wider">Low Stock (≤ 5)</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    ⚠️
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-amber-950">{{ number_format($stats['low_stock']) }}</span>
                <span class="text-[11px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">
                    Restock Soon
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Fast moving items near depletion</p>
        </a>

        {{-- Card 4: Out of Stock --}}
        <a href="{{ route('admin.products.index', ['stock_status' => 'out_of_stock']) }}" class="group block bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs hover:border-rose-400 hover:shadow-md transition-all {{ request('stock_status') === 'out_of_stock' ? 'ring-2 ring-rose-500 border-transparent' : '' }}">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-rose-700 uppercase tracking-wider">Out of Stock</span>
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    🚫
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-rose-950">{{ number_format($stats['out_of_stock']) }}</span>
                <span class="text-[11px] font-bold text-rose-700 bg-rose-50 px-2 py-0.5 rounded-full border border-rose-200">
                    Critical
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">0 available units in warehouse</p>
        </a>

    </div>

    {{-- Main Products Ledger Card --}}
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden">
        
        {{-- Header / Filters Bar --}}
        <div class="p-5 sm:p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            
            <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-1 items-center gap-3 flex-wrap" x-data x-ref="filterForm">
                
                {{-- Search Bar --}}
                <div class="relative w-full sm:w-72">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                        🔍
                    </span>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search drops, SKU, tags..." 
                           class="w-full pl-9 pr-4 py-2.5 bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all shadow-2xs">
                </div>

                {{-- Category Filter --}}
                <select name="category" class="px-3.5 py-2.5 bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-700 outline-none shadow-2xs cursor-pointer" @change="$refs.filterForm.submit()">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->slug }}" {{ request('category') === $category->slug ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                {{-- Stock Status Filter --}}
                <select name="stock_status" class="px-3.5 py-2.5 bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-700 outline-none shadow-2xs cursor-pointer" @change="$refs.filterForm.submit()">
                    <option value="">All Inventory</option>
                    <option value="in_stock" {{ request('stock_status') === 'in_stock' ? 'selected' : '' }}>In Stock (> 0)</option>
                    <option value="low_stock" {{ request('stock_status') === 'low_stock' ? 'selected' : '' }}>Low Stock (≤ 5)</option>
                    <option value="out_of_stock" {{ request('stock_status') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock (0)</option>
                </select>

                {{-- Visibility Status Filter --}}
                <select name="is_active" class="px-3.5 py-2.5 bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-700 outline-none shadow-2xs cursor-pointer hidden sm:block" @change="$refs.filterForm.submit()">
                    <option value="">All Statuses</option>
                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Live (Active)</option>
                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Draft (Inactive)</option>
                </select>

                <button type="submit" class="hidden">Search</button>

                @if(request()->hasAny(['search', 'category', 'is_active', 'is_featured', 'stock_status']))
                    <a href="{{ route('admin.products.index') }}" class="text-xs font-bold text-rose-600 hover:text-rose-700 hover:underline px-2 py-1 transition-colors">
                        ✕ Clear Filters
                    </a>
                @endif
            </form>
            
            {{-- Add Product CTA --}}
            <div class="shrink-0 flex items-center gap-2.5">
                <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-extrabold text-white bg-slate-900 hover:bg-slate-800 transition-all shadow-sm hover:shadow-md cursor-pointer">
                    <span>➕ Add New Product</span>
                </a>
            </div>
        </div>

        {{-- Products Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-[11px] uppercase font-extrabold text-slate-400 border-b border-slate-100 tracking-wider">
                    <tr>
                        <th scope="col" class="px-6 py-4">Product Details</th>
                        <th scope="col" class="px-5 py-4">Category</th>
                        <th scope="col" class="px-5 py-4">Pricing</th>
                        <th scope="col" class="px-5 py-4">Stock / Variants</th>
                        <th scope="col" class="px-5 py-4">Status</th>
                        <th scope="col" class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($products as $product)
                        @php
                            $primaryImg = $product->primaryImage ?? $product->images->first();
                            $imgUrl = $primaryImg?->url;
                            $totalStock = $product->variants->sum('stock');
                            $variantCount = $product->variants->count();
                            $sizesList = $product->variants->pluck('size')->filter()->unique()->take(4)->join(', ');
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors group">
                            
                            {{-- Product Details --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-12 h-14 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center relative shadow-2xs group-hover:border-slate-400 transition-colors">
                                        @if($imgUrl)
                                            <img src="{{ $imgUrl }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="font-bold text-slate-400 text-xs">TX</span>
                                        @endif
                                        @if($product->is_featured)
                                            <span class="absolute top-1 left-1 w-2.5 h-2.5 rounded-full bg-amber-400 ring-2 ring-white" title="Featured Product"></span>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="font-extrabold text-slate-900 text-sm hover:text-blue-600 transition-colors line-clamp-1">
                                            {{ $product->name }}
                                        </a>
                                        <div class="flex items-center gap-2 mt-1 text-[11px] text-slate-400 font-mono">
                                            <span>SKU: {{ $product->sku ?? 'TX-GEN' }}</span>
                                            @if($product->images->count() > 1)
                                                <span class="text-slate-300">•</span>
                                                <span>📷 {{ $product->images->count() }} imgs</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Category --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                    📁 {{ $product->category->name ?? 'Streetwear' }}
                                </span>
                            </td>

                            {{-- Pricing --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="font-extrabold text-slate-900 text-sm">₹{{ number_format($product->price, 2) }}</div>
                                @if($product->compare_price && $product->compare_price > $product->price)
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <span class="text-[10px] text-slate-400 line-through">₹{{ number_format($product->compare_price, 2) }}</span>
                                        <span class="text-[10px] font-black text-rose-600 bg-rose-50 px-1 rounded">
                                            {{ round((($product->compare_price - $product->price) / $product->compare_price) * 100) }}% OFF
                                        </span>
                                    </div>
                                @endif
                            </td>

                            {{-- Stock & Variants --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div>
                                    @if($totalStock == 0)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Out of Stock
                                        </span>
                                    @elseif($totalStock <= 5)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Low: {{ $totalStock }} left
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> {{ $totalStock }} in stock
                                        </span>
                                    @endif
                                </div>
                                <div class="text-[10px] font-bold text-slate-400 mt-1">
                                    {{ $variantCount }} {{ Str::plural('variant', $variantCount) }} @if($sizesList) ({{ $sizesList }}) @endif
                                </div>
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="flex flex-col gap-1">
                                    @if($product->is_active)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200 w-fit">
                                            LIVE
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-slate-100 text-slate-600 border border-slate-200 w-fit">
                                            DRAFT
                                        </span>
                                    @endif

                                    @if($product->is_featured)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200 w-fit">
                                            ⭐ Featured
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    
                                    {{-- Live Store Preview --}}
                                    <a href="{{ route('frontend.products.show', $product->slug) }}" 
                                       target="_blank" 
                                       title="View on Live Storefront" 
                                       class="p-2 rounded-xl text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>

                                    {{-- Manage Variants & Images --}}
                                    <a href="{{ route('admin.products.variants', $product) }}" 
                                       title="Manage Variants & Stock" 
                                       class="p-2 rounded-xl text-slate-400 hover:text-purple-600 hover:bg-purple-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                    </a>

                                    {{-- Edit Product --}}
                                    <a href="{{ route('admin.products.edit', $product) }}" 
                                       title="Edit Product Info" 
                                       class="p-2 rounded-xl text-slate-400 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>

                                    {{-- 1-Click Duplicate Product --}}
                                    <form action="{{ route('admin.products.duplicate', $product) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" title="Duplicate Drop" class="p-2 rounded-xl text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition-colors cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        </button>
                                    </form>

                                    {{-- Delete Product --}}
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product drop?');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Delete Product" class="p-2 rounded-xl text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 mb-3 text-2xl">
                                    👕
                                </div>
                                <h3 class="text-sm font-extrabold text-slate-900 mb-1">No products found</h3>
                                <p class="text-xs text-slate-400 mb-4">No streetwear drops match your search or filter criteria.</p>
                                <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-slate-900 hover:bg-slate-800">
                                    ➕ Create First Drop
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Footer --}}
        @if($products->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="text-xs text-slate-500 font-medium">
                    Showing <strong>{{ $products->firstItem() }}</strong> to <strong>{{ $products->lastItem() }}</strong> of <strong>{{ $products->total() }}</strong> drops
                </div>
                <div>
                    {{ $products->links() }}
                </div>
            </div>
        @endif

    </div>

</div>
@endsection
