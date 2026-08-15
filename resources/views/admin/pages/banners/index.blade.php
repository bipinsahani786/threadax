@extends('admin.layouts.app')

@section('title', 'Hero Banners & Promos - Admin')
@section('page-title', 'Hero Banners & Visual Promos')

@section('content')
<div class="space-y-6 sm:space-y-8">

    {{-- Executive Metric Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        
        {{-- Total Banners --}}
        <a href="{{ route('admin.banners.index') }}" class="group block bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs hover:border-slate-400 hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Banners</span>
                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    🖼️
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900">{{ number_format($stats['total']) }}</span>
                <span class="text-[11px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">
                    Assets
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Storefront visual marketing assets</p>
        </a>

        {{-- Active Live --}}
        <a href="{{ route('admin.banners.index', ['status' => 'active']) }}" class="group block bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs hover:border-emerald-400 hover:shadow-md transition-all {{ request('status') === 'active' ? 'ring-2 ring-emerald-500 border-transparent' : '' }}">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Active Live</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    🟢
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-emerald-950">{{ number_format($stats['active']) }}</span>
                <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                    Live Now
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Currently visible to shoppers</p>
        </a>

        {{-- Hero Carousel --}}
        <a href="{{ route('admin.banners.index', ['position' => 'hero']) }}" class="group block bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs hover:border-blue-400 hover:shadow-md transition-all {{ $position === 'hero' ? 'ring-2 ring-blue-500 border-transparent' : '' }}">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-blue-700 uppercase tracking-wider">Hero Carousel</span>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    🎯
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-blue-950">{{ number_format($stats['hero']) }}</span>
                <span class="text-[11px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-200">
                    Top Slider
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Main homepage flagship slides</p>
        </a>

        {{-- Mid & Promo Banners --}}
        <a href="{{ route('admin.banners.index', ['position' => 'mid']) }}" class="group block bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs hover:border-purple-400 hover:shadow-md transition-all {{ in_array($position, ['mid', 'bottom']) ? 'ring-2 ring-purple-500 border-transparent' : '' }}">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-purple-700 uppercase tracking-wider">Mid & Promo Banners</span>
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    📌
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-purple-950">{{ number_format($stats['mid_bottom']) }}</span>
                <span class="text-[11px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-full border border-purple-200">
                    In-feed Promos
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Mid-page & category promo blocks</p>
        </a>

    </div>

    {{-- Main Banners Visual Studio --}}
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden">
        
        {{-- Position Tabs & Actions Bar --}}
        <div class="p-5 sm:p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            
            {{-- Tabs --}}
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar">
                <a href="{{ route('admin.banners.index', ['position' => 'all']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all shadow-2xs {{ $position === 'all' ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                    All Banners ({{ $stats['total'] }})
                </a>
                
                <a href="{{ route('admin.banners.index', ['position' => 'hero']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all shadow-2xs {{ $position === 'hero' ? 'bg-blue-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                    🎯 Hero Carousel ({{ $stats['hero'] }})
                </a>

                <a href="{{ route('admin.banners.index', ['position' => 'mid']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all shadow-2xs {{ $position === 'mid' ? 'bg-purple-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                    📌 Mid Section
                </a>

                <a href="{{ route('admin.banners.index', ['position' => 'bottom']) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all shadow-2xs {{ $position === 'bottom' ? 'bg-amber-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                    🔻 Bottom Promos
                </a>
            </div>

            {{-- Create Banner CTA --}}
            <div class="shrink-0 flex items-center gap-2.5">
                <a href="{{ route('admin.banners.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-extrabold text-white bg-slate-900 hover:bg-slate-800 transition-all shadow-sm hover:shadow-md cursor-pointer">
                    <span>➕ Add New Banner</span>
                </a>
            </div>
        </div>

        {{-- Visual Banners Grid --}}
        <div class="p-5 sm:p-7">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($banners as $banner)
                    <div class="group bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs hover:shadow-md hover:border-slate-400 transition-all flex flex-col justify-between">
                        
                        {{-- Banner Visual Image Header --}}
                        <div>
                            <div class="relative aspect-video bg-slate-950 overflow-hidden">
                                @if($banner->image_path)
                                    <img src="{{ asset('storage/' . $banner->image_path) }}" alt="{{ $banner->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-500 font-bold">No Image</div>
                                @endif

                                {{-- Position & Status Badges --}}
                                <div class="absolute top-3 left-3 flex items-center gap-1.5 flex-wrap">
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider backdrop-blur-md shadow-xs {{ $banner->position === 'hero' ? 'bg-blue-600/90 text-white' : ($banner->position === 'mid' ? 'bg-purple-600/90 text-white' : 'bg-amber-600/90 text-white') }}">
                                        {{ $banner->position }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded-lg text-[10px] font-mono font-bold bg-black/70 text-white backdrop-blur-md">
                                        #{{ $banner->sort_order }}
                                    </span>
                                </div>

                                <div class="absolute top-3 right-3">
                                    @if($banner->is_active)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-500/90 text-white backdrop-blur-md shadow-xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span> LIVE
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-800/90 text-slate-300 backdrop-blur-md">
                                            DRAFT
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Card Body --}}
                            <div class="p-5 space-y-2.5">
                                <h3 class="font-heading font-extrabold text-slate-900 text-sm line-clamp-1 group-hover:text-blue-600 transition-colors">
                                    {{ $banner->title }}
                                </h3>
                                
                                @if($banner->subtitle)
                                    <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                        {{ $banner->subtitle }}
                                    </p>
                                @endif

                                @if($banner->link)
                                    <div class="pt-1 flex items-center gap-2 text-[11px] text-slate-400 font-mono truncate">
                                        <span>🔗</span>
                                        <span class="truncate">{{ $banner->link }}</span>
                                    </div>
                                @endif

                                @if($banner->button_text)
                                    <div class="pt-1">
                                        <span class="inline-block text-[11px] font-extrabold text-slate-900 bg-slate-100 px-2.5 py-1 rounded-lg">
                                            CTA: "{{ $banner->button_text }}"
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Card Footer Actions --}}
                        <div class="p-4 bg-slate-50/70 border-t border-slate-100 flex items-center justify-between">
                            <a href="{{ route('admin.banners.edit', $banner) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold bg-white text-slate-700 hover:text-slate-900 border border-slate-200 hover:border-slate-400 transition-colors shadow-2xs">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                <span>Edit Banner</span>
                            </a>

                            <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete banner {{ addslashes($banner->title) }}?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer" title="Delete Banner">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>

                    </div>
                @empty
                    <div class="col-span-full py-16 text-center">
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 mb-3 text-2xl">
                            🖼️
                        </div>
                        <h3 class="text-sm font-extrabold text-slate-900 mb-1">No banners found</h3>
                        <p class="text-xs text-slate-400 mb-4">No hero banners or promotional slides in this position.</p>
                        <a href="{{ route('admin.banners.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-slate-900 hover:bg-slate-800">
                            ➕ Create First Banner
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Pagination --}}
        @if($banners->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="text-xs text-slate-500 font-medium">
                    Showing <strong>{{ $banners->firstItem() }}</strong> to <strong>{{ $banners->lastItem() }}</strong> of <strong>{{ $banners->total() }}</strong> banners
                </div>
                <div>
                    {{ $banners->links() }}
                </div>
            </div>
        @endif

    </div>

</div>
@endsection
