@extends('admin.layouts.app')

@section('title', 'Write Article - Admin')
@section('page-title')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.blogs.index') }}" class="text-slate-400 hover:text-slate-900 transition-colors p-1.5 rounded-xl hover:bg-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <span class="text-lg font-extrabold text-slate-900">Write New Editorial Article</span>
            <p class="text-xs text-slate-400">Compose streetwear lookbooks, fashion culture drops, and style guides</p>
        </div>
    </div>
@endsection

@section('content')
<div class="max-w-5xl mx-auto space-y-6 sm:space-y-8"
     x-data="{
         title: '{{ addslashes(old('title', '')) }}',
         metaTitle: '{{ addslashes(old('meta_title', '')) }}',
         metaDescription: '{{ addslashes(old('meta_description', '')) }}',
         body: `{{ addslashes(old('body', '')) }}`,
         imagePreview: '',

         previewFile(event) {
             const file = event.target.files[0];
             if (file) {
                 this.imagePreview = URL.createObjectURL(file);
             }
         },

         get readingTime() {
             const words = this.body.trim().split(/\s+/).length;
             return Math.ceil(words / 200) || 1;
         }
     }">

    {{-- Form --}}
    <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
            
            {{-- Main Content Column (2/3 width) --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Article Content Card --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-7 shadow-xs space-y-5">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-base">
                            ✍️
                        </div>
                        <div>
                            <h3 class="text-sm font-heading font-extrabold text-slate-900">Article Content</h3>
                            <p class="text-xs text-slate-400">Headlines, editorial story, and excerpts</p>
                        </div>
                    </div>

                    {{-- Title --}}
                    <div>
                        <label for="title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Article Headline / Title <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               x-model="title"
                               value="{{ old('title') }}" 
                               required
                               placeholder="e.g. The Evolution of Heavyweight Streetwear: A ThreadAX Deep Dive"
                               class="w-full px-4 py-3 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-sm font-bold text-slate-900 outline-none transition-all @error('title') border-rose-500 @enderror">
                        @error('title') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Excerpt --}}
                    <div>
                        <label for="excerpt" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Short Excerpt / Summary
                        </label>
                        <textarea id="excerpt" 
                                  name="excerpt" 
                                  rows="2"
                                  placeholder="Brief 1-2 sentence teaser for blog listing cards and social feeds..."
                                  class="w-full p-3.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all leading-relaxed">{{ old('excerpt') }}</textarea>
                    </div>

                    {{-- Body Content --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="body" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Story Content <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[11px] font-bold text-slate-400">
                                ⏱️ Est. <span x-text="readingTime"></span> min read
                            </span>
                        </div>
                        <textarea id="body" 
                                  name="body" 
                                  x-model="body"
                                  rows="14"
                                  required
                                  placeholder="Write your article body here... HTML tags like <h2>, <p>, <blockquote>, <ul> are fully supported."
                                  class="w-full p-4 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-mono font-medium text-slate-900 outline-none transition-all leading-relaxed @error('body') border-rose-500 @enderror">{{ old('body') }}</textarea>
                        @error('body') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Google SERP SEO Preview Card --}}
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
                               placeholder="e.g. Heavyweight Streetwear Guide | ThreadAX Editorial"
                               class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all">
                    </div>

                    <div>
                        <label for="meta_description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Meta Description</label>
                        <textarea id="meta_description" 
                                  name="meta_description" 
                                  x-model="metaDescription"
                                  rows="2" 
                                  placeholder="Describe the article for Google search snippets..."
                                  class="w-full p-3 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all leading-relaxed">{{ old('meta_description') }}</textarea>
                    </div>

                    {{-- Live Google Snippet Preview --}}
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/80 space-y-1">
                        <div class="text-[11px] text-slate-400 font-mono">https://threadax.co.in › blog</div>
                        <div class="text-sm font-bold text-blue-700 line-clamp-1" x-text="metaTitle || title || 'Article Title - ThreadAX'"></div>
                        <div class="text-xs text-slate-600 line-clamp-2" x-text="metaDescription || 'Article preview snippet will appear in Google search results...'"></div>
                    </div>
                </div>

            </div>

            {{-- Right Sidebar Column (1/3 width) --}}
            <div class="space-y-6">
                
                {{-- Publishing & Metadata --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs space-y-5">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                            ⚙️
                        </div>
                        <h3 class="text-xs font-heading font-extrabold text-slate-900 uppercase tracking-wider">Publishing Details</h3>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Publishing Status <span class="text-rose-500">*</span>
                        </label>
                        <select id="status" 
                                name="status" 
                                required
                                class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-900 outline-none transition-all cursor-pointer">
                            <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>🟢 Published (Live on Store)</option>
                            <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>📝 Draft (Hidden)</option>
                        </select>
                    </div>

                    {{-- Category --}}
                    <div>
                        <label for="category" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Category / Column
                        </label>
                        <input type="text" 
                               id="category" 
                               name="category" 
                               value="{{ old('category') }}" 
                               placeholder="e.g. Style Guide, Drop Breakdown"
                               class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-900 outline-none transition-all">
                    </div>

                    {{-- Author --}}
                    <div>
                        <label for="author" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Author Byline
                        </label>
                        <input type="text" 
                               id="author" 
                               name="author" 
                               value="{{ old('author', 'ThreadAX Editorial') }}" 
                               class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-900 outline-none transition-all">
                    </div>

                    {{-- Tags --}}
                    <div>
                        <label for="tags" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Tags (comma-separated)
                        </label>
                        <input type="text" 
                               id="tags" 
                               name="tags" 
                               value="{{ old('tags') }}" 
                               placeholder="streetwear, oversized, cotton, styling"
                               class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all">
                    </div>
                </div>

                {{-- Featured Image Upload --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
                            🖼️
                        </div>
                        <h3 class="text-xs font-heading font-extrabold text-slate-900 uppercase tracking-wider">Cover Media</h3>
                    </div>

                    <div>
                        <div class="border-2 border-dashed border-slate-200 hover:border-slate-400 rounded-2xl p-4 text-center transition-colors bg-slate-50/50">
                            <input type="file" 
                                   name="featured_image" 
                                   id="featured_image_input" 
                                   accept="image/*" 
                                   @change="previewFile"
                                   class="hidden">
                            
                            <label for="featured_image_input" class="cursor-pointer flex flex-col items-center justify-center gap-2">
                                <template x-if="imagePreview">
                                    <img :src="imagePreview" class="h-32 w-full object-cover rounded-xl border border-slate-200">
                                </template>
                                <template x-if="!imagePreview">
                                    <div class="flex flex-col items-center gap-1.5 py-2">
                                        <span class="text-2xl">📸</span>
                                        <span class="text-xs font-bold text-slate-900">Upload Cover Artwork</span>
                                        <span class="text-[11px] text-slate-400">PNG, JPG, WebP up to 4MB</span>
                                    </div>
                                </template>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs space-y-3">
                    <button type="submit" class="w-full py-3.5 px-6 rounded-xl text-xs font-extrabold text-white bg-slate-900 hover:bg-slate-800 transition-all shadow-sm hover:shadow-md flex justify-center items-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>Publish Article</span>
                    </button>

                    <div class="text-center pt-1">
                        <a href="{{ route('admin.blogs.index') }}" class="text-xs font-bold text-slate-400 hover:text-slate-700 transition-colors">
                            Discard & Go Back
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </form>

</div>
@endsection
