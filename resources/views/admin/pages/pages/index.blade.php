@extends('admin.layouts.app')

@section('title', 'Static Pages - Admin')
@section('page-title', 'Static Pages & Legal Policies')

@section('content')
<div class="space-y-6 sm:space-y-8">

    {{-- Executive Metric Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
        
        {{-- Total Pages --}}
        <a href="{{ route('admin.pages.index') }}" class="group block bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs hover:border-slate-400 hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Static Pages</span>
                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    📄
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900">{{ number_format($stats['total']) }}</span>
                <span class="text-[11px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">
                    CMS Pages
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Legal policies, terms, and brand pages</p>
        </a>

        {{-- Active Live --}}
        <a href="{{ route('admin.pages.index', ['status' => 'active']) }}" class="group block bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs hover:border-emerald-400 hover:shadow-md transition-all {{ request('status') === 'active' ? 'ring-2 ring-emerald-500 border-transparent' : '' }}">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Active on Storefront</span>
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
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Accessible via footer & navbar links</p>
        </a>

        {{-- Inactive --}}
        <a href="{{ route('admin.pages.index', ['status' => 'inactive']) }}" class="group block bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs hover:border-amber-400 hover:shadow-md transition-all {{ request('status') === 'inactive' ? 'ring-2 ring-amber-500 border-transparent' : '' }}">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-amber-700 uppercase tracking-wider">Draft / Inactive</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    ⏳
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-amber-950">{{ number_format($stats['inactive']) }}</span>
                <span class="text-[11px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">
                    Hidden
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Draft policies not visible publicly</p>
        </a>

    </div>

    {{-- Main Pages Ledger Card --}}
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden">
        
        {{-- Filters & Search Toolbar --}}
        <div class="p-5 sm:p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            
            <form action="{{ route('admin.pages.index') }}" method="GET" class="flex flex-1 items-center gap-3 flex-wrap" x-data x-ref="filterForm">
                
                {{-- Search Bar --}}
                <div class="relative w-full sm:w-64">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                        🔍
                    </span>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search page title, slug..." 
                           class="w-full pl-9 pr-4 py-2.5 bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all shadow-2xs">
                </div>

                {{-- Status Filter --}}
                <select name="status" class="px-3.5 py-2.5 bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-700 outline-none shadow-2xs cursor-pointer" @change="$refs.filterForm.submit()">
                    <option value="all">All Page Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active (Live)</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive (Hidden)</option>
                </select>

                <button type="submit" class="hidden">Search</button>

                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.pages.index') }}" class="text-xs font-bold text-rose-600 hover:text-rose-700 hover:underline px-2 py-1 transition-colors">
                        ✕ Clear
                    </a>
                @endif
            </form>

            {{-- New Page CTA --}}
            <div class="shrink-0 flex items-center gap-2.5">
                <a href="{{ route('admin.pages.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-extrabold text-white bg-slate-900 hover:bg-slate-800 transition-all shadow-sm hover:shadow-md cursor-pointer">
                    <span>➕ Create New Page</span>
                </a>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-[11px] uppercase font-extrabold text-slate-400 border-b border-slate-100 tracking-wider">
                    <tr>
                        <th scope="col" class="px-6 py-4 min-w-[240px]">Page Title & URL Slug</th>
                        <th scope="col" class="px-5 py-4 min-w-[140px]">Public URL Route</th>
                        <th scope="col" class="px-5 py-4 min-w-[120px]">Status</th>
                        <th scope="col" class="px-5 py-4 min-w-[140px]">Last Updated</th>
                        <th scope="col" class="px-6 py-4 text-right min-w-[130px]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($pages as $page)
                        <tr class="hover:bg-slate-50/60 transition-colors group">
                            
                            {{-- Title & Slug --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-sm shrink-0 border border-slate-200 group-hover:border-slate-400 transition-colors shadow-2xs">
                                        📄
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.pages.edit', $page) }}" class="font-extrabold text-slate-900 text-xs hover:text-blue-600 transition-colors">
                                            {{ $page->title }}
                                        </a>
                                        <div class="text-[11px] text-slate-400 font-mono mt-0.5">
                                            /page/{{ $page->slug }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Public URL Route --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <a href="{{ route('frontend.page.show', $page->slug) }}" target="_blank" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-slate-100 text-slate-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                                    <span>/page/{{ $page->slug }}</span>
                                    <svg class="w-3 h-3 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($page->is_active)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> ACTIVE
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-600 border border-slate-200">
                                        INACTIVE
                                    </span>
                                @endif
                            </td>

                            {{-- Last Updated --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="text-xs font-bold text-slate-800">{{ $page->updated_at->format('M d, Y') }}</div>
                                <div class="text-[10px] text-slate-400">{{ $page->updated_at->diffForHumans() }}</div>
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    
                                    {{-- Live Storefront Link --}}
                                    <a href="{{ route('frontend.page.show', $page->slug) }}" 
                                       target="_blank" 
                                       title="View on Storefront" 
                                       class="p-2 rounded-xl text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.pages.edit', $page) }}" 
                                       title="Edit Page" 
                                       class="p-2 rounded-xl text-slate-400 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" onsubmit="return confirm('Delete page {{ addslashes($page->title) }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer" title="Delete Page">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 mb-3 text-2xl">
                                    📄
                                </div>
                                <h3 class="text-sm font-extrabold text-slate-900 mb-1">No pages found</h3>
                                <p class="text-xs text-slate-400 mb-4">No static CMS pages match your search.</p>
                                <a href="{{ route('admin.pages.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-slate-900 hover:bg-slate-800">
                                    ➕ Create First Page
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($pages->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="text-xs text-slate-500 font-medium">
                    Showing <strong>{{ $pages->firstItem() }}</strong> to <strong>{{ $pages->lastItem() }}</strong> of <strong>{{ $pages->total() }}</strong> pages
                </div>
                <div>
                    {{ $pages->links() }}
                </div>
            </div>
        @endif

    </div>

</div>
@endsection
