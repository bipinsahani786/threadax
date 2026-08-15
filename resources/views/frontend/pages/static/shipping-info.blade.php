@extends('frontend.layouts.app')
@section('title', 'Shipping Information — ThreadAX')
@section('meta_description', 'Fast, reliable, and trackable shipping across all of India. Free shipping on orders above ₹999.')

@section('content')
<div class="bg-brand-white min-h-screen">

    {{-- ═══════════ BREADCRUMB & HERO ═══════════ --}}
    <section class="border-b border-brand-border bg-brand-off-white/60 py-6 sm:py-10 lg:py-14">
        <div class="max-w-[1240px] mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Breadcrumbs --}}
            <nav class="flex items-center gap-2 text-xs text-brand-muted mb-3 sm:mb-4 font-medium" aria-label="Breadcrumb">
                <a href="{{ route('frontend.home') }}" class="hover:text-brand-dark transition-colors">Home</a>
                <svg class="w-3.5 h-3.5 text-brand-muted/60 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-brand-dark font-semibold truncate">Shipping Information</span>
            </nav>

            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brand-dark text-white text-[11px] font-bold tracking-wider uppercase mb-3">
                    <svg class="w-3.5 h-3.5 text-sky-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Pan-India Tracked Delivery
                </div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-heading font-extrabold text-brand-dark tracking-tight mb-2 sm:mb-3">
                    Shipping & Delivery Information
                </h1>
                <p class="text-xs sm:text-sm md:text-base text-brand-muted leading-relaxed">
                    We deliver premium streetwear across 20,000+ pincodes in India. Enjoy free prepaid shipping, same-day order processing, and live shipment tracking.
                </p>
            </div>
        </div>
    </section>

    {{-- ═══════════ KEY SHIPPING HIGHLIGHTS ═══════════ --}}
    <section class="py-6 sm:py-10 border-b border-brand-border bg-white">
        <div class="max-w-[1240px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
                {{-- Highlight 1 --}}
                <div class="p-4 sm:p-5 rounded-2xl border border-brand-border bg-brand-off-white/40 hover:border-brand-dark/30 transition-all flex items-start gap-3.5">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-brand-dark text-white flex items-center justify-center shrink-0 shadow-sm">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-xs sm:text-sm text-brand-dark mb-0.5 sm:mb-1">Free Shipping > ₹999</h3>
                        <p class="text-[11px] sm:text-xs text-brand-muted leading-relaxed">Enjoy zero shipping fee on orders above ₹999. Flat ₹79 for smaller orders.</p>
                    </div>
                </div>

                {{-- Highlight 2 --}}
                <div class="p-4 sm:p-5 rounded-2xl border border-brand-border bg-brand-off-white/40 hover:border-brand-dark/30 transition-all flex items-start gap-3.5">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-brand-dark text-white flex items-center justify-center shrink-0 shadow-sm">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-xs sm:text-sm text-brand-dark mb-0.5 sm:mb-1">Same-Day Dispatch</h3>
                        <p class="text-[11px] sm:text-xs text-brand-muted leading-relaxed">Orders placed before 2:00 PM IST (Mon-Sat) leave our hub on the same day.</p>
                    </div>
                </div>

                {{-- Highlight 3 --}}
                <div class="p-4 sm:p-5 rounded-2xl border border-brand-border bg-brand-off-white/40 hover:border-brand-dark/30 transition-all flex items-start gap-3.5">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-brand-dark text-white flex items-center justify-center shrink-0 shadow-sm">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-xs sm:text-sm text-brand-dark mb-0.5 sm:mb-1">Tier-1 Logistics</h3>
                        <p class="text-[11px] sm:text-xs text-brand-muted leading-relaxed">Delivered via verified partners: BlueDart, Delhivery, DTDC & Shadowfax.</p>
                    </div>
                </div>

                {{-- Highlight 4 --}}
                <div class="p-4 sm:p-5 rounded-2xl border border-brand-border bg-brand-off-white/40 hover:border-brand-dark/30 transition-all flex items-start gap-3.5">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-brand-dark text-white flex items-center justify-center shrink-0 shadow-sm">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-xs sm:text-sm text-brand-dark mb-0.5 sm:mb-1">Real-Time Updates</h3>
                        <p class="text-[11px] sm:text-xs text-brand-muted leading-relaxed">Instant tracking links dispatched via SMS and WhatsApp upon order dispatch.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════ DELIVERY ESTIMATES MATRIX ═══════════ --}}
    <section class="py-8 sm:py-12 lg:py-16 bg-brand-off-white/40 border-b border-brand-border">
        <div class="max-w-[1240px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-6 sm:mb-10">
                <span class="text-[11px] sm:text-xs font-bold uppercase tracking-widest text-brand-muted">Transit Estimates</span>
                <h2 class="text-lg sm:text-xl md:text-2xl font-heading font-extrabold text-brand-dark mt-1">
                    Estimated Delivery Timelines
                </h2>
                <p class="text-xs sm:text-sm text-brand-muted mt-1.5 sm:mt-2">
                    Average turnaround time once your package is dispatched from our fulfillment center.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                {{-- Metro Cities --}}
                <div class="bg-white p-5 sm:p-6 rounded-2xl border border-brand-border shadow-sm flex flex-col justify-between hover:border-brand-dark/40 transition-all">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-bold uppercase tracking-wider border border-emerald-200">Express Delivery</span>
                            <div class="w-7 h-7 rounded-full bg-brand-light flex items-center justify-center text-brand-dark">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                        </div>
                        <h3 class="text-base font-heading font-bold text-brand-dark mb-1">Metro & Tier 1 Cities</h3>
                        <p class="text-xs text-brand-muted mb-4 leading-relaxed">Delhi NCR, Mumbai, Bengaluru, Hyderabad, Chennai, Kolkata, Pune & Ahmedabad.</p>
                    </div>
                    <div class="pt-3 border-t border-brand-border flex items-baseline justify-between">
                        <span class="text-xs font-medium text-brand-muted">Estimated Transit:</span>
                        <span class="text-sm sm:text-base font-extrabold text-brand-dark">2 – 4 Days</span>
                    </div>
                </div>

                {{-- Tier 2 & 3 Cities --}}
                <div class="bg-white p-5 sm:p-6 rounded-2xl border border-brand-border shadow-sm flex flex-col justify-between hover:border-brand-dark/40 transition-all">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-2.5 py-0.5 rounded-full bg-sky-50 text-sky-700 text-[11px] font-bold uppercase tracking-wider border border-sky-200">Standard Delivery</span>
                            <div class="w-7 h-7 rounded-full bg-brand-light flex items-center justify-center text-brand-dark">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                        </div>
                        <h3 class="text-base font-heading font-bold text-brand-dark mb-1">Tier 2 & 3 Cities</h3>
                        <p class="text-xs text-brand-muted mb-4 leading-relaxed">State capitals, major industrial zones, and standard pincodes across India.</p>
                    </div>
                    <div class="pt-3 border-t border-brand-border flex items-baseline justify-between">
                        <span class="text-xs font-medium text-brand-muted">Estimated Transit:</span>
                        <span class="text-sm sm:text-base font-extrabold text-brand-dark">4 – 6 Days</span>
                    </div>
                </div>

                {{-- Remote & North East --}}
                <div class="bg-white p-5 sm:p-6 rounded-2xl border border-brand-border shadow-sm flex flex-col justify-between hover:border-brand-dark/40 transition-all">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 text-[11px] font-bold uppercase tracking-wider border border-amber-200">Special Zone</span>
                            <div class="w-7 h-7 rounded-full bg-brand-light flex items-center justify-center text-brand-dark">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                        </div>
                        <h3 class="text-base font-heading font-bold text-brand-dark mb-1">North-East & Remote Areas</h3>
                        <p class="text-xs text-brand-muted mb-4 leading-relaxed">Hill stations, islands, and remote geographical regions requiring special transit routing.</p>
                    </div>
                    <div class="pt-3 border-t border-brand-border flex items-baseline justify-between">
                        <span class="text-xs font-medium text-brand-muted">Estimated Transit:</span>
                        <span class="text-sm sm:text-base font-extrabold text-brand-dark">6 – 9 Days</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════ DETAILED SHIPPING POLICY & SIDEBAR ═══════════ --}}
    <section class="py-8 sm:py-12 lg:py-16 bg-white">
        <div class="max-w-[1240px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-12 items-start">
                
                {{-- Left Sticky Track & Help Sidebar --}}
                <div class="lg:col-span-4 lg:sticky lg:top-28 space-y-4 order-2 lg:order-1">
                    {{-- Track Order Card --}}
                    <div class="p-5 sm:p-6 rounded-2xl border border-brand-border bg-brand-dark text-white space-y-3 sm:space-y-4">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-white/10 flex items-center justify-center text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-sm sm:text-base text-white mb-1">Track Live Order</h3>
                            <p class="text-xs text-white/70 leading-relaxed">
                                Enter your Order ID or AWB Tracking number to see real-time transit status.
                            </p>
                        </div>
                        <a href="{{ route('frontend.page.show', 'track-order') }}" class="w-full inline-flex items-center justify-center gap-2 bg-white text-brand-dark hover:bg-white/90 font-bold text-xs py-2.5 px-4 rounded-xl transition-all">
                            Open Order Tracker
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>

                    {{-- Support Box --}}
                    <div class="p-5 sm:p-6 rounded-2xl border border-brand-border bg-brand-off-white/80 space-y-2.5">
                        <h4 class="font-heading font-bold text-xs sm:text-sm text-brand-dark">Need shipment assistance?</h4>
                        <p class="text-xs text-brand-muted leading-relaxed">
                            If your order has been in transit longer than the estimated timeline or if you need to update your address, write to us.
                        </p>
                        <div class="pt-1">
                            <a href="{{ route('frontend.page.show', 'contact') }}" class="text-xs font-bold text-brand-dark hover:underline flex items-center gap-1.5">
                                Contact Logistics Desk
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
                                ThreadAX Shipping Guidelines & Terms
                            </h2>
                            <p class="text-xs sm:text-sm text-brand-muted leading-relaxed">
                                Everything you need to know regarding dispatch cutoffs, packaging standards, and delivery attempts.
                            </p>
                        </div>

                        <div class="space-y-5 text-xs sm:text-sm text-brand-muted leading-relaxed">
                            {{-- Point 1 --}}
                            <div>
                                <h3 class="font-heading font-bold text-sm sm:text-base text-brand-dark mb-2">
                                    1. Order Processing & Dispatch Timelines
                                </h3>
                                <p class="text-xs sm:text-sm mb-2">
                                    All orders undergo double-pass quality check and secure weatherproof packing before dispatch.
                                </p>
                                <div class="space-y-2">
                                    <div class="p-3 rounded-xl bg-brand-off-white border border-brand-border/80">
                                        <span class="font-bold text-brand-dark">• Orders placed before 2:00 PM (Mon-Sat):</span>
                                        <p class="text-brand-muted mt-0.5">Dispatched on the same business day.</p>
                                    </div>
                                    <div class="p-3 rounded-xl bg-brand-off-white border border-brand-border/80">
                                        <span class="font-bold text-brand-dark">• Orders after 2:00 PM or Sunday/Holidays:</span>
                                        <p class="text-brand-muted mt-0.5">Dispatched on the next immediate working business day.</p>
                                    </div>
                                </div>
                            </div>

                            <hr class="border-brand-border">

                            {{-- Point 2 --}}
                            <div>
                                <h3 class="font-heading font-bold text-sm sm:text-base text-brand-dark mb-2">
                                    2. Shipping Fees & Charges
                                </h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 my-2.5">
                                    <div class="p-3.5 rounded-xl bg-brand-light border border-brand-border">
                                        <p class="font-bold text-xs text-brand-dark mb-0.5">Prepaid Orders ≥ ₹999</p>
                                        <p class="text-xs text-emerald-600 font-extrabold">FREE SHIPPING (₹0)</p>
                                    </div>
                                    <div class="p-3.5 rounded-xl bg-brand-light border border-brand-border">
                                        <p class="font-bold text-xs text-brand-dark mb-0.5">Orders under ₹999</p>
                                        <p class="text-xs text-brand-dark font-extrabold">Flat ₹79 Shipping Fee</p>
                                    </div>
                                </div>
                            </div>

                            <hr class="border-brand-border">

                            {{-- Point 3 --}}
                            <div>
                                <h3 class="font-heading font-bold text-sm sm:text-base text-brand-dark mb-2">
                                    3. Delivery Attempts & OTP Verification
                                </h3>
                                <p class="text-xs sm:text-sm">
                                    Our courier partners make up to <strong>3 delivery attempts</strong> before returning a parcel to our origin fulfillment hub. Please ensure your contact phone number is reachable to verify courier OTP during delivery.
                                </p>
                            </div>

                            <hr class="border-brand-border">

                            {{-- Point 4 --}}
                            <div>
                                <h3 class="font-heading font-bold text-sm sm:text-base text-brand-dark mb-2">
                                    4. International Shipping
                                </h3>
                                <p class="text-xs sm:text-sm">
                                    We currently ship exclusively across India. International worldwide shipping to the US, UK, UAE, and Europe is currently in beta and launching soon.
                                </p>
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
