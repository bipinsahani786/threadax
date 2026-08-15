@extends('frontend.layouts.app')
@section('title', 'Track Your Order — ThreadAX')
@section('meta_description', 'Track your ThreadAX streetwear package live with your Order ID or AWB tracking number.')

@section('content')
<div class="bg-brand-white min-h-screen">

    {{-- ═══════════ BREADCRUMB & HERO ═══════════ --}}
    <section class="border-b border-brand-border bg-brand-off-white/60 py-10 lg:py-14">
        <div class="max-w-[1240px] mx-auto px-4 lg:px-8">
            {{-- Breadcrumbs --}}
            <nav class="flex items-center gap-2 text-xs text-brand-muted mb-4 font-medium" aria-label="Breadcrumb">
                <a href="{{ route('frontend.home') }}" class="hover:text-brand-dark transition-colors">Home</a>
                <svg class="w-3.5 h-3.5 text-brand-muted/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-brand-dark font-semibold">Track Order</span>
            </nav>

            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-dark text-white text-[11px] font-bold tracking-wider uppercase mb-4">
                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    Live Shipment Tracker
                </div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-heading font-extrabold text-brand-dark tracking-tight mb-3">
                    Track Your Order
                </h1>
                <p class="text-sm sm:text-base text-brand-muted leading-relaxed">
                    Check the real-time shipping status and estimated delivery time of your recent ThreadAX order.
                </p>
            </div>
        </div>
    </section>

    {{-- ═══════════ TRACKING CARD & INFO ═══════════ --}}
    <section class="py-12 lg:py-16 bg-brand-off-white/40">
        <div class="max-w-[1240px] mx-auto px-4 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
                
                {{-- Left Column: Tracking Form Card --}}
                <div class="lg:col-span-7">
                    <div class="bg-white rounded-2xl border border-brand-border p-6 sm:p-8 shadow-sm">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-brand-border">
                            <div class="w-10 h-10 rounded-xl bg-brand-dark text-white flex items-center justify-center shrink-0 shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                            <div>
                                <h2 class="font-heading font-bold text-base text-brand-dark">Search by Order ID or AWB</h2>
                                <p class="text-xs text-brand-muted">Enter the details sent to your SMS or confirmation email.</p>
                            </div>
                        </div>

                        <form action="{{ route('account.orders') }}" method="GET" class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-brand-dark uppercase tracking-wider mb-1.5">Order ID / Tracking Number</label>
                                <input type="text" name="order_search" class="w-full px-4 py-2.5 bg-brand-off-white border border-brand-border rounded-xl text-xs sm:text-sm text-brand-dark placeholder-brand-muted focus:outline-none focus:border-brand-dark focus:bg-white transition-all" placeholder="e.g. THX-102938 or 1432190829" required>
                                <p class="text-[11px] text-brand-muted mt-1">Found in your order confirmation SMS / WhatsApp.</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-brand-dark uppercase tracking-wider mb-1.5">Phone Number or Email</label>
                                <input type="text" name="contact_info" class="w-full px-4 py-2.5 bg-brand-off-white border border-brand-border rounded-xl text-xs sm:text-sm text-brand-dark placeholder-brand-muted focus:outline-none focus:border-brand-dark focus:bg-white transition-all" placeholder="Enter phone or email used during checkout" required>
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="btn-primary w-full !py-3 text-xs sm:text-sm font-semibold flex items-center justify-center gap-2">
                                    Track Live Shipment
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Right Column: Information & Help --}}
                <div class="lg:col-span-5 space-y-5">
                    {{-- Quick Account Info --}}
                    <div class="bg-white rounded-2xl border border-brand-border p-6 shadow-sm space-y-3">
                        <div class="w-9 h-9 rounded-xl bg-brand-light flex items-center justify-center text-brand-dark">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <h3 class="font-heading font-bold text-sm text-brand-dark">Have an account with us?</h3>
                        <p class="text-xs text-brand-muted leading-relaxed">
                            Log into your customer dashboard to view your complete order history, download tax invoices, and track live shipments with 1-click.
                        </p>
                        <div class="pt-1">
                            @auth
                                <a href="{{ route('account.orders') }}" class="text-xs font-bold text-brand-dark hover:underline flex items-center gap-1">
                                    View My Orders
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            @else
                                <a href="{{ route('auth.login') }}" class="text-xs font-bold text-brand-dark hover:underline flex items-center gap-1">
                                    Log In to Dashboard
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            @endauth
                        </div>
                    </div>

                    {{-- Need Assistance --}}
                    <div class="bg-brand-off-white/80 rounded-2xl border border-brand-border p-6 space-y-3">
                        <h4 class="font-heading font-bold text-xs uppercase tracking-wider text-brand-dark">Order not showing?</h4>
                        <p class="text-xs text-brand-muted leading-relaxed">
                            It may take up to 6–12 hours for new courier tracking numbers to reflect active location scans on carrier servers. If you need immediate assistance:
                        </p>
                        <div class="pt-1">
                            <a href="{{ route('frontend.page.show', 'contact') }}" class="text-xs font-bold text-brand-dark hover:underline flex items-center gap-1">
                                Contact Customer Support
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

</div>
@endsection
