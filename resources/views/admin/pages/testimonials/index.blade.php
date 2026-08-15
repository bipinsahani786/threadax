@extends('admin.layouts.app')

@section('title', 'Testimonials - Admin')
@section('page-title', 'Customer Testimonials & Influencer Endorsements')

@section('content')
<div class="space-y-6 sm:space-y-8">

    {{-- Executive Metric Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        
        {{-- Total Testimonials --}}
        <a href="{{ route('admin.testimonials.index') }}" class="group block bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs hover:border-slate-400 hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Testimonials</span>
                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    💬
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900">{{ number_format($stats['total']) }}</span>
                <span class="text-[11px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">
                    Reviews
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Customer & creator endorsements</p>
        </a>

        {{-- Active Live --}}
        <a href="{{ route('admin.testimonials.index', ['status' => 'active']) }}" class="group block bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs hover:border-emerald-400 hover:shadow-md transition-all {{ request('status') === 'active' ? 'ring-2 ring-emerald-500 border-transparent' : '' }}">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Active Live</span>
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
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Published on homepage & about page</p>
        </a>

        {{-- 5-Star Reviews --}}
        <a href="{{ route('admin.testimonials.index', ['rating' => '5']) }}" class="group block bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs hover:border-amber-400 hover:shadow-md transition-all {{ request('rating') == '5' ? 'ring-2 ring-amber-500 border-transparent' : '' }}">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-amber-700 uppercase tracking-wider">5-Star Rated</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    ⭐
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-amber-950">{{ number_format($stats['five_star']) }}</span>
                <span class="text-[11px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">
                    5.0 ★
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Flawless customer testimonials</p>
        </a>

        {{-- Average Rating --}}
        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-purple-700 uppercase tracking-wider">Average Score</span>
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-lg">
                    ✨
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-purple-950">{{ number_format($stats['avg_rating'], 1) }} <span class="text-sm font-bold text-slate-400">/ 5.0</span></span>
                <div class="flex text-amber-400">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-3.5 h-3.5 {{ $i <= round($stats['avg_rating']) ? 'fill-current' : 'text-slate-200 fill-current' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Customer satisfaction rating</p>
        </div>

    </div>

    {{-- Main Testimonials Ledger Card --}}
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden">
        
        {{-- Filters & Search Toolbar --}}
        <div class="p-5 sm:p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            
            <form action="{{ route('admin.testimonials.index') }}" method="GET" class="flex flex-1 items-center gap-3 flex-wrap" x-data x-ref="filterForm">
                
                {{-- Search Bar --}}
                <div class="relative w-full sm:w-64">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                        🔍
                    </span>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search customer, role, review..." 
                           class="w-full pl-9 pr-4 py-2.5 bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all shadow-2xs">
                </div>

                {{-- Rating Filter --}}
                <select name="rating" class="px-3.5 py-2.5 bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-700 outline-none shadow-2xs cursor-pointer" @change="$refs.filterForm.submit()">
                    <option value="all">All Ratings</option>
                    <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Stars ★★★★★</option>
                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Stars ★★★★</option>
                    <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Stars ★★★</option>
                </select>

                {{-- Status Filter --}}
                <select name="status" class="px-3.5 py-2.5 bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-700 outline-none shadow-2xs cursor-pointer" @change="$refs.filterForm.submit()">
                    <option value="all">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active (Live)</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive (Hidden)</option>
                </select>

                <button type="submit" class="hidden">Search</button>

                @if(request()->hasAny(['search', 'rating', 'status']))
                    <a href="{{ route('admin.testimonials.index') }}" class="text-xs font-bold text-rose-600 hover:text-rose-700 hover:underline px-2 py-1 transition-colors">
                        ✕ Clear
                    </a>
                @endif
            </form>

            {{-- New Testimonial CTA --}}
            <div class="shrink-0 flex items-center gap-2.5">
                <a href="{{ route('admin.testimonials.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-extrabold text-white bg-slate-900 hover:bg-slate-800 transition-all shadow-sm hover:shadow-md cursor-pointer">
                    <span>➕ Add Testimonial</span>
                </a>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-[11px] uppercase font-extrabold text-slate-400 border-b border-slate-100 tracking-wider">
                    <tr>
                        <th scope="col" class="px-6 py-4 min-w-[200px]">Customer / Creator</th>
                        <th scope="col" class="px-5 py-4 min-w-[120px]">Rating</th>
                        <th scope="col" class="px-5 py-4 min-w-[280px]">Testimonial Quote</th>
                        <th scope="col" class="px-5 py-4 min-w-[120px]">Media Links</th>
                        <th scope="col" class="px-5 py-4 min-w-[100px]">Status</th>
                        <th scope="col" class="px-6 py-4 text-right min-w-[120px]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($testimonials as $testimonial)
                        @php
                            $initials = collect(explode(' ', $testimonial->name))->map(fn($part) => strtoupper(substr($part, 0, 1)))->take(2)->join('');
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors group">
                            
                            {{-- Customer Details --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-slate-900 text-white font-extrabold text-xs flex items-center justify-center shrink-0 shadow-2xs">
                                        {{ $initials ?: 'TX' }}
                                    </div>
                                    <div>
                                        <div class="font-extrabold text-slate-900 text-xs">{{ $testimonial->name }}</div>
                                        <div class="text-[11px] text-slate-400 font-medium">{{ $testimonial->designation ?: 'Verified Customer' }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Rating --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="flex text-amber-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-3.5 h-3.5 {{ $i <= $testimonial->rating ? 'fill-current' : 'text-slate-200 fill-current' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                                <span class="text-[10px] text-slate-400 font-bold block mt-0.5">#{{ $testimonial->sort_order }} priority</span>
                            </td>

                            {{-- Quote --}}
                            <td class="px-5 py-4">
                                <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed bg-slate-50 p-2.5 rounded-xl border border-slate-100 italic">
                                    "{{ $testimonial->content }}"
                                </p>
                            </td>

                            {{-- Media --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-1.5">
                                    @if($testimonial->photo_url)
                                        <a href="{{ $testimonial->photo_url }}" target="_blank" class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-100 transition-colors">
                                            📷 Photo
                                        </a>
                                    @endif
                                    @if($testimonial->video_url)
                                        <a href="{{ $testimonial->video_url }}" target="_blank" class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200 hover:bg-purple-100 transition-colors">
                                            🎥 Video
                                        </a>
                                    @endif
                                    @if(!$testimonial->photo_url && !$testimonial->video_url)
                                        <span class="text-slate-400 text-[11px]">Text Only</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($testimonial->is_active)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> ACTIVE
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-600 border border-slate-200">
                                        HIDDEN
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    
                                    {{-- Edit --}}
                                    <a href="{{ route('admin.testimonials.edit', $testimonial) }}" 
                                       title="Edit Testimonial" 
                                       class="p-2 rounded-xl text-slate-400 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" onsubmit="return confirm('Delete this testimonial from {{ addslashes($testimonial->name) }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer" title="Delete Testimonial">
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
                                    💬
                                </div>
                                <h3 class="text-sm font-extrabold text-slate-900 mb-1">No testimonials found</h3>
                                <p class="text-xs text-slate-400 mb-4">No reviews matching your search filters.</p>
                                <a href="{{ route('admin.testimonials.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-slate-900 hover:bg-slate-800">
                                    ➕ Add First Testimonial
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($testimonials->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="text-xs text-slate-500 font-medium">
                    Showing <strong>{{ $testimonials->firstItem() }}</strong> to <strong>{{ $testimonials->lastItem() }}</strong> of <strong>{{ $testimonials->total() }}</strong> testimonials
                </div>
                <div>
                    {{ $testimonials->links() }}
                </div>
            </div>
        @endif

    </div>

</div>
@endsection
