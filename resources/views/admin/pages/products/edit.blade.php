@extends('admin.layouts.app')

@section('title', 'Edit Product - ' . $product->name)
@section('page-title')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.products.index') }}" class="text-slate-400 hover:text-slate-900 transition-colors p-1.5 rounded-xl hover:bg-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <div class="flex items-center gap-2">
                <span class="text-lg font-extrabold text-slate-900">Edit Drop: {{ $product->name }}</span>
                @if($product->is_active)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Live
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-600 border border-slate-200">
                        Draft
                    </span>
                @endif
            </div>
            <p class="text-xs text-slate-400">SKU: {{ $product->sku ?? 'TX-GEN' }} • Category: {{ $product->category->name ?? 'Streetwear' }}</p>
        </div>
    </div>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-6 sm:space-y-8" 
     x-data="{ 
         price: {{ old('price', (float)$product->price) }}, 
         comparePrice: {{ old('compare_price', (float)($product->compare_price ?? 0)) }},
         name: '{{ addslashes(old('name', $product->name)) }}',
         metaTitle: '{{ addslashes(old('meta_title', $product->meta_title ?? '')) }}',
         metaDescription: '{{ addslashes(old('meta_description', $product->meta_description ?? '')) }}',
         isActive: {{ old('is_active', $product->is_active ? 1 : 0) ? 'true' : 'false' }},
         isFeatured: {{ old('is_featured', $product->is_featured ? 1 : 0) ? 'true' : 'false' }},

         get discountPercent() {
             if (this.comparePrice > this.price && this.comparePrice > 0) {
                 return Math.round(((this.comparePrice - this.price) / this.comparePrice) * 100);
             }
             return 0;
         },
         get discountSavings() {
             if (this.comparePrice > this.price && this.comparePrice > 0) {
                 return (this.comparePrice - this.price).toFixed(2);
             }
             return 0;
         },
         ...productAiHelper()
     }">

    {{-- Top Action Toolbar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-3">
            <div class="w-12 h-14 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center">
                @if($product->primaryImage)
                    <img src="{{ $product->primaryImage->url }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
                @else
                    <span class="font-bold text-slate-400 text-xs">TX</span>
                @endif
            </div>
            <div>
                <h3 class="text-sm font-extrabold text-slate-900 line-clamp-1">{{ $product->name }}</h3>
                <div class="flex items-center gap-2 mt-1 text-xs text-slate-400">
                    <span>{{ $product->variants->count() }} Variants ({{ $product->variants->sum('stock') }} units in stock)</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap">
            {{-- Live Preview --}}
            <a href="{{ route('frontend.products.show', $product->slug) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors">
                <span>👁️ View Live</span>
            </a>

            {{-- Manage Variants & Media --}}
            <a href="{{ route('admin.products.variants', $product) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-purple-50 hover:bg-purple-100 text-purple-700 border border-purple-200 transition-colors">
                <span>⚡ Variants & Images ({{ $product->variants->count() }})</span>
            </a>

            {{-- Duplicate --}}
            <form action="{{ route('admin.products.duplicate', $product) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors cursor-pointer" title="Duplicate as Draft">
                    <span>📋 Duplicate</span>
                </button>
            </form>

            {{-- ✨ AI Studio Quick Button --}}
            <button type="button" 
                    @click="openAiModal('copy')" 
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white shadow-xs hover:shadow-md transition-all active:scale-95 cursor-pointer">
                <span class="animate-pulse text-amber-300">✨</span>
                <span>AI Studio</span>
            </button>
        </div>
    </div>

    {{-- Main Form --}}
    <form action="{{ route('admin.products.update', $product) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
            
            {{-- Main Column (2/3 width) --}}
            <div class="lg:col-span-2 space-y-6 sm:space-y-8">
                
                {{-- 1. Basic Information Card --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-7 shadow-xs">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100 flex-wrap gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-base">
                                👕
                            </div>
                            <div>
                                <h3 class="text-sm font-heading font-extrabold text-slate-900">General Information</h3>
                                <p class="text-xs text-slate-400">Title, product story and detailed descriptions</p>
                            </div>
                        </div>

                        {{-- ✨ Gemini AI Auto-Fill Action --}}
                        <button type="button" 
                                @click="openAiModal('copy')" 
                                class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-purple-600 via-indigo-600 to-purple-700 hover:from-purple-700 hover:to-indigo-800 text-white text-xs font-bold shadow-xs hover:shadow-md transition-all active:scale-95 cursor-pointer">
                            <span class="animate-pulse text-amber-300">✨</span>
                            <span>AI Auto-Generate (Gemini)</span>
                        </button>
                    </div>
                    
                    <div class="space-y-5">
                        {{-- Product Name --}}
                        <div>
                            <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Product Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   x-model="name"
                                   value="{{ old('name', $product->name) }}" 
                                   required
                                   class="w-full px-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-900 outline-none transition-all @error('name') border-rose-500 @enderror">
                            @error('name') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Short Description --}}
                        <div>
                            <label for="short_description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Short Catchy Subtitle (Product Card & Preview)
                            </label>
                            <textarea id="short_description" 
                                      name="short_description" 
                                      rows="2"
                                      placeholder="A brief punchy hook (e.g. Heavyweight 240 GSM drop-shoulder streetwear cut)..."
                                      class="w-full p-3 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all leading-relaxed @error('short_description') border-rose-500 @enderror">{{ old('short_description', $product->short_description) }}</textarea>
                            @error('short_description') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Full Description --}}
                        <div>
                            <label for="description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Full Product Description & Fit Details
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="5"
                                      placeholder="Fabric composition, wash care instructions, model sizing reference, etc..."
                                      class="w-full p-3 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all leading-relaxed @error('description') border-rose-500 @enderror">{{ old('description', $product->description) }}</textarea>
                            @error('description') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- 2. Pricing & Base SKU Card --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-7 shadow-xs">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-base">
                                🏷️
                            </div>
                            <div>
                                <h3 class="text-sm font-heading font-extrabold text-slate-900">Pricing & Base SKU</h3>
                                <p class="text-xs text-slate-400">Regular selling price and compare-at MRP calculation</p>
                            </div>
                        </div>
                        
                        {{-- Live Discount Pill --}}
                        <div x-show="discountPercent > 0" class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-700 border border-rose-200 px-3 py-1 rounded-full text-xs font-black">
                            <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                            <span x-text="discountPercent + '% OFF (Save ₹' + discountSavings + ')'"></span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        {{-- Selling Price --}}
                        <div>
                            <label for="price" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Selling Price (₹) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-bold text-xs">₹</span>
                                <input type="number" 
                                       step="0.01" 
                                       id="price" 
                                       name="price" 
                                       x-model.number="price"
                                       value="{{ old('price', $product->price) }}" 
                                       required
                                       class="w-full pl-8 pr-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-extrabold text-slate-900 outline-none transition-all @error('price') border-rose-500 @enderror">
                            </div>
                            @error('price') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Compare At MRP --}}
                        <div>
                            <label for="compare_price" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Compare At Price / MRP (₹)
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-bold text-xs">₹</span>
                                <input type="number" 
                                       step="0.01" 
                                       id="compare_price" 
                                       name="compare_price" 
                                       x-model.number="comparePrice"
                                       value="{{ old('compare_price', $product->compare_price) }}" 
                                       placeholder="Optional (Strikethrough)"
                                       class="w-full pl-8 pr-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-extrabold text-slate-900 outline-none transition-all @error('compare_price') border-rose-500 @enderror">
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1">Leave empty or higher than price to show discount badge.</p>
                            @error('compare_price') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        
                        {{-- Base SKU --}}
                        <div class="sm:col-span-2">
                            <label for="sku" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Base Master SKU
                            </label>
                            <input type="text" 
                                   id="sku" 
                                   name="sku" 
                                   value="{{ old('sku', $product->sku) }}"
                                   placeholder="e.g. TX-VWT-001-GRY"
                                   class="w-full px-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-mono font-bold text-slate-900 outline-none transition-all @error('sku') border-rose-500 @enderror">
                            @error('sku') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
                
                {{-- 3. Rich Media & 3D Model URLs Card --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-7 shadow-xs">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                        <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-base">
                            🎬
                        </div>
                        <div>
                            <h3 class="text-sm font-heading font-extrabold text-slate-900">Interactive Rich Media</h3>
                            <p class="text-xs text-slate-400">Optional video reels, 3D model viewer, and size guide assets</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        {{-- Video URL --}}
                        <div>
                            <label for="video_url" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Lookbook Video URL (YouTube / MP4)
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">🎥</span>
                                <input type="url" 
                                       id="video_url" 
                                       name="video_url" 
                                       value="{{ old('video_url', $product->video_url) }}"
                                       placeholder="e.g. https://www.youtube.com/watch?v=..."
                                       class="w-full pl-9 pr-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all @error('video_url') border-rose-500 @enderror">
                            </div>
                        </div>

                        {{-- 3D Model URL --}}
                        <div>
                            <label for="model_3d_url" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                3D AR Model URL (.glb / .gltf)
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">👓</span>
                                <input type="url" 
                                       id="model_3d_url" 
                                       name="model_3d_url" 
                                       value="{{ old('model_3d_url', $product->model_3d_url) }}"
                                       placeholder="e.g. https://cdn.threadax.co.in/models/tee.glb"
                                       class="w-full pl-9 pr-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all @error('model_3d_url') border-rose-500 @enderror">
                            </div>
                        </div>

                        {{-- Size Guide URL --}}
                        <div>
                            <label for="size_guide_url" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Custom Sizing Chart Image URL
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">📏</span>
                                <input type="url" 
                                       id="size_guide_url" 
                                       name="size_guide_url" 
                                       value="{{ old('size_guide_url', $product->size_guide_url) }}"
                                       placeholder="e.g. https://example.com/oversized-tee-chart.jpg"
                                       class="w-full pl-9 pr-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all @error('size_guide_url') border-rose-500 @enderror">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 4. Google SERP SEO Preview Card --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-7 shadow-xs">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-base">
                            🔍
                        </div>
                        <div>
                            <h3 class="text-sm font-heading font-extrabold text-slate-900">Search Engine Optimization (SEO)</h3>
                            <p class="text-xs text-slate-400">Live preview of how this product appears on Google Search</p>
                        </div>
                    </div>

                    {{-- Google Search Result Snippet Preview --}}
                    <div class="mb-6 p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
                        <div class="text-[11px] text-slate-500 font-mono flex items-center gap-1.5">
                            <span class="w-3.5 h-3.5 rounded-full bg-slate-200 flex items-center justify-center text-[9px]">🌐</span>
                            <span>https://threadax.co.in &rsaquo; product &rsaquo; {{ $product->slug }}</span>
                        </div>
                        <h4 class="text-sm font-bold text-blue-700 hover:underline cursor-pointer" x-text="metaTitle || name || 'Product Title on Google'"></h4>
                        <p class="text-xs text-slate-600 leading-relaxed line-clamp-2" x-text="metaDescription || 'Buy the latest ' + name + ' from ThreadAX. Premium streetwear, fast delivery and easy returns.'"></p>
                    </div>
                    
                    <div class="space-y-4">
                        {{-- Meta Title --}}
                        <div>
                            <label for="meta_title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Meta Title Tag
                            </label>
                            <input type="text" 
                                   id="meta_title" 
                                   name="meta_title" 
                                   x-model="metaTitle"
                                   value="{{ old('meta_title', $product->meta_title) }}"
                                   placeholder="e.g. Essential Oversized Streetwear Tee — ThreadAX"
                                   class="w-full px-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all">
                        </div>

                        {{-- Meta Description --}}
                        <div>
                            <label for="meta_description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Meta Description
                            </label>
                            <textarea id="meta_description" 
                                      name="meta_description" 
                                      x-model="metaDescription"
                                      rows="2"
                                      placeholder="Compelling 150-160 character summary for search engines..."
                                      class="w-full p-3 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all leading-relaxed">{{ old('meta_description', $product->meta_description) }}</textarea>
                        </div>
                    </div>
                </div>
                
            </div>
            
            {{-- Right Sidebar Column (1/3 width) --}}
            <div class="space-y-6 sm:space-y-8">
                
                {{-- 1. Category Organization --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs">
                    <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-sm">
                            📁
                        </div>
                        <div>
                            <h3 class="text-xs font-heading font-extrabold text-slate-900 uppercase tracking-wider">Category Collection</h3>
                        </div>
                    </div>
                    
                    <div>
                        <label for="category_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Select Category <span class="text-rose-500">*</span>
                        </label>
                        <select id="category_id" 
                                name="category_id" 
                                required
                                class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-900 outline-none transition-all cursor-pointer">
                            <option value="">Choose Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->parent ? $category->parent->name . ' > ' : '' }}{{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>
                
                {{-- 2. Clean Status & Visibility Switches --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                            ⚙️
                        </div>
                        <div>
                            <h3 class="text-xs font-heading font-extrabold text-slate-900 uppercase tracking-wider">Status & Visibility</h3>
                        </div>
                    </div>
                    
                    {{-- Active Toggle Switch --}}
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                        <div>
                            <span class="text-xs font-bold text-slate-900 block">Live on Storefront</span>
                            <span class="text-[11px] text-slate-400">Enable purchasing</span>
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

                    {{-- Featured Toggle Switch --}}
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                        <div>
                            <span class="text-xs font-bold text-slate-900 block">Featured Drop</span>
                            <span class="text-[11px] text-slate-400">Spotlight on homepage</span>
                        </div>
                        <button type="button" 
                                @click="isFeatured = !isFeatured" 
                                :class="isFeatured ? 'bg-amber-500' : 'bg-slate-200'" 
                                class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none shadow-2xs">
                            <span :class="isFeatured ? 'translate-x-5' : 'translate-x-0'" 
                                  class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"></span>
                        </button>
                        <input type="hidden" name="is_featured" :value="isFeatured ? '1' : '0'">
                    </div>
                </div>
                
                {{-- 3. Update CTA Card --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs space-y-3">
                    <button type="submit" class="w-full py-3.5 px-6 rounded-xl text-xs font-extrabold text-white bg-slate-900 hover:bg-slate-800 transition-all shadow-sm hover:shadow-md flex justify-center items-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>Update Product</span>
                    </button>
                    
                    <a href="{{ route('admin.products.variants', $product) }}" class="w-full py-3 px-6 rounded-xl text-xs font-bold text-purple-700 bg-purple-50 hover:bg-purple-100 border border-purple-200 transition-all flex justify-center items-center gap-2">
                        <span>⚡ Edit Variants & Photos</span>
                    </a>

                    <div class="text-center pt-1">
                        <a href="{{ route('admin.products.index') }}" class="text-xs font-bold text-slate-400 hover:text-slate-700 transition-colors">
                            Discard & Go Back
                        </a>
                    </div>
                </div>
                
            </div>
        </div>
    </form>

    {{-- ✨ AI Assistant Modal (Gemini Copy & Photoshoot) --}}
    @include('admin.pages.products.partials.ai_modal')

</div>
@endsection

@push('styles')
<style>
    /* CKEditor 5 Modern Admin Theme */
    .ck.ck-editor {
        width: 100% !important;
    }
    .ck-editor__editable_inline {
        min-height: 220px !important;
        max-height: 460px !important;
        font-family: 'Inter', sans-serif !important;
        font-size: 13.5px !important;
        line-height: 1.6 !important;
        border-bottom-left-radius: 12px !important;
        border-bottom-right-radius: 12px !important;
        padding: 14px 18px !important;
        color: #0F172A !important;
        background-color: #F8FAFC !important;
    }
    .ck.ck-toolbar {
        border-top-left-radius: 12px !important;
        border-top-right-radius: 12px !important;
        background-color: #F1F5F9 !important;
        border-color: #E2E8F0 !important;
    }
    .ck.ck-editor__main>.ck-editor__editable {
        border-color: #E2E8F0 !important;
    }
    .ck.ck-editor__main>.ck-editor__editable:focus {
        border-color: #94A3B8 !important;
        box-shadow: 0 0 0 3px rgba(148, 163, 184, 0.15) !important;
        background-color: #FFFFFF !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const descEl = document.querySelector('#description');
    if (descEl) {
        ClassicEditor
            .create(descEl, {
                toolbar: [
                    'heading', '|', 
                    'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|', 
                    'undo', 'redo'
                ],
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                    ]
                }
            })
            .then(editor => {
                window.productCkEditor = editor;
                editor.model.document.on('change:data', () => {
                    descEl.value = editor.getData();
                });
                const form = descEl.closest('form');
                if (form) {
                    form.addEventListener('submit', () => {
                        descEl.value = editor.getData();
                    });
                }
            })
            .catch(error => {
                console.error('CKEditor Init Error on Product Edit:', error);
            });
    }
});
</script>
@endpush
