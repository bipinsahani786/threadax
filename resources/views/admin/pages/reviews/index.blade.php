@extends('admin.layouts.app')

@section('title', 'Product Reviews - Admin')
@section('page-title', 'Customer Reviews & Social Proof')

@section('content')
<div class="space-y-6 sm:space-y-8">

    {{-- Executive Metric Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        
        {{-- Total Reviews --}}
        <a href="{{ route('admin.reviews.index', ['status' => 'all']) }}" class="group block bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs hover:border-slate-400 hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Feedback</span>
                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    💬
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900">{{ number_format($stats['total']) }}</span>
                <span class="text-[11px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">
                    All Reviews
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Customer ratings & testimonies</p>
        </a>

        {{-- Approved Live --}}
        <a href="{{ route('admin.reviews.index', ['status' => 'approved']) }}" class="group block bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs hover:border-emerald-400 hover:shadow-md transition-all {{ $status === 'approved' ? 'ring-2 ring-emerald-500 border-transparent' : '' }}">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Approved Live</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    ✅
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-emerald-950">{{ number_format($stats['approved']) }}</span>
                <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                    {{ $stats['total'] > 0 ? round(($stats['approved'] / $stats['total']) * 100) : 0 }}% Live
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Published on product pages</p>
        </a>

        {{-- Pending Moderation --}}
        <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" class="group block bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs hover:border-amber-400 hover:shadow-md transition-all {{ $status === 'pending' ? 'ring-2 ring-amber-500 border-transparent' : '' }}">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-amber-700 uppercase tracking-wider">Pending Review</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    ⏳
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-amber-950">{{ number_format($stats['pending']) }}</span>
                @if($stats['pending'] > 0)
                    <span class="text-[11px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200 animate-pulse">
                        Action Required
                    </span>
                @else
                    <span class="text-[11px] font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">
                        All Cleared
                    </span>
                @endif
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Awaiting admin moderation</p>
        </a>

        {{-- Store Average Rating --}}
        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-amber-700 uppercase tracking-wider">Average Rating</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center font-bold text-lg">
                    ⭐
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold font-heading text-slate-900">{{ number_format($stats['avg_rating'], 1) }} <span class="text-sm font-bold text-slate-400">/ 5.0</span></span>
                <div class="flex text-amber-400">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-3.5 h-3.5 {{ $i <= round($stats['avg_rating']) ? 'fill-current' : 'text-slate-200 fill-current' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
            </div>
            <p class="text-[11px] text-slate-400 mt-2 font-medium">Overall customer satisfaction score</p>
        </div>

    </div>

    {{-- Main Reviews Ledger Card --}}
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden">
        
        {{-- Status Filter Tabs & Search Bar --}}
        <div class="p-5 sm:p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar">
                <a href="{{ route('admin.reviews.index', array_merge(request()->query(), ['status' => 'all'])) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all shadow-2xs {{ $status === 'all' ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                    All Feedback ({{ $stats['total'] }})
                </a>
                
                <a href="{{ route('admin.reviews.index', array_merge(request()->query(), ['status' => 'pending'])) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all shadow-2xs {{ $status === 'pending' ? 'bg-amber-500 text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                    ⏳ Pending ({{ $stats['pending'] }})
                </a>

                <a href="{{ route('admin.reviews.index', array_merge(request()->query(), ['status' => 'approved'])) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all shadow-2xs {{ $status === 'approved' ? 'bg-emerald-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                    ✅ Approved ({{ $stats['approved'] }})
                </a>

                <a href="{{ route('admin.reviews.index', array_merge(request()->query(), ['status' => 'rejected'])) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all shadow-2xs {{ $status === 'rejected' ? 'bg-rose-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                    ❌ Rejected ({{ $stats['rejected'] }})
                </a>
            </div>

            <form action="{{ route('admin.reviews.index') }}" method="GET" class="flex items-center gap-3 flex-wrap" x-data x-ref="filterForm">
                <input type="hidden" name="status" value="{{ $status }}">
                
                {{-- Search Bar --}}
                <div class="relative w-full sm:w-64">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                        🔍
                    </span>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search review, customer, product..." 
                           class="w-full pl-9 pr-4 py-2 bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all shadow-2xs">
                </div>

                {{-- Rating Filter --}}
                <select name="rating" class="px-3 py-2 bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-700 outline-none shadow-2xs cursor-pointer" @change="$refs.filterForm.submit()">
                    <option value="">All Star Ratings</option>
                    <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Stars ★★★★★</option>
                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Stars ★★★★</option>
                    <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Stars ★★★</option>
                    <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Stars ★★</option>
                    <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Star ★</option>
                </select>

                <button type="submit" class="hidden">Search</button>

                @if(request()->hasAny(['search', 'rating']))
                    <a href="{{ route('admin.reviews.index', ['status' => $status]) }}" class="text-xs font-bold text-rose-600 hover:text-rose-700 hover:underline px-1 py-1 transition-colors">
                        ✕ Clear
                    </a>
                @endif
            </form>

        </div>

        {{-- Reviews Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-[11px] uppercase font-extrabold text-slate-400 border-b border-slate-100 tracking-wider">
                    <tr>
                        <th scope="col" class="px-6 py-4 min-w-[200px]">Product</th>
                        <th scope="col" class="px-5 py-4 min-w-[150px]">Customer</th>
                        <th scope="col" class="px-5 py-4 min-w-[120px]">Rating</th>
                        <th scope="col" class="px-5 py-4 min-w-[280px]">Review Feedback</th>
                        <th scope="col" class="px-5 py-4 min-w-[100px]">Status</th>
                        <th scope="col" class="px-6 py-4 text-right min-w-[160px]">Moderation</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($reviews as $review)
                        @php
                            $userInitials = collect(explode(' ', $review->user->name ?? 'User'))->map(fn($part) => strtoupper(substr($part, 0, 1)))->take(2)->join('');
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors group">
                            
                            {{-- Product --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-13 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center shadow-2xs">
                                        @if($review->product?->primaryImage)
                                            <img src="{{ $review->product->primaryImage->url }}" alt="{{ $review->product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="font-bold text-slate-400 text-xs">TX</span>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        @if($review->product)
                                            <a href="{{ route('frontend.products.show', $review->product->slug) }}" target="_blank" class="font-extrabold text-slate-900 text-xs hover:text-blue-600 transition-colors line-clamp-1">
                                                {{ $review->product->name }}
                                            </a>
                                            <span class="text-[10px] text-slate-400 font-bold block mt-0.5">₹{{ number_format($review->product->price) }}</span>
                                        @else
                                            <span class="font-extrabold text-slate-400 text-xs italic">[Product Unavailable]</span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Customer --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-slate-900 text-white font-bold text-[10px] flex items-center justify-center shrink-0">
                                        {{ $userInitials }}
                                    </div>
                                    <div>
                                        <div class="font-extrabold text-slate-900 text-xs">{{ $review->user->name ?? 'Customer' }}</div>
                                        <div class="text-[10px] text-emerald-600 font-bold flex items-center gap-1">
                                            <span>✓ Verified Buyer</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Star Rating --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-1">
                                    <div class="flex text-amber-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'fill-current' : 'text-slate-200 fill-current' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @endfor
                                    </div>
                                    <span class="font-bold text-slate-700 text-xs ml-1">{{ $review->rating }}.0</span>
                                </div>
                                <div class="text-[10px] text-slate-400 mt-1">{{ $review->created_at->format('M d, Y') }}</div>
                            </td>

                            {{-- Review Feedback --}}
                            <td class="px-5 py-4">
                                @if($review->title)
                                    <h4 class="font-extrabold text-slate-900 text-xs mb-0.5">"{{ $review->title }}"</h4>
                                @endif
                                <p class="text-xs text-slate-600 leading-relaxed line-clamp-3 bg-slate-50 p-2 rounded-xl border border-slate-100">
                                    {{ $review->body }}
                                </p>
                            </td>

                            {{-- Status Badge --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($review->status === 'approved')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> LIVE
                                    </span>
                                @elseif($review->status === 'pending')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> PENDING
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-50 text-rose-700 border border-rose-200">
                                        REJECTED
                                    </span>
                                @endif
                            </td>

                            {{-- Moderation Actions --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    
                                    @if($review->status !== 'approved')
                                        <form method="POST" action="{{ route('admin.reviews.status.update', $review) }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-black bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white border border-emerald-200 transition-all cursor-pointer shadow-2xs" title="Approve & Publish Live">
                                                <span>✓ Approve</span>
                                            </button>
                                        </form>
                                    @endif

                                    @if($review->status !== 'rejected')
                                        <form method="POST" action="{{ route('admin.reviews.status.update', $review) }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-black bg-slate-100 text-slate-700 hover:bg-rose-600 hover:text-white border border-slate-200 transition-all cursor-pointer shadow-2xs" title="Reject & Hide">
                                                <span>✕ Reject</span>
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('Delete this review completely?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer" title="Delete Review">
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
                                    ⭐
                                </div>
                                <h3 class="text-sm font-extrabold text-slate-900 mb-1">No reviews found</h3>
                                <p class="text-xs text-slate-400">No customer feedback matching your filter criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Footer --}}
        @if($reviews->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="text-xs text-slate-500 font-medium">
                    Showing <strong>{{ $reviews->firstItem() }}</strong> to <strong>{{ $reviews->lastItem() }}</strong> of <strong>{{ $reviews->total() }}</strong> reviews
                </div>
                <div>
                    {{ $reviews->links() }}
                </div>
            </div>
        @endif

    </div>

</div>
@endsection
