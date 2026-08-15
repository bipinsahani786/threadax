@extends('admin.layouts.app')

@section('title', isset($banner) ? 'Edit Banner - Admin' : 'Add Banner - Admin')
@section('page-title')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.banners.index') }}" class="text-slate-400 hover:text-slate-900 transition-colors p-1.5 rounded-xl hover:bg-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <span class="text-lg font-extrabold text-slate-900">{{ isset($banner) ? 'Edit Visual Banner' : 'Create Visual Banner' }}</span>
            <p class="text-xs text-slate-400">Configure storefront promotional slides and hero carousels</p>
        </div>
    </div>
@endsection

@section('content')
<div class="max-w-5xl mx-auto space-y-6 sm:space-y-8"
     x-data="{ 
         title: '{{ addslashes(old('title', $banner->title ?? '')) }}',
         subtitle: '{{ addslashes(old('subtitle', $banner->subtitle ?? '')) }}',
         buttonText: '{{ addslashes(old('button_text', $banner->button_text ?? 'Shop Collection')) }}',
         position: '{{ old('position', $banner->position ?? 'hero') }}',
         isActive: {{ old('is_active', isset($banner) ? ($banner->is_active ? 1 : 0) : 1) ? 'true' : 'false' }},
         imagePreview: '{{ isset($banner) && $banner->image_path ? asset('storage/' . $banner->image_path) : '' }}',

         previewFile(event) {
             const file = event.target.files[0];
             if (file) {
                 this.imagePreview = URL.createObjectURL(file);
             }
         }
     }">

    {{-- Form --}}
    <form action="{{ isset($banner) ? route('admin.banners.update', $banner) : route('admin.banners.store') }}" 
          method="POST" 
          enctype="multipart/form-data">
        @csrf
        @if(isset($banner))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
            
            {{-- Left / Main Column (2/3 width) --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Banner Content Card --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-7 shadow-xs space-y-5">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-base">
                            🖼️
                        </div>
                        <div>
                            <h3 class="text-sm font-heading font-extrabold text-slate-900">Banner Details</h3>
                            <p class="text-xs text-slate-400">Headlines, call-to-actions, and landing page URLs</p>
                        </div>
                    </div>

                    {{-- Title --}}
                    <div>
                        <label for="title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Banner Title / Headline <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               x-model="title"
                               value="{{ old('title', $banner->title ?? '') }}" 
                               required
                               placeholder="e.g. OVERSIZED ESSENTIALS // DROP 04"
                               class="w-full px-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-900 outline-none transition-all @error('title') border-rose-500 @enderror">
                        @error('title') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Subtitle --}}
                    <div>
                        <label for="subtitle" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Subtitle / Tagline
                        </label>
                        <input type="text" 
                               id="subtitle" 
                               name="subtitle" 
                               x-model="subtitle"
                               value="{{ old('subtitle', $banner->subtitle ?? '') }}" 
                               placeholder="e.g. 280 GSM French Terry Cotton. Engineered for luxury comfort."
                               class="w-full px-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all @error('subtitle') border-rose-500 @enderror">
                        @error('subtitle') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Grid for Link & Button Text --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Target Link --}}
                        <div>
                            <label for="link" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Destination Link URL
                            </label>
                            <input type="text" 
                                   id="link" 
                                   name="link" 
                                   value="{{ old('link', $banner->link ?? '') }}" 
                                   placeholder="e.g. /shop?category=t-shirts"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-mono font-medium text-slate-900 outline-none transition-all">
                        </div>

                        {{-- Button Text --}}
                        <div>
                            <label for="button_text" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Button Call-To-Action Text
                            </label>
                            <input type="text" 
                                   id="button_text" 
                                   name="button_text" 
                                   x-model="buttonText"
                                   value="{{ old('button_text', $banner->button_text ?? 'Shop Collection') }}" 
                                   placeholder="e.g. Explore Drop, Shop Now"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-900 outline-none transition-all">
                        </div>
                    </div>
                </div>

                {{-- Banner Visual Upload & Live Simulator --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-7 shadow-xs space-y-5">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-base">
                            📸
                        </div>
                        <div>
                            <h3 class="text-sm font-heading font-extrabold text-slate-900">Visual Media & Simulator</h3>
                            <p class="text-xs text-slate-400">High-resolution artwork & live storefront layout preview</p>
                        </div>
                    </div>

                    {{-- Image Upload Box --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Banner Artwork Image {{ isset($banner) ? '' : '*' }}
                        </label>
                        
                        <div class="border-2 border-dashed border-slate-200 hover:border-slate-400 rounded-2xl p-6 text-center transition-colors bg-slate-50/50">
                            <input type="file" 
                                   name="image" 
                                   id="image_input" 
                                   accept="image/*" 
                                   @change="previewFile"
                                   {{ isset($banner) ? '' : 'required' }}
                                   class="hidden">
                            
                            <label for="image_input" class="cursor-pointer flex flex-col items-center justify-center gap-2">
                                <div class="w-12 h-12 rounded-xl bg-white border border-slate-200 shadow-2xs flex items-center justify-center text-xl">
                                    📁
                                </div>
                                <span class="text-xs font-extrabold text-slate-900">Click to upload high-res banner image</span>
                                <span class="text-[11px] text-slate-400">Recommended: 1920x800 for Hero, 1440x500 for Promos (Max: 4MB)</span>
                            </label>
                        </div>
                        @error('image') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Live Simulator Card --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Live Storefront Simulator Preview
                        </label>
                        <div class="relative aspect-video rounded-2xl overflow-hidden bg-slate-950 border border-slate-800 shadow-inner flex items-center justify-center">
                            
                            <template x-if="imagePreview">
                                <img :src="imagePreview" alt="Preview" class="absolute inset-0 w-full h-full object-cover">
                            </template>

                            <template x-if="!imagePreview">
                                <div class="text-slate-600 font-bold text-xs">Upload an image to see live preview</div>
                            </template>

                            {{-- Dark Overlay Gradients --}}
                            <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-transparent"></div>

                            {{-- Simulated Content Overlay --}}
                            <div class="absolute inset-y-0 left-0 flex flex-col justify-center p-6 sm:p-8 max-w-md text-white z-10 space-y-2">
                                <span class="text-[10px] font-black tracking-widest uppercase text-amber-400">THREADAX EXCLUSIVE</span>
                                <h2 class="text-lg sm:text-2xl font-black font-heading leading-tight" x-text="title || 'BANNER HEADLINE PREVIEW'"></h2>
                                <p class="text-xs text-slate-300 line-clamp-2" x-text="subtitle || 'Subtitle and drop description will appear here...'"></p>
                                <div class="pt-2">
                                    <span class="inline-block px-4 py-2 bg-white text-slate-950 font-black text-[11px] rounded-lg tracking-wider uppercase shadow-md" x-text="buttonText || 'Shop Collection'"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Right Column (1/3 width) --}}
            <div class="space-y-6">
                
                {{-- Placement & Visibility Settings --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs space-y-5">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                            ⚙️
                        </div>
                        <h3 class="text-xs font-heading font-extrabold text-slate-900 uppercase tracking-wider">Placement & Order</h3>
                    </div>

                    {{-- Position Selection --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Storefront Position <span class="text-rose-500">*</span>
                        </label>
                        <select name="position" 
                                x-model="position"
                                required
                                class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-900 outline-none transition-all cursor-pointer">
                            <option value="hero">🎯 Hero Carousel (Homepage Top)</option>
                            <option value="mid">📌 Mid Section Promo Banner</option>
                            <option value="bottom">🔻 Bottom Collection Banner</option>
                        </select>
                    </div>

                    {{-- Sort Order --}}
                    <div>
                        <label for="sort_order" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Carousel Sort Order <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" 
                               id="sort_order" 
                               name="sort_order" 
                               value="{{ old('sort_order', $banner->sort_order ?? 0) }}" 
                               required
                               class="w-full px-3.5 py-2 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-mono font-bold text-slate-900 outline-none transition-all">
                        <p class="text-[11px] text-slate-400 mt-1">Lower numbers appear first (0, 1, 2...).</p>
                    </div>

                    {{-- Active Toggle Switch --}}
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                        <div>
                            <span class="text-xs font-bold text-slate-900 block">Active Status</span>
                            <span class="text-[11px] text-slate-400">Show on website</span>
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
                </div>

                {{-- Action Buttons --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs space-y-3">
                    <button type="submit" class="w-full py-3.5 px-6 rounded-xl text-xs font-extrabold text-white bg-slate-900 hover:bg-slate-800 transition-all shadow-sm hover:shadow-md flex justify-center items-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ isset($banner) ? 'Update Visual Banner' : 'Save & Publish Banner' }}</span>
                    </button>

                    <div class="text-center pt-1">
                        <a href="{{ route('admin.banners.index') }}" class="text-xs font-bold text-slate-400 hover:text-slate-700 transition-colors">
                            Discard & Go Back
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </form>

</div>
@endsection
