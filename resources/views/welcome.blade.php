@extends('frontend.layouts.app')

@section('title', 'Premium Streetwear')

@section('content')

{{-- ═══════════ HERO SECTION ═══════════ --}}
<section class="relative w-full h-[85vh] min-h-[500px] sm:min-h-[600px] overflow-hidden bg-brand-light flex items-center justify-center">
    {{-- Background Image --}}
    <img src="{{ asset('images/hero-full.png') }}" alt="Premium Streetwear" class="absolute inset-0 w-full h-full object-cover object-top opacity-90">
    
    {{-- Dark Overlay for text readability (optional, but good for white text) --}}
    <div class="absolute inset-0 bg-black/30"></div>

    {{-- Content --}}
    <div class="relative z-10 text-center px-4 max-w-4xl mx-auto w-full">
        <p class="text-xs sm:text-sm font-bold uppercase tracking-[0.3em] text-white mb-4 sm:mb-6">The New Standard</p>
        <h1 class="text-[10vw] sm:text-6xl md:text-7xl font-heading font-extrabold text-white leading-tight mb-6 sm:mb-8 drop-shadow-lg">
            REDEFINE YOUR<br>STREETWEAR
        </h1>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="#" class="btn-primary w-full sm:w-auto text-center text-sm uppercase tracking-wider px-8 py-4 bg-white text-brand-dark hover:bg-brand-light hover:text-brand-dark hover:shadow-xl border border-transparent">
                Shop Collection
            </a>
        </div>
    </div>
</section>

{{-- ═══════════ FEATURED CATEGORIES (SPLIT) ═══════════ --}}
<section class="w-full">
    <div class="flex flex-col md:flex-row w-full">
        {{-- Men --}}
        <div class="relative w-full md:w-1/2 aspect-[4/5] md:aspect-auto md:h-[700px] overflow-hidden group cursor-pointer">
            <img src="{{ asset('images/banner-men.png') }}" alt="Shop Men" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
            <div class="absolute inset-0 bg-black/20 group-hover:bg-black/30 transition-colors duration-500"></div>
            <div class="absolute bottom-8 left-6 sm:bottom-12 sm:left-12 z-10 text-white">
                <h2 class="text-3xl sm:text-4xl font-heading font-bold mb-3 sm:mb-4">MENSWEAR</h2>
                <a href="#" class="inline-block border-b-2 border-white pb-1 text-xs sm:text-sm font-semibold uppercase tracking-widest hover:text-brand-light hover:border-brand-light transition-colors">
                    Explore →
                </a>
            </div>
        </div>

        {{-- Women --}}
        <div class="relative w-full md:w-1/2 aspect-[4/5] md:aspect-auto md:h-[700px] overflow-hidden group cursor-pointer">
            <img src="{{ asset('images/banner-women.png') }}" alt="Shop Women" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
            <div class="absolute inset-0 bg-black/20 group-hover:bg-black/30 transition-colors duration-500"></div>
            <div class="absolute bottom-8 left-6 sm:bottom-12 sm:left-12 z-10 text-white">
                <h2 class="text-3xl sm:text-4xl font-heading font-bold mb-3 sm:mb-4">WOMENSWEAR</h2>
                <a href="#" class="inline-block border-b-2 border-white pb-1 text-xs sm:text-sm font-semibold uppercase tracking-widest hover:text-brand-light hover:border-brand-light transition-colors">
                    Explore →
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════ OVERSIZED COLLECTION BANNER ═══════════ --}}
<section class="py-16 bg-white">
    <div class="max-w-[1440px] mx-auto px-4 lg:px-8">
        <div class="relative w-full h-[500px] overflow-hidden rounded-lg group cursor-pointer">
            <img src="{{ asset('images/banner-oversized.png') }}" alt="Oversized Collection" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
            <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-transparent"></div>
            <div class="absolute inset-0 flex flex-col justify-center px-12 md:px-24 z-10 text-white max-w-2xl">
                <h2 class="text-5xl font-heading font-extrabold mb-4">OVERSIZED<br>ESSENTIALS</h2>
                <p class="text-lg text-gray-200 mb-8 max-w-md">Premium heavyweight cotton. Drop shoulders. The perfect fit for everyday comfort.</p>
                <a href="#" class="btn-primary inline-flex self-start bg-white text-brand-dark hover:bg-brand-light border-transparent px-8 py-3 text-sm uppercase tracking-widest">
                    Shop Now
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════ TRENDING PRODUCTS ═══════════ --}}
<section class="py-12 bg-brand-off-white border-y border-brand-border">
    <div class="max-w-[1440px] mx-auto px-4 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-heading font-extrabold uppercase tracking-wide">Trending Now</h2>
            <a href="#" class="text-sm font-semibold text-brand-text border-b-2 border-brand-text hover:text-brand-muted hover:border-brand-muted transition-colors uppercase tracking-wider pb-1">View All</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @for($i = 1; $i <= 4; $i++)
                <div class="group cursor-pointer">
                    <div class="aspect-[3/4] bg-brand-border relative overflow-hidden mb-4">
                        <div class="absolute inset-0 flex items-center justify-center text-brand-muted text-sm">
                            Product Image
                        </div>
                        @if($i === 1)
                            <span class="absolute top-3 left-3 bg-brand-dark text-white text-[10px] font-bold uppercase tracking-wider px-2 py-1">New</span>
                        @endif
                        <div class="absolute bottom-0 left-0 right-0 p-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                            <button class="w-full bg-white/90 backdrop-blur-sm text-brand-dark font-semibold py-3 text-sm hover:bg-white transition-colors border border-brand-border shadow-sm">
                                Quick Add
                            </button>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-brand-muted mb-1">ThreadAx</p>
                        <h3 class="text-sm font-semibold text-brand-text truncate group-hover:underline">Premium Drop Shoulder Tee</h3>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-sm font-bold text-brand-text">₹699</span>
                            <span class="text-xs text-brand-muted line-through">₹1,299</span>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>
</section>

{{-- ═══════════ INSTAGRAM REELS EMBED ═══════════ --}}
<section class="py-20 bg-white overflow-hidden">
    <div class="max-w-[1440px] mx-auto px-4 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-heading font-extrabold uppercase tracking-wider mb-2">@threadax.co.in</h2>
            <p class="text-brand-muted text-sm uppercase tracking-widest">Follow us on Instagram</p>
        </div>

        {{-- Reels Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 justify-items-center">
            {{-- Dummy Embeds (Replace blockquotes with actual reel URLs later) --}}
            @for($r = 1; $r <= 4; $r++)
            <div class="w-full max-w-[320px] aspect-[9/16] bg-brand-off-white rounded-lg overflow-hidden relative shadow-sm border border-brand-border flex items-center justify-center">
                <svg class="w-12 h-12 text-brand-muted/30 absolute z-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                
                <div class="relative z-10 w-full h-full flex flex-col items-center justify-center bg-black/5 p-4 text-center">
                    <p class="text-sm font-semibold mb-2">Instagram Reel</p>
                    <p class="text-xs text-brand-muted mb-4">Embed your actual reel here</p>
                    <a href="https://www.instagram.com/threadax.co.in/" target="_blank" class="px-4 py-2 bg-white border border-brand-border text-xs font-semibold hover:bg-brand-light transition-colors">Watch on IG</a>
                </div>
            </div>
            @endfor
        </div>
        
        <div class="mt-12 text-center">
            <a href="https://www.instagram.com/threadax.co.in/" target="_blank" class="btn-outline inline-flex items-center gap-2 text-sm px-8 py-3 uppercase tracking-widest font-semibold border-brand-text">
                Explore More on Instagram
            </a>
        </div>
    </div>
</section>

{{-- ═══════════ FEATURES STRIP (MINIMAL) ═══════════ --}}
<section class="py-16 border-t border-brand-border bg-brand-off-white">
    <div class="max-w-[1440px] mx-auto px-4 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center">
                <p class="text-sm font-heading font-extrabold uppercase tracking-widest text-brand-text mb-2">Free Shipping</p>
                <p class="text-xs text-brand-muted">On all orders above ₹999</p>
            </div>
            <div class="text-center">
                <p class="text-sm font-heading font-extrabold uppercase tracking-widest text-brand-text mb-2">7 Days Return</p>
                <p class="text-xs text-brand-muted">No questions asked</p>
            </div>
            <div class="text-center">
                <p class="text-sm font-heading font-extrabold uppercase tracking-widest text-brand-text mb-2">Premium Quality</p>
                <p class="text-xs text-brand-muted">100% heavy cotton fabrics</p>
            </div>
            <div class="text-center">
                <p class="text-sm font-heading font-extrabold uppercase tracking-widest text-brand-text mb-2">Secure Payments</p>
                <p class="text-xs text-brand-muted">Encrypted secure checkout</p>
            </div>
        </div>
    </div>
</section>

@endsection
