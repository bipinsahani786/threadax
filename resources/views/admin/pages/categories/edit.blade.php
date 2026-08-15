@extends('admin.layouts.app')

@section('title', 'Edit Category - ' . $category->name)
@section('page-title')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.categories.index') }}" class="text-slate-400 hover:text-slate-900 transition-colors p-1.5 rounded-xl hover:bg-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <div class="flex items-center gap-2">
                <span class="text-lg font-extrabold text-slate-900">Edit Category: {{ $category->name }}</span>
                @if($category->is_active)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Live
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-600 border border-slate-200">
                        Draft
                    </span>
                @endif
            </div>
            <p class="text-xs text-slate-400">Slug: /category/{{ $category->slug }} • Assigned Products: {{ $category->products()->count() }}</p>
        </div>
    </div>
@endsection

@section('content')
<div class="max-w-5xl mx-auto space-y-6 sm:space-y-8"
     x-data="{ 
         name: '{{ addslashes(old('name', $category->name)) }}',
         slug: '{{ $category->slug }}',
         isActive: {{ old('is_active', $category->is_active ? 1 : 0) ? 'true' : 'false' }},
         showInHeader: {{ old('show_in_header', $category->show_in_header ? 1 : 0) ? 'true' : 'false' }}
     }">

    {{-- Top Action Toolbar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-lg text-slate-700 shrink-0">
                @if($category->parent)
                    ↳
                @else
                    📁
                @endif
            </div>
            <div>
                <h3 class="text-sm font-extrabold text-slate-900">{{ $category->name }}</h3>
                <div class="flex items-center gap-2 mt-0.5 text-xs text-slate-400">
                    <span>{{ $category->parent ? 'Parent: ' . $category->parent->name : 'Root Level Department' }}</span>
                    <span>•</span>
                    <span>{{ $category->products()->count() }} items listed</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap">
            <a href="{{ route('frontend.products.index', ['category' => $category->id]) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors shadow-2xs">
                <span>👁️ View on Store</span>
            </a>
            
            <a href="{{ route('admin.products.index', ['category' => $category->slug]) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 transition-colors shadow-2xs">
                <span>👕 View Products ({{ $category->products()->count() }})</span>
            </a>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
            
            {{-- Main Info Column (2/3 width) --}}
            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-7 shadow-xs space-y-5">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-base">
                            📁
                        </div>
                        <div>
                            <h3 class="text-sm font-heading font-extrabold text-slate-900">General Information</h3>
                            <p class="text-xs text-slate-400">Category name, parent tree, and story</p>
                        </div>
                    </div>

                    {{-- Category Name --}}
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Category Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               x-model="name"
                               value="{{ old('name', $category->name) }}" 
                               required
                               class="w-full px-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-900 outline-none transition-all @error('name') border-rose-500 @enderror">
                        @error('name') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Parent Category --}}
                    <div>
                        <label for="parent_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Parent Category Tree
                        </label>
                        <select id="parent_id" 
                                name="parent_id" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-900 outline-none transition-all cursor-pointer @error('parent_id') border-rose-500 @enderror">
                            <option value="">None (Top-Level Root Department)</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                    📁 {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-slate-400 mt-1.5">Leave blank to make this a root-level department (e.g. Clothing, Accessories).</p>
                        @error('parent_id') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Collection Description & Story
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="4"
                                  placeholder="Describe the aesthetic and drops featured in this collection..."
                                  class="w-full p-3 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all leading-relaxed @error('description') border-rose-500 @enderror">{{ old('description', $category->description) }}</textarea>
                        @error('description') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Live URL Snippet Preview --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-xs flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center text-sm font-bold shrink-0">
                        🌐
                    </div>
                    <div class="text-xs text-slate-500 min-w-0">
                        <span class="block font-bold text-slate-700">Storefront URL:</span>
                        <span class="font-mono text-slate-400 truncate block">https://threadax.co.in/category/<strong class="text-slate-900">{{ $category->slug }}</strong></span>
                    </div>
                </div>

            </div>

            {{-- Right Sidebar Column (1/3 width) --}}
            <div class="space-y-6">
                
                {{-- Status & Visibility Switches --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                            ⚙️
                        </div>
                        <h3 class="text-xs font-heading font-extrabold text-slate-900 uppercase tracking-wider">Visibility & Menus</h3>
                    </div>
                    
                    {{-- Active Toggle Switch --}}
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                        <div>
                            <span class="text-xs font-bold text-slate-900 block">Active Status</span>
                            <span class="text-[11px] text-slate-400">Show on store</span>
                        </div>
                        <button type="button" 
                                @click="isActive = !isActive" 
                                :class="isActive ? 'bg-slate-900' : 'bg-slate-200'" 
                                class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none shadow-2xs">
                            <span :class="isActive ? 'translate-x-5' : 'translate-x-0'" 
                                  class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"></span>
                        </button>
                        <input type="hidden" name="is_active" :value="isActive ? '1' : '0'">
                    </div>

                    {{-- Show in Header Nav Toggle Switch --}}
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                        <div>
                            <span class="text-xs font-bold text-slate-900 block">Header Navbar Link</span>
                            <span class="text-[11px] text-slate-400">Pin to top menu</span>
                        </div>
                        <button type="button" 
                                @click="showInHeader = !showInHeader" 
                                :class="showInHeader ? 'bg-purple-600' : 'bg-slate-200'" 
                                class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none shadow-2xs">
                            <span :class="showInHeader ? 'translate-x-5' : 'translate-x-0'" 
                                  class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"></span>
                        </button>
                        <input type="hidden" name="show_in_header" :value="showInHeader ? '1' : '0'">
                    </div>

                    {{-- Sort Order --}}
                    <div class="pt-2">
                        <label for="sort_order" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Display Sort Order
                        </label>
                        <input type="number" 
                               id="sort_order" 
                               name="sort_order" 
                               value="{{ old('sort_order', $category->sort_order) }}"
                               class="w-full px-3.5 py-2 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-mono font-bold text-slate-900 outline-none transition-all">
                        <p class="text-[11px] text-slate-400 mt-1">Lower numbers appear first (0, 1, 2...).</p>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs space-y-3">
                    <button type="submit" class="w-full py-3.5 px-6 rounded-xl text-xs font-extrabold text-white bg-slate-900 hover:bg-slate-800 transition-all shadow-sm hover:shadow-md flex justify-center items-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>Update Category</span>
                    </button>

                    <div class="text-center pt-1">
                        <a href="{{ route('admin.categories.index') }}" class="text-xs font-bold text-slate-400 hover:text-slate-700 transition-colors">
                            Discard & Go Back
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </form>

</div>
@endsection
