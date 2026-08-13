@extends('frontend.layouts.app')
@section('title', 'Returns & Exchanges — ThreadAX')

@section('content')
<section class="bg-brand-dark py-16 lg:py-24 text-center">
    <div class="max-w-3xl mx-auto px-4">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-heading font-extrabold text-white mb-6 uppercase tracking-tight">Returns & Exchanges</h1>
        <p class="text-lg text-white/70">7-Day easy returns and exchanges for a stress-free shopping experience.</p>
    </div>
</section>

{{-- Step-by-Step UI --}}
<section class="py-16 bg-white border-b border-brand-border">
    <div class="max-w-[1200px] mx-auto px-4 lg:px-8">
        <h2 class="text-3xl font-heading font-extrabold text-brand-dark text-center mb-12 uppercase">How it works</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
            {{-- Connecting Line (Desktop) --}}
            <div class="hidden md:block absolute top-12 left-[16%] right-[16%] h-0.5 bg-brand-border z-0"></div>
            
            {{-- Step 1 --}}
            <div class="relative z-10 text-center flex flex-col items-center group">
                <div class="w-24 h-24 bg-white border-4 border-brand-border group-hover:border-brand-dark transition-colors rounded-full flex items-center justify-center text-brand-dark mb-6 shadow-sm">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-brand-dark mb-3">1. Submit Request</h3>
                <p class="text-brand-muted text-sm">Enter your Order ID and select the item you wish to return or exchange within 7 days of delivery.</p>
            </div>

            {{-- Step 2 --}}
            <div class="relative z-10 text-center flex flex-col items-center group">
                <div class="w-24 h-24 bg-white border-4 border-brand-border group-hover:border-brand-dark transition-colors rounded-full flex items-center justify-center text-brand-dark mb-6 shadow-sm">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <h3 class="text-xl font-bold text-brand-dark mb-3">2. Pack the Item</h3>
                <p class="text-brand-muted text-sm">Keep all tags intact and pack the product safely. Our delivery partner will pick it up from your address.</p>
            </div>

            {{-- Step 3 --}}
            <div class="relative z-10 text-center flex flex-col items-center group">
                <div class="w-24 h-24 bg-white border-4 border-brand-border group-hover:border-brand-dark transition-colors rounded-full flex items-center justify-center text-brand-dark mb-6 shadow-sm">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-brand-dark mb-3">3. Get Refund/Exchange</h3>
                <p class="text-brand-muted text-sm">Once the quality check passes, we'll immediately dispatch the new item or process your refund.</p>
            </div>
        </div>

        <div class="mt-16 text-center">
            <a href="#" class="btn-primary inline-flex items-center gap-2 px-10 py-4 text-lg shadow-xl shadow-brand-dark/10">
                Start a Return
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- Prose Policy from Database --}}
<section class="py-20 lg:py-32 bg-white">
    <div class="max-w-[1200px] mx-auto px-4 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-24">
            <div class="lg:col-span-4 lg:sticky lg:top-32 self-start">
                <h2 class="text-3xl font-heading font-extrabold text-brand-dark mb-4">Read The Policy</h2>
                <div class="w-12 h-1 bg-brand-text mb-6"></div>
                <p class="text-brand-muted text-lg">Please read our returns and exchange policy carefully to understand your rights and the requirements for a successful return.</p>
            </div>
            
            <div class="lg:col-span-8">
                <div class="prose prose-lg md:prose-xl max-w-none prose-headings:font-heading prose-headings:text-brand-dark prose-p:text-brand-muted prose-a:text-brand-text prose-strong:text-brand-dark prose-li:text-brand-muted prose-ul:list-disc prose-li:marker:text-brand-dark prose-li:my-2 prose-ul:pl-6 prose-p:leading-relaxed border-l-0 lg:border-l lg:border-brand-border lg:pl-12">
                    {!! $page->body !!}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
