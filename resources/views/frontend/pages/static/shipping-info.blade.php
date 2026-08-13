@extends('frontend.layouts.app')
@section('title', 'Shipping Information — ThreadAX')

@section('content')
<section class="bg-brand-dark py-16 lg:py-24 text-center">
    <div class="max-w-3xl mx-auto px-4">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-heading font-extrabold text-white mb-6 uppercase tracking-tight">Shipping Information</h1>
        <p class="text-lg text-white/70">Fast, reliable, and trackable shipping across all of India.</p>
    </div>
</section>

{{-- Delivery Estimates Cards --}}
<section class="py-16 bg-brand-off-white border-b border-brand-border">
    <div class="max-w-[1200px] mx-auto px-4 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            {{-- Free Shipping Card --}}
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-brand-border flex flex-col items-center text-center">
                <div class="w-16 h-16 bg-[#F3F4F6] rounded-full flex items-center justify-center text-brand-dark mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-xl font-bold font-heading text-brand-dark mb-2">Free Prepaid Shipping</h3>
                <p class="text-brand-muted">Enjoy free shipping across India on all prepaid orders.</p>
            </div>

            {{-- Metro Delivery --}}
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-brand-border flex flex-col items-center text-center">
                <div class="w-16 h-16 bg-[#F3F4F6] rounded-full flex items-center justify-center text-brand-dark mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h3 class="text-xl font-bold font-heading text-brand-dark mb-2">Metro Cities</h3>
                <p class="text-brand-muted">2 to 4 working days delivery time for Tier-1 and Metro cities.</p>
            </div>

            {{-- General Delivery --}}
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-brand-border flex flex-col items-center text-center">
                <div class="w-16 h-16 bg-[#F3F4F6] rounded-full flex items-center justify-center text-brand-dark mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-xl font-bold font-heading text-brand-dark mb-2">Rest of India</h3>
                <p class="text-brand-muted">5 to 7 working days delivery time for all other pincodes.</p>
            </div>

        </div>
    </div>
</section>

{{-- Prose Policy from Database --}}
<section class="py-20 lg:py-32 bg-white">
    <div class="max-w-[1200px] mx-auto px-4 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-24">
            <div class="lg:col-span-4 lg:sticky lg:top-32 self-start">
                <h2 class="text-3xl font-heading font-extrabold text-brand-dark mb-4">Detailed Policy</h2>
                <div class="w-12 h-1 bg-brand-text mb-6"></div>
                <p class="text-brand-muted text-lg">Full details regarding our dispatch times, shipping partners, and delivery procedures.</p>
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
