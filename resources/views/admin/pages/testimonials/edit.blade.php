@extends('admin.layouts.app')

@section('title', 'Edit Testimonial - ' . $testimonial->name)
@section('page-title')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.testimonials.index') }}" class="text-slate-400 hover:text-slate-900 transition-colors p-1.5 rounded-xl hover:bg-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <div class="flex items-center gap-2">
                <span class="text-lg font-extrabold text-slate-900">Edit Testimonial: {{ $testimonial->name }}</span>
                @if($testimonial->is_active)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Live
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-600 border border-slate-200">
                        Hidden
                    </span>
                @endif
            </div>
            <p class="text-xs text-slate-400">Sort Priority: #{{ $testimonial->sort_order }} • {{ $testimonial->rating }} Stars</p>
        </div>
    </div>
@endsection

@section('content')
<div class="max-w-5xl mx-auto space-y-6 sm:space-y-8"
     x-data="{
         name: '{{ addslashes(old('name', $testimonial->name)) }}',
         designation: '{{ addslashes(old('designation', $testimonial->designation)) }}',
         content: `{{ addslashes(old('content', $testimonial->content)) }}`,
         rating: {{ old('rating', $testimonial->rating) }},
         photoUrl: '{{ addslashes(old('photo_url', $testimonial->photo_url)) }}',
         videoUrl: '{{ addslashes(old('video_url', $testimonial->video_url)) }}',
         isActive: {{ old('is_active', $testimonial->is_active ? 1 : 0) ? 'true' : 'false' }}
     }">

    {{-- Form --}}
    <form action="{{ route('admin.testimonials.update', $testimonial) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
            
            {{-- Main Content Column (2/3 width) --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Review Details Card --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-7 shadow-xs space-y-5">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-base">
                            💬
                        </div>
                        <div>
                            <h3 class="text-sm font-heading font-extrabold text-slate-900">Testimonial Details</h3>
                            <p class="text-xs text-slate-400">Customer feedback and credibility metrics</p>
                        </div>
                    </div>

                    {{-- Customer Name & Designation --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Customer / Creator Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   x-model="name"
                                   value="{{ old('name', $testimonial->name) }}" 
                                   required
                                   class="w-full px-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-900 outline-none transition-all @error('name') border-rose-500 @enderror">
                            @error('name') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="designation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Tagline / Role
                            </label>
                            <input type="text" 
                                   id="designation" 
                                   name="designation" 
                                   x-model="designation"
                                   value="{{ old('designation', $testimonial->designation) }}" 
                                   class="w-full px-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all">
                        </div>
                    </div>

                    {{-- Review Content --}}
                    <div>
                        <label for="content" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Endorsement / Review Text <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="content" 
                                  name="content" 
                                  x-model="content"
                                  rows="4"
                                  required
                                  maxlength="1000"
                                  class="w-full p-4 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all leading-relaxed @error('content') border-rose-500 @enderror">{{ old('content', $testimonial->content) }}</textarea>
                        @error('content') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Rating & Media Links --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Rating --}}
                        <div>
                            <label for="rating" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Star Rating <span class="text-rose-500">*</span>
                            </label>
                            <select id="rating" 
                                    name="rating" 
                                    x-model="rating"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-900 outline-none transition-all cursor-pointer">
                                <option value="5" {{ old('rating', $testimonial->rating) == 5 ? 'selected' : '' }}>★★★★★ 5 Stars (Flawless)</option>
                                <option value="4" {{ old('rating', $testimonial->rating) == 4 ? 'selected' : '' }}>★★★★☆ 4 Stars (Great)</option>
                                <option value="3" {{ old('rating', $testimonial->rating) == 3 ? 'selected' : '' }}>★★★☆☆ 3 Stars (Good)</option>
                            </select>
                        </div>

                        {{-- Photo URL --}}
                        <div>
                            <label for="photo_url" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Avatar / Photo URL
                            </label>
                            <input type="url" 
                                   id="photo_url" 
                                   name="photo_url" 
                                   x-model="photoUrl"
                                   value="{{ old('photo_url', $testimonial->photo_url) }}" 
                                   class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-mono font-medium text-slate-900 outline-none transition-all">
                        </div>
                    </div>

                    {{-- Video URL --}}
                    <div>
                        <label for="video_url" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Video Testimonial URL (YouTube or MP4)
                        </label>
                        <input type="url" 
                               id="video_url" 
                               name="video_url" 
                               x-model="videoUrl"
                               value="{{ old('video_url', $testimonial->video_url) }}" 
                               class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-mono font-medium text-slate-900 outline-none transition-all">
                    </div>
                </div>

            </div>

            {{-- Right Column (1/3 width) --}}
            <div class="space-y-6">
                
                {{-- Live Storefront Card Simulator --}}
                <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-950 rounded-2xl p-6 text-white shadow-md space-y-4">
                    <div class="flex items-center justify-between text-xs text-amber-400 font-bold uppercase tracking-wider">
                        <span>✨ Live Card Simulator</span>
                        <span>Storefront Preview</span>
                    </div>

                    <div class="flex text-amber-400">
                        <template x-for="i in 5">
                            <svg class="w-4 h-4" :class="i <= rating ? 'fill-current' : 'text-slate-600 fill-current'" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </template>
                    </div>

                    <p class="text-xs text-slate-300 italic line-clamp-4 leading-relaxed" x-text="content ? '“' + content + '”' : '“Customer testimonial quote will appear formatted here...”'"></p>

                    <div class="flex items-center gap-3 pt-2 border-t border-white/10">
                        <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center font-bold text-xs text-white uppercase">
                            <span x-text="name ? name.substring(0,2) : 'TX'"></span>
                        </div>
                        <div>
                            <div class="font-extrabold text-xs text-white" x-text="name || 'Customer Name'"></div>
                            <div class="text-[10px] text-amber-400 font-bold" x-text="designation || 'Verified Buyer'"></div>
                        </div>
                    </div>
                </div>

                {{-- Status & Sorting --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                            ⚙️
                        </div>
                        <h3 class="text-xs font-heading font-extrabold text-slate-900 uppercase tracking-wider">Publish Status</h3>
                    </div>

                    {{-- Sort Order --}}
                    <div>
                        <label for="sort_order" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Display Sort Order
                        </label>
                        <input type="number" 
                               id="sort_order" 
                               name="sort_order" 
                               value="{{ old('sort_order', $testimonial->sort_order) }}" 
                               min="0"
                               class="w-full px-3.5 py-2 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-mono font-bold text-slate-900 outline-none transition-all">
                    </div>

                    {{-- Active Toggle Switch --}}
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                        <div>
                            <span class="text-xs font-bold text-slate-900 block">Active Status</span>
                            <span class="text-[11px] text-slate-400">Show on homepage</span>
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
                        <span>Update Testimonial</span>
                    </button>

                    <div class="text-center pt-1">
                        <a href="{{ route('admin.testimonials.index') }}" class="text-xs font-bold text-slate-400 hover:text-slate-700 transition-colors">
                            Discard & Go Back
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </form>

</div>
@endsection
