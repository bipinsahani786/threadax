@extends('frontend.layouts.app')
@section('title', ($page->meta_title ?? $page->title) . ' — ThreadAX')
@section('meta_description', $page->meta_description ?? 'Read official documentation and policies for ThreadAX streetwear.')

@section('content')
<div class="bg-brand-white min-h-screen">

    {{-- ═══════════ BREADCRUMB & HERO ═══════════ --}}
    <section class="border-b border-brand-border bg-brand-off-white/60 py-6 sm:py-10 lg:py-14">
        <div class="max-w-[1240px] mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Breadcrumbs --}}
            <nav class="flex items-center gap-2 text-xs text-brand-muted mb-3 sm:mb-4 font-medium" aria-label="Breadcrumb">
                <a href="{{ route('frontend.home') }}" class="hover:text-brand-dark transition-colors">Home</a>
                <svg class="w-3.5 h-3.5 text-brand-muted/60 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-brand-dark font-semibold truncate">{{ $page->title }}</span>
            </nav>

            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brand-dark text-white text-[11px] font-bold tracking-wider uppercase mb-3">
                    <svg class="w-3.5 h-3.5 text-brand-accent shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Official Document
                </div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-heading font-extrabold text-brand-dark tracking-tight mb-2 sm:mb-3">
                    {{ $page->title }}
                </h1>
                <p class="text-xs sm:text-sm md:text-base text-brand-muted leading-relaxed">
                    Please read these terms and policies carefully. They govern your relationship and usage of the ThreadAX website and services.
                </p>
            </div>
        </div>
    </section>

    {{-- ═══════════ CONTENT & SIDEBAR ═══════════ --}}
    <section class="py-8 sm:py-12 lg:py-16 bg-white">
        <div class="max-w-[1240px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-12 items-start">
                
                {{-- Left Sticky Legal Navigation Sidebar --}}
                <div class="lg:col-span-4 lg:sticky lg:top-28 space-y-4 order-2 lg:order-1">
                    
                    {{-- Last Updated Card --}}
                    <div class="p-4 sm:p-6 rounded-2xl border border-brand-border bg-brand-off-white/80 space-y-2.5">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-brand-muted font-medium">Document Status</span>
                            <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 font-bold text-[10px]">Active & Verified</span>
                        </div>
                        <div class="pt-2 border-t border-brand-border">
                            <p class="text-xs text-brand-muted mb-0.5">Last Updated</p>
                            <p class="text-xs sm:text-sm font-bold text-brand-dark">
                                {{ $page->updated_at ? $page->updated_at->format('F d, Y') : date('F d, Y') }}
                            </p>
                        </div>
                    </div>

                    {{-- Other Legal Pages Navigation --}}
                    <div class="p-4 sm:p-6 rounded-2xl border border-brand-border bg-white shadow-sm space-y-3 sm:space-y-4">
                        <h3 class="font-heading font-extrabold text-xs uppercase tracking-wider text-brand-dark">
                            Policies & Legal Links
                        </h3>
                        
                        <div class="space-y-1 text-xs sm:text-sm">
                            <a href="{{ route('frontend.page.show', 'privacy-policy') }}" class="flex items-center justify-between p-2.5 rounded-xl {{ request()->is('*privacy-policy*') ? 'bg-brand-dark text-white font-bold' : 'text-brand-muted hover:text-brand-dark hover:bg-brand-off-white' }} transition-colors">
                                <span>Privacy Policy</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            <a href="{{ route('frontend.page.show', 'terms-of-service') }}" class="flex items-center justify-between p-2.5 rounded-xl {{ request()->is('*terms-of-service*') ? 'bg-brand-dark text-white font-bold' : 'text-brand-muted hover:text-brand-dark hover:bg-brand-off-white' }} transition-colors">
                                <span>Terms of Service</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            <a href="{{ route('frontend.page.show', 'returns-exchanges') }}" class="flex items-center justify-between p-2.5 rounded-xl {{ request()->is('*returns-exchanges*') ? 'bg-brand-dark text-white font-bold' : 'text-brand-muted hover:text-brand-dark hover:bg-brand-off-white' }} transition-colors">
                                <span>Returns & Exchanges</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            <a href="{{ route('frontend.page.show', 'shipping-info') }}" class="flex items-center justify-between p-2.5 rounded-xl {{ request()->is('*shipping-info*') ? 'bg-brand-dark text-white font-bold' : 'text-brand-muted hover:text-brand-dark hover:bg-brand-off-white' }} transition-colors">
                                <span>Shipping Information</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            <a href="{{ route('frontend.page.show', 'faq') }}" class="flex items-center justify-between p-2.5 rounded-xl {{ request()->is('*faq*') ? 'bg-brand-dark text-white font-bold' : 'text-brand-muted hover:text-brand-dark hover:bg-brand-off-white' }} transition-colors">
                                <span>Help & FAQs</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>

                    {{-- Grievance Support Box --}}
                    <div class="p-4 sm:p-6 rounded-2xl border border-brand-border bg-brand-off-white/80 space-y-1.5">
                        <h4 class="font-heading font-bold text-xs uppercase tracking-wider text-brand-dark">Grievance & Legal Desk</h4>
                        <p class="text-xs text-brand-muted leading-relaxed">
                            For legal inquiries or privacy concerns, please contact our legal desk at <a href="mailto:support@threadax.co.in" class="font-semibold text-brand-dark underline">support@threadax.co.in</a>.
                        </p>
                    </div>

                </div>

                {{-- Right Column: Main Document Content --}}
                <div class="lg:col-span-8 order-1 lg:order-2">
                    <div class="border border-brand-border rounded-2xl p-4 sm:p-6 lg:p-8 bg-white shadow-sm overflow-hidden">
                        
                        <div class="prose prose-neutral max-w-none text-xs sm:text-sm prose-headings:font-heading prose-headings:text-brand-dark prose-headings:font-extrabold prose-h2:text-base sm:prose-h2:text-lg prose-h2:mt-6 prose-h2:mb-2.5 prose-h2:border-b prose-h2:border-brand-border prose-h2:pb-2 prose-h3:text-sm sm:prose-h3:text-base prose-h3:mt-4 prose-h3:mb-1.5 prose-p:text-brand-muted prose-p:leading-relaxed prose-li:text-brand-muted prose-li:my-1 prose-strong:text-brand-dark prose-a:text-brand-dark prose-a:font-semibold prose-a:underline hover:prose-a:text-brand-accent">
                            {!! $page->body !!}
                        </div>

                        {{-- Footer Action --}}
                        <div class="mt-8 pt-6 border-t border-brand-border flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <p class="text-xs font-bold text-brand-dark">Have questions about these terms?</p>
                                <p class="text-xs text-brand-muted">Our customer support team is available Mon-Sat.</p>
                            </div>
                            <a href="{{ route('frontend.page.show', 'contact') }}" class="btn-primary inline-flex items-center justify-center gap-2 !py-2 !px-5 text-xs font-semibold w-full sm:w-auto">
                                Contact Support
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

</div>
@endsection
