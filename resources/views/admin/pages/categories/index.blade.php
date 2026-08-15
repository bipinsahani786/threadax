@extends('admin.layouts.app')

@section('title', 'Category Architecture - Admin')
@section('page-title', 'Category Architecture & Navigation')

@section('content')
<div class="space-y-6 sm:space-y-8">

    {{-- Top Executive Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        
        {{-- Card 1: Total Categories --}}
        <a href="{{ route('admin.categories.index') }}" class="group block bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs hover:border-slate-400 hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Categories</span>
                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    📁
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900">{{ number_format($stats['total']) }}</span>
                <span class="text-[11px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">
                    {{ $stats['subs'] }} Sub-cats
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Department & collection trees</p>
        </a>

        {{-- Card 2: Active Categories --}}
        <a href="{{ route('admin.categories.index', ['is_active' => '1']) }}" class="group block bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs hover:border-emerald-400 hover:shadow-md transition-all {{ request('is_active') === '1' ? 'ring-2 ring-emerald-500 border-transparent' : '' }}">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Active on Store</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    🟢
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-emerald-950">{{ number_format($stats['active']) }}</span>
                <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                    Live
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Accessible to customers on store</p>
        </a>

        {{-- Card 3: Root Departments --}}
        <a href="{{ route('admin.categories.index', ['parent_id' => 'root']) }}" class="group block bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs hover:border-blue-400 hover:shadow-md transition-all {{ request('parent_id') === 'root' ? 'ring-2 ring-blue-500 border-transparent' : '' }}">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-blue-700 uppercase tracking-wider">Root Departments</span>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    🏛️
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-blue-950">{{ number_format($stats['roots']) }}</span>
                <span class="text-[11px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-200">
                    Top Level
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Main store umbrella departments</p>
        </a>

        {{-- Card 4: Header Nav Menus --}}
        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-purple-700 uppercase tracking-wider">Header Menu Nav</span>
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-lg">
                    🌐
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-purple-950">{{ number_format($stats['header']) }}</span>
                <span class="text-[11px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-full border border-purple-200">
                    Navbar
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Pinned to top customer navbar</p>
        </div>

    </div>

    {{-- Main Categories Ledger Card --}}
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden">
        
        {{-- Header / Filters Bar --}}
        <div class="p-5 sm:p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            
            <form action="{{ route('admin.categories.index') }}" method="GET" class="flex flex-1 items-center gap-3 flex-wrap" x-data x-ref="filterForm">
                
                {{-- Search Input --}}
                <div class="relative w-full sm:w-72">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                        🔍
                    </span>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search categories, slugs..." 
                           class="w-full pl-9 pr-4 py-2.5 bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all shadow-2xs">
                </div>

                {{-- Parent Dropdown --}}
                <select name="parent_id" class="px-3.5 py-2.5 bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-700 outline-none shadow-2xs cursor-pointer" @change="$refs.filterForm.submit()">
                    <option value="">All Hierarchies</option>
                    <option value="root" {{ request('parent_id') === 'root' ? 'selected' : '' }}>Root Collections Only</option>
                    @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}" {{ request('parent_id') == $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                    @endforeach
                </select>

                {{-- Visibility Status --}}
                <select name="is_active" class="px-3.5 py-2.5 bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-700 outline-none shadow-2xs cursor-pointer" @change="$refs.filterForm.submit()">
                    <option value="">All Statuses</option>
                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active (Live)</option>
                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive (Hidden)</option>
                </select>
                
                <button type="submit" class="hidden">Search</button>

                @if(request()->hasAny(['search', 'parent_id', 'is_active']))
                    <a href="{{ route('admin.categories.index') }}" class="text-xs font-bold text-rose-600 hover:text-rose-700 hover:underline px-2 py-1 transition-colors">
                        ✕ Clear Filters
                    </a>
                @endif
            </form>
            
            {{-- Add Category CTA --}}
            <div class="shrink-0 flex items-center gap-2.5">
                <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-extrabold text-white bg-slate-900 hover:bg-slate-800 transition-all shadow-sm hover:shadow-md cursor-pointer">
                    <span>➕ Add New Category</span>
                </a>
            </div>
        </div>

        {{-- Categories Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-[11px] uppercase font-extrabold text-slate-400 border-b border-slate-100 tracking-wider">
                    <tr>
                        <th scope="col" class="px-6 py-4">Category Name & Slug</th>
                        <th scope="col" class="px-5 py-4">Parent Tree</th>
                        <th scope="col" class="px-5 py-4">Products Assigned</th>
                        <th scope="col" class="px-5 py-4">Status & Navbar</th>
                        <th scope="col" class="px-5 py-4">Sort Order</th>
                        <th scope="col" class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($categories as $category)
                        <tr class="hover:bg-slate-50/60 transition-colors group">
                            
                            {{-- Category Name & Slug --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-sm shrink-0 border border-slate-200 shadow-2xs group-hover:border-slate-400 transition-colors">
                                        @if($category->parent)
                                            ↳
                                        @else
                                            📁
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="font-extrabold text-slate-900 text-sm hover:text-blue-600 transition-colors">
                                            {{ $category->name }}
                                        </a>
                                        <div class="flex items-center gap-1.5 mt-0.5 text-[11px] text-slate-400 font-mono">
                                            <span>/category/{{ $category->slug }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Parent Hierarchy --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($category->parent)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                        📁 {{ $category->parent->name }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                        🏛️ Root Department
                                    </span>
                                @endif
                            </td>

                            {{-- Products Count --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.products.index', ['category' => $category->slug]) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold hover:bg-slate-100 transition-colors {{ $category->products_count > 0 ? 'text-slate-900' : 'text-slate-400' }}">
                                    <span>👕 {{ $category->products_count }} {{ Str::plural('drop', $category->products_count) }}</span>
                                    <svg class="w-3 h-3 text-slate-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </td>

                            {{-- Status & Header Nav --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    @if($category->is_active)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> ACTIVE
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-slate-100 text-slate-600 border border-slate-200">
                                            INACTIVE
                                        </span>
                                    @endif
                                    
                                    @if($category->show_in_header)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-purple-50 text-purple-700 border border-purple-200">
                                            🌐 Header
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- Sort Order --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="text-xs font-mono font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">
                                    #{{ $category->sort_order }}
                                </span>
                            </td>

                            {{-- Actions Toolbar --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    
                                    {{-- Live Store Link --}}
                                    <a href="{{ route('frontend.products.index', ['category' => $category->id]) }}" 
                                       target="_blank" 
                                       title="View on Storefront" 
                                       class="p-2 rounded-xl text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>

                                    {{-- Edit Category --}}
                                    <a href="{{ route('admin.categories.edit', $category) }}" 
                                       title="Edit Category Details" 
                                       class="p-2 rounded-xl text-slate-400 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>

                                    {{-- Delete Category --}}
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete category {{ $category->name }}?');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors cursor-pointer" title="Delete Category">
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
                                    📁
                                </div>
                                <h3 class="text-sm font-extrabold text-slate-900 mb-1">No categories found</h3>
                                <p class="text-xs text-slate-400 mb-4">No departments match your search or filter criteria.</p>
                                <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-slate-900 hover:bg-slate-800">
                                    ➕ Create First Category
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Footer --}}
        @if($categories->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="text-xs text-slate-500 font-medium">
                    Showing <strong>{{ $categories->firstItem() }}</strong> to <strong>{{ $categories->lastItem() }}</strong> of <strong>{{ $categories->total() }}</strong> categories
                </div>
                <div>
                    {{ $categories->withQueryString()->links() }}
                </div>
            </div>
        @endif

    </div>

</div>
@endsection
