@extends('admin.layouts.app')

@section('title', 'Create Page - Admin')
@section('page-title')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.pages.index') }}" class="text-slate-400 hover:text-slate-900 transition-colors p-1.5 rounded-xl hover:bg-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <span class="text-lg font-extrabold text-slate-900">Create Static CMS Page</span>
            <p class="text-xs text-slate-400">Design legal disclosures, customer policies, and brand narratives</p>
        </div>
    </div>
@endsection

@section('content')
<div class="max-w-5xl mx-auto space-y-6 sm:space-y-8"
     x-data="{
         title: '{{ addslashes(old('title', '')) }}',
         slug: '{{ addslashes(old('slug', '')) }}',
         metaTitle: '{{ addslashes(old('meta_title', '')) }}',
         metaDescription: '{{ addslashes(old('meta_description', '')) }}',
         isActive: {{ old('is_active', true) ? 'true' : 'false' }},

         generateSlug() {
             if (!this.slug || this.slug.trim() === '') {
                 this.slug = this.title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
             }
         }
     }">

    {{-- Form --}}
    <form action="{{ route('admin.pages.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
            
            {{-- Main Content Column (2/3 width) --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Page Content Card --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-7 shadow-xs space-y-5">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-base">
                            📄
                        </div>
                        <div>
                            <h3 class="text-sm font-heading font-extrabold text-slate-900">Page Content</h3>
                            <p class="text-xs text-slate-400">Document structure, text, and policies</p>
                        </div>
                    </div>

                    {{-- Title --}}
                    <div>
                        <label for="title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Page Title <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               x-model="title"
                               @blur="generateSlug()"
                               value="{{ old('title') }}" 
                               required
                               placeholder="e.g. Terms & Conditions, Shipping Policy..."
                               class="w-full px-4 py-3 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-sm font-bold text-slate-900 outline-none transition-all @error('title') border-rose-500 @enderror">
                        @error('title') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Slug --}}
                    <div>
                        <label for="slug" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            URL Slug Path <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 text-xs font-mono">/page/</span>
                            <input type="text" 
                                   id="slug" 
                                   name="slug" 
                                   x-model="slug"
                                   value="{{ old('slug') }}" 
                                   placeholder="terms-and-conditions"
                                   class="w-full pl-16 pr-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-mono font-bold text-slate-900 outline-none transition-all">
                        </div>
                        @error('slug') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Body --}}
                    <div>
                        <label for="body" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Page Body Content <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="body" 
                                  name="body" 
                                  rows="14"
                                  required
                                  placeholder="Write the full policy or page content here... HTML tags like <h2>, <p>, <ul>, <li> are fully supported."
                                  class="w-full p-4 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-mono font-medium text-slate-900 outline-none transition-all leading-relaxed @error('body') border-rose-500 @enderror">{{ old('body') }}</textarea>
                        @error('body') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- SEO Settings --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-7 shadow-xs space-y-4">
                    <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-sm">
                            🔍
                        </div>
                        <div>
                            <h3 class="text-xs font-heading font-extrabold text-slate-900 uppercase tracking-wider">Search Engine Optimization (SEO)</h3>
                        </div>
                    </div>

                    <div>
                        <label for="meta_title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Meta Title</label>
                        <input type="text" 
                               id="meta_title" 
                               name="meta_title" 
                               x-model="metaTitle"
                               value="{{ old('meta_title') }}" 
                               class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all">
                    </div>

                    <div>
                        <label for="meta_description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Meta Description</label>
                        <textarea id="meta_description" 
                                  name="meta_description" 
                                  x-model="metaDescription"
                                  rows="2" 
                                  class="w-full p-3 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all leading-relaxed">{{ old('meta_description') }}</textarea>
                    </div>
                </div>

            </div>

            {{-- Right Sidebar Column (1/3 width) --}}
            <div class="space-y-6">
                
                {{-- Status & Visibility --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                            ⚙️
                        </div>
                        <h3 class="text-xs font-heading font-extrabold text-slate-900 uppercase tracking-wider">Publish Status</h3>
                    </div>

                    {{-- Active Toggle Switch --}}
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                        <div>
                            <span class="text-xs font-bold text-slate-900 block">Active Status</span>
                            <span class="text-[11px] text-slate-400">Accessible on store</span>
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
                        <span>Create & Publish Page</span>
                    </button>

                    <div class="text-center pt-1">
                        <a href="{{ route('admin.pages.index') }}" class="text-xs font-bold text-slate-400 hover:text-slate-700 transition-colors">
                            Discard & Go Back
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </form>

</div>
@endsection
