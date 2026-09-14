@extends('frontend.layouts.app')
@section('title', 'Returns & Exchanges — ThreadAX')
@section('meta_description', 'Learn about ThreadAX 7-day easy returns and exchange policy. Doorstep pickup, hassle-free size swaps, and fast refunds.')

@section('content')
<div class="bg-brand-white min-h-screen">

    {{-- ═══════════ BREADCRUMB & HERO ═══════════ --}}
    <section class="border-b border-brand-border bg-brand-off-white/60 py-6 sm:py-10 lg:py-14">
        <div class="max-w-[1240px] mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Breadcrumbs --}}
            <nav class="flex items-center gap-2 text-xs text-brand-muted mb-3 sm:mb-4 font-medium" aria-label="Breadcrumb">
                <a href="{{ route('frontend.home') }}" class="hover:text-brand-dark transition-colors">Home</a>
                <svg class="w-3.5 h-3.5 text-brand-muted/60 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-brand-dark font-semibold truncate">Returns & Exchanges</span>
            </nav>

            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brand-dark text-white text-[11px] font-bold tracking-wider uppercase mb-3">
                    <svg class="w-3.5 h-3.5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    7-Day Hassle-Free Policy
                </div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-heading font-extrabold text-brand-dark tracking-tight mb-2 sm:mb-3">
                    Returns & Exchanges
                </h1>
                <p class="text-xs sm:text-sm md:text-base text-brand-muted leading-relaxed">
                    We want you to love your streetwear. If the fit isn’t perfect or you changed your mind, our straightforward 7-day return and exchange process has you covered.
                </p>
            </div>
        </div>
    </section>

    {{-- ═══════════ KEY GUARANTEE HIGHLIGHTS ═══════════ --}}
    <section class="py-6 sm:py-10 border-b border-brand-border bg-white">
        <div class="max-w-[1240px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
                {{-- Item 1 --}}
                <div class="p-4 sm:p-5 rounded-2xl border border-brand-border bg-brand-off-white/40 hover:border-brand-dark/30 transition-all flex items-start gap-3.5">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-brand-dark text-white flex items-center justify-center shrink-0 shadow-sm">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-xs sm:text-sm text-brand-dark mb-0.5 sm:mb-1">7 Days Window</h3>
                        <p class="text-[11px] sm:text-xs text-brand-muted leading-relaxed">Initiate a return or size exchange within 7 days of delivery.</p>
                    </div>
                </div>

                {{-- Item 2 --}}
                <div class="p-4 sm:p-5 rounded-2xl border border-brand-border bg-brand-off-white/40 hover:border-brand-dark/30 transition-all flex items-start gap-3.5">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-brand-dark text-white flex items-center justify-center shrink-0 shadow-sm">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-xs sm:text-sm text-brand-dark mb-0.5 sm:mb-1">Doorstep Pickup</h3>
                        <p class="text-[11px] sm:text-xs text-brand-muted leading-relaxed">Our courier partner will pick up the package directly from your address.</p>
                    </div>
                </div>

                {{-- Item 3 --}}
                <div class="p-4 sm:p-5 rounded-2xl border border-brand-border bg-brand-off-white/40 hover:border-brand-dark/30 transition-all flex items-start gap-3.5">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-brand-dark text-white flex items-center justify-center shrink-0 shadow-sm">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-xs sm:text-sm text-brand-dark mb-0.5 sm:mb-1">Free Size Swaps</h3>
                        <p class="text-[11px] sm:text-xs text-brand-muted leading-relaxed">Need a different size? We arrange seamless replacement drops quickly.</p>
                    </div>
                </div>

                {{-- Item 4 --}}
                <div class="p-4 sm:p-5 rounded-2xl border border-brand-border bg-brand-off-white/40 hover:border-brand-dark/30 transition-all flex items-start gap-3.5">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-brand-dark text-white flex items-center justify-center shrink-0 shadow-sm">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-xs sm:text-sm text-brand-dark mb-0.5 sm:mb-1">Fast 3-5 Day Refund</h3>
                        <p class="text-[11px] sm:text-xs text-brand-muted leading-relaxed">Refunds are credited directly to your original payment method / UPI.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════ HOW IT WORKS (3 STEPS) ═══════════ --}}
    <section class="py-8 sm:py-12 lg:py-16 bg-brand-off-white/40 border-b border-brand-border">
        <div class="max-w-[1240px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-6 sm:mb-10">
                <span class="text-[11px] sm:text-xs font-bold uppercase tracking-widest text-brand-muted">Simple 3-Step Process</span>
                <h2 class="text-lg sm:text-xl md:text-2xl font-heading font-extrabold text-brand-dark mt-1">
                    How Return & Exchange Works
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 relative">
                {{-- Step 1 --}}
                <div class="bg-white p-5 sm:p-6 rounded-2xl border border-brand-border shadow-sm flex flex-col justify-between hover:border-brand-dark/40 transition-all">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[11px] font-extrabold text-brand-dark tracking-wider px-2 py-0.5 rounded-md bg-brand-light">STEP 01</span>
                            <div class="w-7 h-7 rounded-full bg-brand-off-white flex items-center justify-center text-brand-dark border border-brand-border">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </div>
                        </div>
                        <h3 class="text-sm sm:text-base font-heading font-bold text-brand-dark mb-1.5">Submit Your Request</h3>
                        <p class="text-xs sm:text-sm text-brand-muted leading-relaxed">
                            Log in to your account, select the item from your recent orders, or email us at <a href="mailto:{{ $globalSettings['contact_email'] ?? 'support@threadax.co.in' }}" class="font-semibold text-brand-dark underline">{{ $globalSettings['contact_email'] ?? 'support@threadax.co.in' }}</a> with your Order ID.
                        </p>
                    </div>
                </div>

                {{-- Step 2 --}}
                <div class="bg-white p-5 sm:p-6 rounded-2xl border border-brand-border shadow-sm flex flex-col justify-between hover:border-brand-dark/40 transition-all">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[11px] font-extrabold text-brand-dark tracking-wider px-2 py-0.5 rounded-md bg-brand-light">STEP 02</span>
                            <div class="w-7 h-7 rounded-full bg-brand-off-white flex items-center justify-center text-brand-dark border border-brand-border">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                            </div>
                        </div>
                        <h3 class="text-sm sm:text-base font-heading font-bold text-brand-dark mb-1.5">Pack Product Safely</h3>
                        <p class="text-xs sm:text-sm text-brand-muted leading-relaxed">
                            Ensure the product is unwashed, unworn, with all original brand tags and polybag intact. Our courier partner will arrive for doorstep pickup in 24-48 hrs.
                        </p>
                    </div>
                </div>

                {{-- Step 3 --}}
                <div class="bg-white p-5 sm:p-6 rounded-2xl border border-brand-border shadow-sm flex flex-col justify-between hover:border-brand-dark/40 transition-all">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[11px] font-extrabold text-brand-dark tracking-wider px-2 py-0.5 rounded-md bg-brand-light">STEP 03</span>
                            <div class="w-7 h-7 rounded-full bg-brand-off-white flex items-center justify-center text-brand-dark border border-brand-border">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                        </div>
                        <h3 class="text-sm sm:text-base font-heading font-bold text-brand-dark mb-1.5">Quality Check & Refund</h3>
                        <p class="text-xs sm:text-sm text-brand-muted leading-relaxed">
                            Once the returned parcel reaches our warehouse and passes QC inspection, your replacement size is dispatched or your full refund is released within 3-5 days.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Action Row --}}
            <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row items-stretch sm:items-center justify-center gap-2.5 sm:gap-3 text-center">
                @auth
                    <a href="{{ route('account.orders') }}" class="btn-primary inline-flex items-center justify-center gap-2 !py-2.5 !px-6 text-xs sm:text-sm">
                        Go to My Orders & Return
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                @else
                    <a href="{{ route('frontend.page.show', 'contact') }}" class="btn-primary inline-flex items-center justify-center gap-2 !py-2.5 !px-6 text-xs sm:text-sm">
                        Contact Support for Return
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                @endauth
                <a href="{{ route('frontend.page.show', 'track-order') }}" class="btn-outline inline-flex items-center justify-center gap-2 !py-2.5 !px-5 text-xs sm:text-sm">
                    Track Existing Shipment
                </a>
            </div>
        </div>
    </section>

    {{-- ═══════════ DETAILED POLICY & GUIDELINES ═══════════ --}}
    <section class="py-8 sm:py-12 lg:py-16 bg-white">
        <div class="max-w-[1240px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-12 items-start">
                
                {{-- Left Sticky Summary Sidebar --}}
                <div class="lg:col-span-4 lg:sticky lg:top-28 space-y-4 order-2 lg:order-1">
                    <div class="p-4 sm:p-6 rounded-2xl border border-brand-border bg-brand-off-white/80 space-y-3 sm:space-y-4">
                        <h3 class="font-heading font-extrabold text-xs sm:text-sm text-brand-dark uppercase tracking-wider">
                            Policy Quick Summary
                        </h3>
                        
                        <div class="space-y-2.5 text-xs">
                            <div class="flex justify-between items-center py-1.5 border-b border-brand-border">
                                <span class="text-brand-muted">Return Window</span>
                                <span class="font-bold text-brand-dark">7 Days from Delivery</span>
                            </div>
                            <div class="flex justify-between items-center py-1.5 border-b border-brand-border">
                                <span class="text-brand-muted">Item Condition</span>
                                <span class="font-bold text-brand-dark">Unworn, Tags Intact</span>
                            </div>
                            <div class="flex justify-between items-center py-1.5 border-b border-brand-border">
                                <span class="text-brand-muted">Pickup Charges</span>
                                <span class="font-bold text-emerald-600">Free Doorstep Pickup</span>
                            </div>
                            <div class="flex justify-between items-center py-1.5 border-b border-brand-border">
                                <span class="text-brand-muted">Refund Method</span>
                                <span class="font-bold text-brand-dark">Original Payment / UPI</span>
                            </div>
                            <div class="flex justify-between items-center py-1.5">
                                <span class="text-brand-muted">Refund Timeline</span>
                                <span class="font-bold text-brand-dark">3 - 5 Business Days</span>
                            </div>
                        </div>
                    </div>

                    {{-- Need Assistance Card --}}
                    <div class="p-4 sm:p-6 rounded-2xl border border-brand-border bg-white shadow-sm space-y-2.5">
                        <div class="w-8 h-8 rounded-xl bg-brand-light flex items-center justify-center text-brand-dark">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <h4 class="font-heading font-bold text-xs sm:text-sm text-brand-dark">Have questions about your return?</h4>
                        <p class="text-xs text-brand-muted leading-relaxed">
                            Our team is available Mon-Sat (10:00 AM – 7:00 PM IST) to assist you with tracking, size swaps, or refunds.
                        </p>
                        <div class="pt-1">
                            <a href="{{ route('frontend.page.show', 'contact') }}" class="text-xs font-bold text-brand-dark hover:underline flex items-center gap-1.5">
                                Reach Customer Support
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Structured Clean Policy Content --}}
                <div class="lg:col-span-8 order-1 lg:order-2">
                    <div class="border border-brand-border rounded-2xl p-4 sm:p-6 lg:p-8 bg-white shadow-sm">
                        
                        {{-- Policy Header --}}
                        <div class="border-b border-brand-border pb-4 sm:pb-6 mb-6">
                            <h2 class="text-lg sm:text-xl lg:text-2xl font-heading font-extrabold text-brand-dark mb-1 sm:mb-2">
                                ThreadAX Returns & Exchange Terms
                            </h2>
                            <p class="text-xs sm:text-sm text-brand-muted leading-relaxed">
                                Please review the complete terms below to ensure a smooth return or exchange experience.
                            </p>
                        </div>

                        {{-- Section 1: Return Eligibility --}}
                        <div class="space-y-6 text-xs sm:text-sm text-brand-muted leading-relaxed">
                            <div>
                                <h3 class="font-heading font-bold text-sm sm:text-base text-brand-dark mb-2">
                                    1. Eligibility Criteria
                                </h3>
                                <p class="mb-3 text-xs sm:text-sm">To be eligible for a return or size exchange, your items must strictly meet the following conditions:</p>
                                
                                <div class="space-y-2">
                                    <div class="p-3 rounded-xl bg-brand-off-white border border-brand-border/80 flex items-start gap-2.5">
                                        <svg class="w-4 h-4 text-emerald-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span class="text-xs sm:text-sm text-brand-dark">Return request must be placed within <strong>7 days</strong> of the verified delivery timestamp.</span>
                                    </div>
                                    <div class="p-3 rounded-xl bg-brand-off-white border border-brand-border/80 flex items-start gap-2.5">
                                        <svg class="w-4 h-4 text-emerald-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span class="text-xs sm:text-sm text-brand-dark">Product must be <strong>unworn, unwashed, and undamaged</strong> without perfume or makeup marks.</span>
                                    </div>
                                    <div class="p-3 rounded-xl bg-brand-off-white border border-brand-border/80 flex items-start gap-2.5">
                                        <svg class="w-4 h-4 text-emerald-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span class="text-xs sm:text-sm text-brand-dark">All original <strong>brand tags, barcodes, and protective polybags</strong> must remain attached and intact.</span>
                                    </div>
                                </div>
                            </div>

                            <hr class="border-brand-border">

                            {{-- Section 2: Size Exchanges --}}
                            <div>
                                <h3 class="font-heading font-bold text-sm sm:text-base text-brand-dark mb-2">
                                    2. Size & Color Exchanges
                                </h3>
                                <p class="text-xs sm:text-sm mb-3">
                                    We offer <strong>1 complimentary size exchange</strong> per order. If your ordered oversized t-shirt or hoodie does not fit as desired, you can request an alternate size.
                                </p>
                                <div class="p-3.5 rounded-xl bg-brand-light text-xs text-brand-dark border border-brand-border flex items-start gap-2.5">
                                    <svg class="w-4 h-4 text-brand-muted mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Exchange fulfillment is subject to size availability in warehouse stock. If the requested size is out of stock, a full refund or store credit will be issued.</span>
                                </div>
                            </div>

                            <hr class="border-brand-border">

                            {{-- Section 3: Refunds & Timelines --}}
                            <div>
                                <h3 class="font-heading font-bold text-sm sm:text-base text-brand-dark mb-2">
                                    3. Refund Process & Timelines
                                </h3>
                                <p class="text-xs sm:text-sm mb-3">
                                    Once our quality check team receives the returned parcel and verifies its condition:
                                </p>
                                <div class="space-y-2">
                                    <div class="p-3.5 rounded-xl bg-brand-off-white border border-brand-border/80">
                                        <p class="font-bold text-xs sm:text-sm text-brand-dark mb-1">• Prepaid Orders (Cards / UPI / NetBanking):</p>
                                        <p class="text-xs sm:text-sm text-brand-muted">Refund will be processed back to the original payment source within <strong>3-5 business days</strong>.</p>
                                    </div>
                                    <div class="p-3.5 rounded-xl bg-brand-off-white border border-brand-border/80">
                                        <p class="font-bold text-xs sm:text-sm text-brand-dark mb-1">• COD Orders (when applicable):</p>
                                        <p class="text-xs sm:text-sm text-brand-muted">Refund will be credited via secure UPI ID or bank transfer details provided by the customer.</p>
                                    </div>
                                </div>
                            </div>

                            <hr class="border-brand-border">

                            {{-- Section 4: Non-Returnables & Exceptions --}}
                            <div>
                                <h3 class="font-heading font-bold text-sm sm:text-base text-brand-dark mb-2">
                                    4. Non-Returnable Items & Sale Events
                                </h3>
                                <div class="space-y-2">
                                    <div class="p-3 rounded-xl bg-amber-50/60 border border-amber-200/80 flex items-start gap-2.5">
                                        <svg class="w-4 h-4 text-amber-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        <span class="text-xs sm:text-sm text-amber-950">Items purchased during flash clearance sales or marked as "Final Sale" are eligible for <strong>size exchange only</strong> (or store credit), not monetary refunds.</span>
                                    </div>
                                    <div class="p-3 rounded-xl bg-amber-50/60 border border-amber-200/80 flex items-start gap-2.5">
                                        <svg class="w-4 h-4 text-amber-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        <span class="text-xs sm:text-sm text-amber-950">Accessories, socks, and hygiene-sensitive items cannot be returned once unsealed.</span>
                                    </div>
                                </div>
                            </div>

                            {{-- DB Content Fallback if admin added custom text --}}
                            @if(!empty(strip_tags($page->body ?? '')))
                                <div class="mt-6 pt-6 border-t border-brand-border">
                                    <div class="prose prose-sm max-w-none prose-p:text-xs sm:prose-p:text-sm prose-p:text-brand-muted prose-headings:font-heading prose-headings:font-bold prose-headings:text-brand-dark">
                                        {!! $page->body !!}
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

</div>
@endsection
