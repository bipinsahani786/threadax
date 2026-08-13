@extends('frontend.layouts.app')
@section('title', $page->title . ' — ThreadAX')

@section('content')
<section class="relative bg-brand-dark py-20 lg:py-32 overflow-hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-gradient-to-br from-brand-dark via-brand-dark to-brand-text/50"></div>
    <div class="relative z-10 text-center px-4 max-w-3xl mx-auto">
        <h1 class="text-4xl md:text-5xl lg:text-7xl font-heading font-extrabold text-white mb-6 uppercase tracking-tight drop-shadow-xl">{{ $page->title }}</h1>
        <div class="w-24 h-1 bg-white/20 mx-auto rounded-full"></div>
    </div>
</section>

<section class="py-20 lg:py-32 bg-white">
    <div class="max-w-[1200px] mx-auto px-4 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-24">
            <div class="lg:col-span-4 lg:sticky lg:top-32 self-start">
                <h2 class="text-3xl font-heading font-extrabold text-brand-dark mb-4">Read The Policy</h2>
                <div class="w-12 h-1 bg-brand-text mb-6"></div>
                <p class="text-brand-muted text-lg mb-8">Please read the terms carefully to understand your rights and obligations when using our platform.</p>
                
                <div class="p-6 bg-brand-off-white border border-brand-border rounded-2xl">
                    <p class="text-brand-dark font-bold text-sm mb-1">Last Updated</p>
                    <p class="text-brand-muted text-sm">{{ $page->updated_at->format('F d, Y') }}</p>
                </div>
            </div>
            
            <div class="lg:col-span-8">
                <div class="prose prose-lg md:prose-xl max-w-none prose-headings:font-heading prose-headings:text-brand-dark prose-p:text-brand-muted prose-a:text-brand-text prose-strong:text-brand-dark prose-li:text-brand-muted prose-ul:list-disc prose-li:marker:text-brand-dark prose-li:my-2 prose-ul:pl-6 prose-p:leading-relaxed border-l-0 lg:border-l lg:border-brand-border lg:pl-12">
                    {!! $page->body !!}
                </div>
                
                <div class="mt-16 pt-8 border-t border-brand-border">
                    <p class="text-brand-muted text-sm mb-4">Have questions about our {{ strtolower($page->title) }}?</p>
                    <a href="{{ route('frontend.page.show', 'contact') }}" class="inline-flex items-center gap-2 btn-primary !py-3 !px-8 text-base shadow-lg shadow-brand-dark/10">
                        Contact Support
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
