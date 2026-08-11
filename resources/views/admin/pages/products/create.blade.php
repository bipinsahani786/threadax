@extends('admin.layouts.app')

@section('title', 'Create Product')
@section('page-title')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.products.index') }}" class="text-slate-400 hover:text-slate-900 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <span>Create Product</span>
    </div>
@endsection

@section('content')

<form action="{{ route('admin.products.store') }}" method="POST">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Main Column --}}
        <div class="lg:col-span-2 space-y-8">
            
            {{-- Basic Info Card --}}
            <div class="bg-white rounded-[24px] shadow-[0_4px_24px_rgba(0,0,0,0.03)] border border-slate-100 p-8">
                <h3 class="text-lg font-bold text-slate-900 mb-6 font-heading">Basic Information</h3>
                
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-bold text-slate-900 mb-2">Product Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none transition-all text-sm @error('name') border-red-500 @enderror"
                               placeholder="e.g., Oversized Graphic Heavyweight Tee">
                        @error('name') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="short_description" class="block text-sm font-bold text-slate-900 mb-2">Short Description</label>
                        <textarea id="short_description" name="short_description" rows="2"
                                  class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none transition-all text-sm @error('short_description') border-red-500 @enderror"
                                  placeholder="A brief catchy description for product cards...">{{ old('short_description') }}</textarea>
                        @error('short_description') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-bold text-slate-900 mb-2">Full Description</label>
                        <textarea id="description" name="description" rows="6"
                                  class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none transition-all text-sm @error('description') border-red-500 @enderror"
                                  placeholder="Detailed product information, materials, fit..."></textarea>
                        @error('description') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Pricing & Inventory --}}
            <div class="bg-white rounded-[24px] shadow-[0_4px_24px_rgba(0,0,0,0.03)] border border-slate-100 p-8">
                <h3 class="text-lg font-bold text-slate-900 mb-6 font-heading">Pricing & Inventory</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="price" class="block text-sm font-bold text-slate-900 mb-2">Selling Price (₹) *</label>
                        <input type="number" step="0.01" id="price" name="price" value="{{ old('price') }}" required
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none transition-all text-sm @error('price') border-red-500 @enderror">
                        @error('price') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="compare_price" class="block text-sm font-bold text-slate-900 mb-2">Compare at Price / MRP (₹)</label>
                        <input type="number" step="0.01" id="compare_price" name="compare_price" value="{{ old('compare_price') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none transition-all text-sm @error('compare_price') border-red-500 @enderror">
                        <p class="mt-2 text-xs text-slate-500">Original price (strikethrough).</p>
                        @error('compare_price') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="sku" class="block text-sm font-bold text-slate-900 mb-2">Base SKU (Optional)</label>
                        <input type="text" id="sku" name="sku" value="{{ old('sku') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none transition-all text-sm @error('sku') border-red-500 @enderror"
                               placeholder="e.g., TEE-OS-BLK">
                        <p class="mt-2 text-xs text-slate-500">Variant specific SKUs can be added later.</p>
                        @error('sku') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
                
                <div class="mt-8 p-4 rounded-xl bg-blue-50 border border-blue-100 flex items-start gap-4">
                    <svg class="w-6 h-6 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <h4 class="text-sm font-bold text-blue-900">Manage Variants & Images</h4>
                        <p class="text-sm text-blue-700 mt-1">You can add product variants (sizes, colors), manage specific stock quantities, and upload images on the next screen after saving the basic product details.</p>
                    </div>
                </div>
            </div>
            
            {{-- Rich Media --}}
            <div class="bg-white rounded-[24px] shadow-[0_4px_24px_rgba(0,0,0,0.03)] border border-slate-100 p-8">
                <h3 class="text-lg font-bold text-slate-900 mb-6 font-heading">Rich Media URLs (Optional)</h3>
                
                <div class="space-y-6">
                    <div>
                        <label for="video_url" class="block text-sm font-bold text-slate-900 mb-2">Video URL</label>
                        <input type="url" id="video_url" name="video_url" value="{{ old('video_url') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none transition-all text-sm @error('video_url') border-red-500 @enderror"
                               placeholder="e.g., YouTube link or direct MP4 URL">
                        <p class="mt-2 text-xs text-slate-500">Will be embedded in the product gallery.</p>
                        @error('video_url') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="model_3d_url" class="block text-sm font-bold text-slate-900 mb-2">3D Model URL (.glb / .gltf)</label>
                        <input type="url" id="model_3d_url" name="model_3d_url" value="{{ old('model_3d_url') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none transition-all text-sm @error('model_3d_url') border-red-500 @enderror"
                               placeholder="e.g., https://example.com/model.glb">
                        <p class="mt-2 text-xs text-slate-500">For 3D interactive viewer on product page.</p>
                        @error('model_3d_url') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- SEO --}}
            <div class="bg-white rounded-[24px] shadow-[0_4px_24px_rgba(0,0,0,0.03)] border border-slate-100 p-8">
                <h3 class="text-lg font-bold text-slate-900 mb-6 font-heading">Search Engine Optimization</h3>
                
                <div class="space-y-6">
                    <div>
                        <label for="meta_title" class="block text-sm font-bold text-slate-900 mb-2">Meta Title</label>
                        <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none transition-all text-sm @error('meta_title') border-red-500 @enderror">
                        @error('meta_title') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="meta_description" class="block text-sm font-bold text-slate-900 mb-2">Meta Description</label>
                        <textarea id="meta_description" name="meta_description" rows="3"
                                  class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none transition-all text-sm @error('meta_description') border-red-500 @enderror"></textarea>
                        @error('meta_description') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
            
        </div>
        
        {{-- Sidebar Column --}}
        <div class="space-y-8">
            
            {{-- Organization --}}
            <div class="bg-white rounded-[24px] shadow-[0_4px_24px_rgba(0,0,0,0.03)] border border-slate-100 p-8">
                <h3 class="text-lg font-bold text-slate-900 mb-6 font-heading">Organization</h3>
                
                <div>
                    <label for="category_id" class="block text-sm font-bold text-slate-900 mb-2">Category *</label>
                    <select id="category_id" name="category_id" required
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none transition-all text-sm @error('category_id') border-red-500 @enderror bg-white">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->parent ? $category->parent->name . ' > ' : '' }}{{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>
            
            {{-- Status --}}
            <div class="bg-white rounded-[24px] shadow-[0_4px_24px_rgba(0,0,0,0.03)] border border-slate-100 p-8">
                <h3 class="text-lg font-bold text-slate-900 mb-6 font-heading">Status</h3>
                
                <div class="space-y-6">
                    <div class="flex items-center gap-3">
                        <input type="hidden" name="is_active" value="0">
                        <div class="relative inline-block w-12 mr-2 align-middle select-none transition duration-200 ease-in">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 border-slate-200 appearance-none cursor-pointer transition-transform duration-200 ease-in-out checked:border-slate-900 checked:translate-x-6 z-10"/>
                            <label for="is_active" class="toggle-label block overflow-hidden h-6 rounded-full bg-slate-200 cursor-pointer"></label>
                        </div>
                        <div>
                            <label for="is_active" class="text-sm font-bold text-slate-900 cursor-pointer block">Active</label>
                            <span class="text-xs text-slate-500">Show on store</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <input type="hidden" name="is_featured" value="0">
                        <div class="relative inline-block w-12 mr-2 align-middle select-none transition duration-200 ease-in">
                            <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', false) ? 'checked' : '' }} class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 border-slate-200 appearance-none cursor-pointer transition-transform duration-200 ease-in-out checked:border-slate-900 checked:translate-x-6 z-10"/>
                            <label for="is_featured" class="toggle-label block overflow-hidden h-6 rounded-full bg-slate-200 cursor-pointer"></label>
                        </div>
                        <div>
                            <label for="is_featured" class="text-sm font-bold text-slate-900 cursor-pointer block">Featured</label>
                            <span class="text-xs text-slate-500">Show on homepage</span>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Submit --}}
            <div class="bg-white rounded-[24px] shadow-[0_4px_24px_rgba(0,0,0,0.03)] border border-slate-100 p-8 text-center">
                <button type="submit" class="w-full mb-4 px-6 py-3.5 rounded-xl text-sm font-bold text-white bg-slate-900 hover:bg-slate-800 transition-shadow shadow-[0_4px_12px_rgba(0,0,0,0.1)] hover:shadow-[0_4px_16px_rgba(0,0,0,0.15)] flex justify-center items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                    Save & Proceed to Variants
                </button>
                <a href="{{ route('admin.products.index') }}" class="text-sm font-bold text-slate-500 hover:text-slate-900 transition-colors">
                    Discard changes
                </a>
            </div>
            
        </div>
    </div>
</form>

@push('styles')
<style>
    /* Custom Toggle Switch Styles */
    .toggle-checkbox:checked {
        right: 0;
        border-color: #0F172A; /* slate-900 */
    }
    .toggle-checkbox:checked + .toggle-label {
        background-color: #0F172A;
    }
</style>
@endpush
@endsection
