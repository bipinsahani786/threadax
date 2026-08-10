@extends('admin.layouts.app')

@section('title', 'Products')
@section('page-title', 'Manage Products')

@section('content')

{{-- Analytics Grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-[20px] p-6 shadow-[0_4px_24px_rgba(0,0,0,0.03)] border border-slate-100">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-slate-50 text-slate-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Products</span>
            </div>
        </div>
        <p class="text-3xl font-black text-slate-800 font-heading">{{ $stats['total'] }}</p>
    </div>

    <div class="bg-white rounded-[20px] p-6 shadow-[0_4px_24px_rgba(0,0,0,0.03)] border border-slate-100">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-green-50 text-green-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Active</span>
            </div>
        </div>
        <p class="text-3xl font-black text-slate-800 font-heading">{{ $stats['active'] }}</p>
    </div>

    <div class="bg-white rounded-[20px] p-6 shadow-[0_4px_24px_rgba(0,0,0,0.03)] border border-slate-100">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-amber-50 text-amber-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
            </div>
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Featured</span>
            </div>
        </div>
        <p class="text-3xl font-black text-slate-800 font-heading">{{ $stats['featured'] }}</p>
    </div>

    <div class="bg-white rounded-[20px] p-6 shadow-[0_4px_24px_rgba(0,0,0,0.03)] border border-slate-100">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-red-50 text-red-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Out of Stock</span>
            </div>
        </div>
        <p class="text-3xl font-black text-slate-800 font-heading">{{ $stats['out_of_stock'] }}</p>
    </div>
</div>

{{-- Main Content Card --}}
<div class="bg-white rounded-[24px] shadow-[0_4px_24px_rgba(0,0,0,0.03)] border border-slate-100 overflow-hidden">
    
    {{-- Header / Filters --}}
    <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50">
        <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-1 items-center gap-3 flex-wrap" x-data x-ref="filterForm">
            <div class="relative w-full md:max-w-xs">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products, SKU..." class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-slate-900 focus:border-slate-900 outline-none transition-all">
            </div>
            
            <select name="category" class="border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-slate-900 outline-none bg-white" @change="$refs.filterForm.submit()">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->slug }}" {{ request('category') === $category->slug ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <select name="is_active" class="border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-slate-900 outline-none bg-white hidden sm:block" @change="$refs.filterForm.submit()">
                <option value="">Status</option>
                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
            </select>
            
            <button type="submit" class="hidden">Search</button>
            @if(request()->hasAny(['search', 'category', 'is_active', 'is_featured']))
                <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-slate-500 hover:text-red-500 transition-colors">Clear</a>
            @endif
        </form>
        
        <div class="shrink-0">
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-slate-900 hover:bg-slate-800 transition-all shadow-[0_4px_12px_rgba(0,0,0,0.1)] hover:shadow-[0_4px_16px_rgba(0,0,0,0.15)]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Product
            </a>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50/80 text-xs uppercase font-bold text-slate-400 border-b border-slate-100">
                <tr>
                    <th scope="col" class="px-6 py-4">Product</th>
                    <th scope="col" class="px-6 py-4">Category</th>
                    <th scope="col" class="px-6 py-4">Price</th>
                    <th scope="col" class="px-6 py-4">Status</th>
                    <th scope="col" class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($products as $product)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-lg bg-slate-100 flex-shrink-0 flex items-center justify-center overflow-hidden border border-slate-200">
                                    @if($product->primary_image)
                                        <img src="{{ $product->primary_image->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 line-clamp-1">{{ $product->name }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5">SKU: {{ $product->sku ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                {{ $product->category->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-bold text-slate-900">₹{{ number_format($product->price) }}</div>
                            @if($product->compare_price > $product->price)
                                <div class="text-xs text-slate-400 line-through">₹{{ number_format($product->compare_price) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col gap-1.5">
                                @if($product->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-green-50 text-green-700 border border-green-200 w-fit">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200 w-fit">
                                        Inactive
                                    </span>
                                @endif

                                @if($product->is_featured)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200 w-fit">
                                        Featured
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.products.edit', $product) }}" title="Edit Product" class="p-2 rounded-lg text-slate-400 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <a href="{{ route('admin.products.variants', $product) }}" title="Manage Variants & Images" class="p-2 rounded-lg text-slate-400 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Delete Product" class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 text-slate-400 mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900 mb-1">No products found</h3>
                            <p class="text-sm text-slate-500">Get started by adding your first product to the catalog.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($products->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $products->withQueryString()->links() }}
        </div>
    @endif
</div>

@endsection
