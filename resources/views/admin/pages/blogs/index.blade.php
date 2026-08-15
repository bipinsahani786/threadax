@extends('admin.layouts.app')

@section('title', 'Blog Editorial - Admin')
@section('page-title', 'Blog Articles & Streetwear Editorial')

@section('content')
<div class="space-y-6 sm:space-y-8">

    {{-- Executive Metric Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        
        {{-- Total Articles --}}
        <a href="{{ route('admin.blogs.index') }}" class="group block bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs hover:border-slate-400 hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Articles</span>
                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    📰
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900">{{ number_format($stats['total']) }}</span>
                <span class="text-[11px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">
                    Articles
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Editorial stories & style guides</p>
        </a>

        {{-- Published Live --}}
        <a href="{{ route('admin.blogs.index', ['status' => 'published']) }}" class="group block bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs hover:border-emerald-400 hover:shadow-md transition-all {{ $status === 'published' ? 'ring-2 ring-emerald-500 border-transparent' : '' }}">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Published Live</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    🟢
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-emerald-950">{{ number_format($stats['published']) }}</span>
                <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                    Live
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Accessible to blog readers</p>
        </a>

        {{-- Drafts --}}
        <a href="{{ route('admin.blogs.index', ['status' => 'draft']) }}" class="group block bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs hover:border-amber-400 hover:shadow-md transition-all {{ $status === 'draft' ? 'ring-2 ring-amber-500 border-transparent' : '' }}">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-amber-700 uppercase tracking-wider">Draft Articles</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    📝
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-amber-950">{{ number_format($stats['draft']) }}</span>
                <span class="text-[11px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">
                    In Review
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Unpublished editorial drafts</p>
        </a>

        {{-- Reader Views --}}
        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-purple-700 uppercase tracking-wider">Total Impressions</span>
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-lg">
                    👁️
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-purple-950">{{ number_format($stats['total_views']) }}</span>
                <span class="text-[11px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-full border border-purple-200">
                    Reads
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Cumulative article pageviews</p>
        </div>

    </div>

    {{-- Main Blog Ledger Card --}}
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden">
        
        {{-- Filters & Search Toolbar --}}
        <div class="p-5 sm:p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            
            <form action="{{ route('admin.blogs.index') }}" method="GET" class="flex flex-1 items-center gap-3 flex-wrap" x-data x-ref="filterForm">
                
                {{-- Search Bar --}}
                <div class="relative w-full sm:w-64">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                        🔍
                    </span>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search articles, author..." 
                           class="w-full pl-9 pr-4 py-2.5 bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all shadow-2xs">
                </div>

                {{-- Status Filter --}}
                <select name="status" class="px-3.5 py-2.5 bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-700 outline-none shadow-2xs cursor-pointer" @change="$refs.filterForm.submit()">
                    <option value="all">All Publishing Statuses</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published (Live)</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft (Hidden)</option>
                </select>

                {{-- Category Filter --}}
                @if(count($categories) > 0)
                    <select name="category" class="px-3.5 py-2.5 bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-700 outline-none shadow-2xs cursor-pointer" @change="$refs.filterForm.submit()">
                        <option value="all">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                @endif

                <button type="submit" class="hidden">Search</button>

                @if(request()->hasAny(['search', 'status', 'category']))
                    <a href="{{ route('admin.blogs.index') }}" class="text-xs font-bold text-rose-600 hover:text-rose-700 hover:underline px-2 py-1 transition-colors">
                        ✕ Clear
                    </a>
                @endif
            </form>

            {{-- New Post CTA --}}
            <div class="shrink-0 flex items-center gap-2.5">
                <a href="{{ route('admin.blogs.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-extrabold text-white bg-slate-900 hover:bg-slate-800 transition-all shadow-sm hover:shadow-md cursor-pointer">
                    <span>➕ Write New Post</span>
                </a>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-[11px] uppercase font-extrabold text-slate-400 border-b border-slate-100 tracking-wider">
                    <tr>
                        <th scope="col" class="px-6 py-4 min-w-[260px]">Article Title & Author</th>
                        <th scope="col" class="px-5 py-4 min-w-[140px]">Category</th>
                        <th scope="col" class="px-5 py-4 min-w-[120px]">Status</th>
                        <th scope="col" class="px-5 py-4 min-w-[100px]">Readership</th>
                        <th scope="col" class="px-5 py-4 min-w-[140px]">Date Published</th>
                        <th scope="col" class="px-6 py-4 text-right min-w-[130px]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($blogs as $blog)
                        <tr class="hover:bg-slate-50/60 transition-colors group">
                            
                            {{-- Title & Author --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center shadow-2xs">
                                        @if($blog->featured_image)
                                            <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-base">📰</span>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('admin.blogs.edit', $blog) }}" class="font-extrabold text-slate-900 text-xs hover:text-blue-600 transition-colors line-clamp-1">
                                            {{ $blog->title }}
                                        </a>
                                        <div class="text-[11px] text-slate-400 mt-0.5 flex items-center gap-1.5">
                                            <span>by <strong class="text-slate-600">{{ $blog->author }}</strong></span>
                                            <span>•</span>
                                            <span class="font-mono truncate">/blog/{{ $blog->slug }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Category --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($blog->category)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                        🏷️ {{ $blog->category }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-xs">—</span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($blog->status === 'published')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> PUBLISHED
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200">
                                        DRAFT
                                    </span>
                                @endif
                            </td>

                            {{-- Views --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="font-extrabold text-slate-900 text-xs">👁️ {{ number_format($blog->views) }}</span>
                            </td>

                            {{-- Published Date --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($blog->published_at)
                                    <div class="text-xs font-bold text-slate-800">{{ $blog->published_at->format('M d, Y') }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $blog->published_at->diffForHumans() }}</div>
                                @else
                                    <span class="text-slate-400 text-xs">Not Published</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    
                                    {{-- Live Storefront Link --}}
                                    <a href="{{ route('frontend.blog.show', $blog->slug) }}" 
                                       target="_blank" 
                                       title="View on Storefront" 
                                       class="p-2 rounded-xl text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.blogs.edit', $blog) }}" 
                                       title="Edit Article" 
                                       class="p-2 rounded-xl text-slate-400 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('admin.blogs.destroy', $blog) }}" method="POST" onsubmit="return confirm('Delete blog post {{ addslashes($blog->title) }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer" title="Delete Article">
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
                                    📰
                                </div>
                                <h3 class="text-sm font-extrabold text-slate-900 mb-1">No blog posts found</h3>
                                <p class="text-xs text-slate-400 mb-4">No articles matching your filter criteria.</p>
                                <a href="{{ route('admin.blogs.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-slate-900 hover:bg-slate-800">
                                    ➕ Write First Article
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($blogs->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="text-xs text-slate-500 font-medium">
                    Showing <strong>{{ $blogs->firstItem() }}</strong> to <strong>{{ $blogs->lastItem() }}</strong> of <strong>{{ $blogs->total() }}</strong> posts
                </div>
                <div>
                    {{ $blogs->links() }}
                </div>
            </div>
        @endif

    </div>

</div>
@endsection
