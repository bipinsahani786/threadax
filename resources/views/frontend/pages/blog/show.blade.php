@extends('frontend.layouts.app')
@section('title', ($blog->meta_title ?? $blog->title) . ' — ThreadAX Blog')
@section('meta_description', $blog->meta_description ?? $blog->excerpt ?? Str::limit(strip_tags($blog->body), 155))
@section('meta_image', $blog->featured_image_url ?? asset('images/about/hero.png'))

@push('head')
    {{-- Schema.org BlogPosting Structured Data --}}
    @php
        $articleSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $blog->title,
            'image' => $blog->featured_image_url ?? asset('images/about/hero.png'),
            'datePublished' => $blog->published_at ? $blog->published_at->toIso8601String() : $blog->created_at->toIso8601String(),
            'dateModified' => $blog->updated_at->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $blog->author_name ?? 'ThreadAX Team',
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'ThreadAX',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('images/logo.png'),
                ],
            ],
            'description' => $blog->excerpt ?? Str::limit(strip_tags($blog->body), 155),
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => route('frontend.blog.show', $blog->slug),
            ],
        ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($articleSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
@endpush

@section('content')
{{-- Hero Image --}}
<section class="relative w-full bg-brand-dark h-[60vh] lg:h-[70vh] flex items-end justify-center overflow-hidden">
    {{-- Background Image --}}
    @if($blog->featured_image)
        <img src="{{ asset($blog->featured_image) }}" alt="{{ $blog->title }}" class="absolute inset-0 w-full h-full object-cover opacity-60">
    @else
        <img src="{{ asset('images/about/hero.png') }}" alt="{{ $blog->title }}" class="absolute inset-0 w-full h-full object-cover opacity-60">
    @endif
    <div class="absolute inset-0 bg-gradient-to-t from-brand-dark via-brand-dark/40 to-transparent"></div>

    <div class="relative z-10 w-full max-w-[1000px] mx-auto px-4 lg:px-8 pb-16 lg:pb-24 text-center">
        <div class="inline-flex items-center gap-3 text-xs mb-6 font-bold uppercase tracking-widest text-white">
            @if($blog->category)
                <span class="bg-brand-text/50 backdrop-blur-md border border-white/20 px-4 py-1.5 rounded-full">{{ $blog->category }}</span>
            @endif
            <span class="text-white/80">{{ $blog->published_at?->format('F d, Y') }}</span>
            <span class="text-white/40">•</span>
            <span class="text-white/80">{{ $blog->read_time }} min read</span>
        </div>
        
        <h1 class="text-4xl sm:text-6xl lg:text-7xl font-heading font-extrabold leading-[1.1] text-white tracking-tight mb-6">
            {{ $blog->title }}
        </h1>
        
        @if($blog->excerpt)
            <p class="text-white/80 text-lg sm:text-2xl max-w-3xl mx-auto font-medium">
                {{ $blog->excerpt }}
            </p>
        @endif
    </div>
</section>

{{-- Article Body --}}
<article class="max-w-[800px] mx-auto px-4 lg:px-8 py-20 lg:py-32">
    {{-- Author Badge --}}
    <div class="flex items-center justify-between border-b border-brand-border pb-10 mb-12">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-brand-dark text-white flex items-center justify-center text-xl font-heading font-extrabold shadow-lg">
                {{ strtoupper(substr($blog->author, 0, 1)) }}
            </div>
            <div>
                <p class="text-lg font-heading font-extrabold text-brand-dark">{{ $blog->author }}</p>
                <p class="text-sm text-brand-muted">{{ number_format($blog->views) }} views</p>
            </div>
        </div>
    </div>

    <div class="prose prose-lg md:prose-xl max-w-none prose-headings:font-heading prose-headings:text-brand-dark prose-p:text-brand-muted prose-a:text-brand-text prose-strong:text-brand-dark prose-li:text-brand-muted prose-ul:list-disc prose-li:marker:text-brand-dark prose-li:my-2 prose-ul:pl-6 prose-p:leading-relaxed prose-img:rounded-3xl prose-img:shadow-xl">
        {!! $blog->body !!}
    </div>

    {{-- Tags --}}
    @if(is_array($blog->tags) && count($blog->tags) > 0)
    <div class="mt-12 pt-8 border-t border-brand-border">
        <div class="flex flex-wrap items-center gap-2">
            <span class="text-xs font-bold uppercase tracking-wider text-brand-muted mr-2">Tags:</span>
            @foreach($blog->tags as $tag)
                <span class="px-3 py-1 text-xs font-semibold bg-brand-light text-brand-dark rounded-full border border-brand-border">{{ $tag }}</span>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Share --}}
    <div class="mt-8 pt-8 border-t border-brand-border flex flex-col sm:flex-row items-center justify-between gap-4">
        <p class="text-sm font-bold text-brand-dark">Share this article</p>
        <div class="flex items-center gap-3">
            <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($blog->title) }}" target="_blank" class="w-10 h-10 rounded-full bg-brand-light flex items-center justify-center text-brand-muted hover:bg-brand-text hover:text-white transition-all">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            </a>
            <a href="https://wa.me/?text={{ urlencode($blog->title . ' ' . url()->current()) }}" target="_blank" class="w-10 h-10 rounded-full bg-brand-light flex items-center justify-center text-brand-muted hover:bg-[#25D366] hover:text-white transition-all">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
            </a>
        </div>
    </div>
</article>

{{-- Related Posts --}}
@if($related->count() > 0)
<section class="bg-brand-off-white border-t border-brand-border py-16">
    <div class="max-w-[1440px] mx-auto px-4 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-heading font-extrabold text-center mb-12">YOU MAY ALSO LIKE</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($related as $post)
            <a href="{{ route('frontend.blog.show', $post->slug) }}" class="group block">
                <div class="relative aspect-[16/10] bg-brand-light rounded-2xl overflow-hidden mb-4">
                    @if($post->featured_image)
                        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
                    @else
                        <div class="w-full h-full flex items-center justify-center"><svg class="w-10 h-10 text-brand-border" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg></div>
                    @endif
                </div>
                <p class="text-xs text-brand-muted mb-2">{{ $post->published_at?->format('M d, Y') }} · {{ $post->read_time }} min read</p>
                <h3 class="font-bold text-brand-dark group-hover:text-brand-muted transition-colors line-clamp-2">{{ $post->title }}</h3>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
