@extends('frontend.layouts.app')
@section('title', 'About Us — ThreadAX')
@section('meta_description', 'Discover the story behind ThreadAX. India\'s fastest-growing premium streetwear brand, crafting 280GSM heavyweight apparel.')

@section('content')

    {{-- Clean Hero Section --}}
    <section class="relative bg-brand-dark min-h-[40vh] sm:min-h-[50vh] flex items-center justify-center py-12 sm:py-16 overflow-hidden">
        <div class="absolute inset-0">
            <img src="{{ asset('images/about/hero.png') }}" alt="ThreadAX Streetwear"
                class="w-full h-full object-cover opacity-60">
            <div class="absolute inset-0 bg-gradient-to-t from-brand-dark via-brand-dark/50 to-transparent"></div>
        </div>

        <div class="relative z-10 text-center px-4 max-w-3xl mx-auto">
            <span
                class="inline-block py-1 px-3 border border-white/30 rounded-full text-white/90 text-[11px] font-bold tracking-widest uppercase mb-4 backdrop-blur-sm">
                Est. 2026
            </span>
            <h1
                class="text-2xl sm:text-4xl lg:text-5xl font-heading font-extrabold text-white mb-3 sm:mb-4 uppercase tracking-tight">
                Redefining Streetwear
            </h1>
            <p class="text-xs sm:text-sm md:text-base text-white/80 max-w-xl mx-auto font-medium leading-relaxed">
                Premium oversized essentials, crafted from the finest 280GSM heavyweight cotton. No compromises, no luxury markups.
            </p>
        </div>
    </section>

    {{-- The Story Section (Elegant Two Column) --}}
    <section class="py-10 sm:py-16 lg:py-20 bg-white">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8 lg:gap-16 items-start">
                <div class="lg:w-1/3">
                    <h2 class="text-xl sm:text-2xl lg:text-3xl font-heading font-extrabold text-brand-dark mb-2 sm:mb-3 uppercase">
                        Our Story
                    </h2>
                    <div class="w-12 h-1 bg-brand-text"></div>
                </div>

                <div class="lg:w-2/3 space-y-4 text-xs sm:text-sm text-brand-muted leading-relaxed">
                    <p class="text-sm sm:text-base font-heading font-semibold text-brand-dark leading-snug">
                        ThreadAX was born out of frustration with the modern fashion industry. You either pay ₹3000+ for decent quality, or you settle for thin, fast-fashion t-shirts that lose their shape after two washes. We knew there had to be a better way.
                    </p>
                    <p>
                        We spent over a year researching fabrics, fits, and manufacturing processes. Our goal was simple: create the perfect oversized t-shirt. A t-shirt that feels substantial, drapes beautifully, and is built to last.
                    </p>
                    <p>
                        By partnering directly with the best manufacturers in India and cutting out traditional retail middlemen, we deliver genuine luxury-grade streetwear at honest, accessible prices. Every ThreadAX piece is meticulously designed in-house, featuring our signature drop-shoulder fit and premium 280GSM cotton.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Core Values (Clean Grid) --}}
    <section class="py-10 sm:py-16 lg:py-20 bg-brand-off-white border-y border-brand-border">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 sm:mb-12">
                <h2 class="text-xl sm:text-2xl lg:text-3xl font-heading font-extrabold text-brand-dark uppercase mb-2">
                    The ThreadAX Standard
                </h2>
                <p class="text-brand-muted text-xs sm:text-sm max-w-xl mx-auto">
                    We don't cut corners. Here is what makes our streetwear essentials different.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                {{-- Value 1 --}}
                <div class="text-center flex flex-col items-center bg-white p-6 rounded-2xl border border-brand-border shadow-sm">
                    <div
                        class="w-12 h-12 bg-brand-light rounded-2xl flex items-center justify-center text-brand-dark mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="text-base font-heading font-bold text-brand-dark mb-2">280GSM Heavyweight</h3>
                    <p class="text-brand-muted text-xs sm:text-sm leading-relaxed">
                        Our signature fabric. Thick enough to hold a crisp oversized structure, yet breathable for all-day comfort.
                    </p>
                </div>

                {{-- Value 2 --}}
                <div class="text-center flex flex-col items-center bg-white p-6 rounded-2xl border border-brand-border shadow-sm">
                    <div
                        class="w-12 h-12 bg-brand-light rounded-2xl flex items-center justify-center text-brand-dark mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243 4.243 3 3 0 004.243-4.243zm0-5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-heading font-bold text-brand-dark mb-2">Meticulous Craftsmanship</h3>
                    <p class="text-brand-muted text-xs sm:text-sm leading-relaxed">
                        Double-stitched seams, high-density ribbed collars that won't sag, and bio-washed for an ultra-soft hand feel.
                    </p>
                </div>

                {{-- Value 3 --}}
                <div class="text-center flex flex-col items-center bg-white p-6 rounded-2xl border border-brand-border shadow-sm">
                    <div
                        class="w-12 h-12 bg-brand-light rounded-2xl flex items-center justify-center text-brand-dark mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-heading font-bold text-brand-dark mb-2">Honest Direct Pricing</h3>
                    <p class="text-brand-muted text-xs sm:text-sm leading-relaxed">
                        By selling directly online without distributor markups, we deliver luxury-grade streetwear at accessible pricing.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Instagram Community --}}
    <section class="py-10 sm:py-16 lg:py-20 bg-white">
        <div class="max-w-[1240px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between sm:items-end mb-8 gap-4">
                <div>
                    <h2 class="text-xl sm:text-2xl lg:text-3xl font-heading font-extrabold text-brand-dark uppercase mb-1">
                        Join The Culture
                    </h2>
                    <p class="text-xs sm:text-sm text-brand-muted">Tag us to get featured <a href="https://www.instagram.com/threadax.co.in/" target="_blank"
                            class="text-brand-dark font-bold underline hover:text-brand-muted transition">@threadax.co.in</a>
                    </p>
                </div>
                <a href="https://www.instagram.com/threadax.co.in/" target="_blank"
                    class="btn-outline text-xs !py-2 !px-4 self-start sm:self-auto">Follow on Instagram</a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
                <a href="https://www.instagram.com/threadax.co.in/" target="_blank"
                    class="block aspect-square overflow-hidden rounded-xl bg-brand-light">
                    <img src="{{ asset('images/about/grid1.png') }}"
                        class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                        alt="Instagram Post">
                </a>
                <a href="https://www.instagram.com/threadax.co.in/" target="_blank"
                    class="block aspect-square overflow-hidden rounded-xl bg-brand-light">
                    <img src="{{ asset('images/about/grid2.png') }}"
                        class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                        alt="Instagram Reel">
                </a>
                <a href="https://www.instagram.com/threadax.co.in/" target="_blank"
                    class="block aspect-square overflow-hidden rounded-xl bg-brand-light">
                    <img src="{{ asset('images/about/grid3.png') }}"
                        class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                        alt="Instagram Reel">
                </a>
                <a href="https://www.instagram.com/threadax.co.in/" target="_blank"
                    class="block aspect-square overflow-hidden rounded-xl bg-brand-light">
                    <img src="{{ asset('images/about/grid4.png') }}"
                        class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                        alt="Instagram Post">
                </a>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-12 sm:py-16 bg-brand-dark text-center">
        <div class="max-w-xl mx-auto px-4 sm:px-6">
            <h2 class="text-xl sm:text-2xl md:text-3xl font-heading font-extrabold text-white mb-2 sm:mb-3 uppercase">
                Ready to Upgrade?
            </h2>
            <p class="text-white/70 text-xs sm:text-sm mb-6 leading-relaxed">
                Explore our latest collection of heavyweight oversized essentials.
            </p>
            <a href="{{ route('frontend.products.index') }}"
                class="inline-flex items-center justify-center gap-2 bg-white text-brand-dark font-bold text-xs sm:text-sm px-6 py-3 rounded-xl hover:bg-gray-100 transition-colors w-full sm:w-auto">
                Shop The Collection
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>
    </section>

@endsection