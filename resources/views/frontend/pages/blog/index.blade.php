@extends('frontend.layouts.app')
@section('title', 'Blog — ThreadAX | Streetwear Style & Fashion')
@section('meta_description', 'Read the latest streetwear style guides, fashion tips, and behind-the-scenes stories from ThreadAX.')

@section('content')
{{-- Hero --}}
<section class="relative bg-brand-dark py-20 sm:py-28 overflow-hidden">
    <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-[0.03] overflow-hidden select-none">
        <h1 class="text-[30vw] font-heading font-extrabold whitespace-nowrap">BLOG</h1>
    </div>
    <div class="max-w-[1440px] mx-auto px-4 lg:px-8 relative z-10 text-center">
        <p class="text-xs font-bold uppercase tracking-[0.4em] text-white/50 mb-3">Stories & Style</p>
        <h1 class="text-4xl sm:text-6xl font-heading font-extrabold text-white mb-4">THE THREADAX JOURNAL</h1>
        <p class="text-white/60 text-sm sm:text-base max-w-md mx-auto">Style guides, behind-the-scenes drops, and everything streetwear.</p>
    </div>
</section>

{{-- Blog Grid --}}
<section class="max-w-[1440px] mx-auto px-4 lg:px-8 py-16">
    @if($categories->count() > 0)
    <div class="flex flex-wrap gap-3 mb-12 justify-center">
        <a href="{{ route('frontend.blog.index') }}" class="px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wider border {{ !request('category') ? 'bg-brand-text text-white border-brand-text' : 'border-brand-border text-brand-muted hover:border-brand-text hover:text-brand-text' }} transition-all">
            All
        </a>
        @foreach($categories as $cat)
        <a href="{{ route('frontend.blog.index', ['category' => $cat->category]) }}" class="px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wider border {{ request('category') === $cat->category ? 'bg-brand-text text-white border-brand-text' : 'border-brand-border text-brand-muted hover:border-brand-text hover:text-brand-text' }} transition-all">
            {{ $cat->category }} ({{ $cat->count }})
        </a>
        @endforeach
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($blogs as $blog)
        <a href="{{ route('frontend.blog.show', $blog->slug) }}" class="group block">
            <div class="relative aspect-[16/10] bg-brand-light rounded-2xl overflow-hidden mb-5">
                @if($blog->featured_image)
                    <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-brand-light">
                        <svg class="w-12 h-12 text-brand-border" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    </div>
                @endif
                @if($blog->category)
                    <div class="absolute top-4 left-4">
                        <span class="bg-white/90 backdrop-blur-sm text-brand-text text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-full shadow-sm">{{ $blog->category }}</span>
                    </div>
                @endif
            </div>
            <div class="flex items-center gap-3 text-xs text-brand-muted mb-3">
                <span>{{ $blog->published_at?->format('M d, Y') }}</span>
                <span class="w-1 h-1 rounded-full bg-brand-border"></span>
                <span>{{ $blog->read_time }} min read</span>
            </div>
            <h3 class="text-lg font-bold text-brand-dark mb-2 group-hover:text-brand-muted transition-colors line-clamp-2">{{ $blog->title }}</h3>
            <p class="text-sm text-brand-muted line-clamp-2">{{ $blog->excerpt ?? Str::limit(strip_tags($blog->body), 120) }}</p>
        </a>
        @empty
        <div class="col-span-full text-center py-20">
            <svg class="w-16 h-16 text-brand-border mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            <p class="text-brand-muted text-lg font-semibold mb-2">No blog posts yet</p>
            <p class="text-brand-muted text-sm">Check back soon for style guides and fashion insights.</p>
        </div>
        @endforelse
    </div>

    @if($blogs->hasPages())
    <div class="mt-12">{{ $blogs->links() }}</div>
    @endif
</section>
@endsection
