@extends('frontend.layouts.app')

@section('title', 'ThreadAX — Premium Streetwear | Official Store India')
@section('meta_description', 'Shop premium oversized streetwear at ThreadAX. Heavyweight cotton, bold designs, perfect fits. Free shipping above ₹999. Shop Men, Women, Oversized collections.')

@section('content')

{{-- ═══════════ HERO SECTION ═══════════ --}}
@if(isset($banners['hero']) && $banners['hero']->count() > 0)
    <section x-data="{ activeSlide: 0, slides: {{ $banners['hero']->count() }} }" class="relative w-full h-[90vh] min-h-[560px] overflow-hidden bg-brand-dark cursor-pointer">
        @foreach($banners['hero'] as $index => $banner)
            <a href="{{ url($banner->link ?? '/products') }}" x-show="activeSlide === {{ $index }}"
                 x-transition:enter="transition ease-out duration-700"
                 x-transition:enter-start="opacity-0 scale-105"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-500"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute inset-0 w-full h-full flex items-center justify-center group">
                <img src="{{ asset('storage/' . $banner->image_path) }}" alt="{{ $banner->title }}" class="absolute inset-0 w-full h-full object-cover object-center opacity-80 group-hover:scale-105 transition-transform duration-[20s]">
                <div class="absolute inset-0 bg-gradient-to-b from-black/20 via-black/30 to-black/60 group-hover:from-black/10 transition-colors duration-700"></div>
                <div class="relative z-10 text-center px-4 max-w-5xl mx-auto w-full">
                    @if($banner->subtitle)
                        <p class="text-xs sm:text-sm font-bold uppercase tracking-[0.4em] text-white/80 mb-5">{{ $banner->subtitle }}</p>
                    @endif
                    <h1 class="text-[11vw] sm:text-7xl md:text-8xl font-heading font-extrabold text-white leading-[0.9] mb-8 drop-shadow-2xl">
                        {!! nl2br(e($banner->title)) !!}
                    </h1>
                    @if($banner->link && $banner->button_text)
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <span class="inline-flex items-center justify-center gap-2 bg-white text-brand-dark text-sm font-bold uppercase tracking-[0.2em] px-10 py-4 group-hover:bg-brand-light transition-all duration-300 group-hover:shadow-2xl group-hover:-translate-y-0.5">
                                {{ $banner->button_text }}
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </span>
                        </div>
                    @endif
                </div>
            </a>
        @endforeach

        @if($banners['hero']->count() > 1)
            <button @click.prevent="activeSlide = activeSlide === 0 ? slides - 1 : activeSlide - 1" class="absolute left-4 sm:left-8 top-1/2 -translate-y-1/2 z-20 w-12 h-12 bg-white/10 backdrop-blur-sm border border-white/20 text-white hover:bg-white/20 flex items-center justify-center transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button @click.prevent="activeSlide = activeSlide === slides - 1 ? 0 : activeSlide + 1" class="absolute right-4 sm:right-8 top-1/2 -translate-y-1/2 z-20 w-12 h-12 bg-white/10 backdrop-blur-sm border border-white/20 text-white hover:bg-white/20 flex items-center justify-center transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <div class="absolute bottom-8 left-0 right-0 flex justify-center gap-2 z-20">
                @foreach($banners['hero'] as $index => $banner)
                    <button @click.prevent="activeSlide = {{ $index }}" :class="{'bg-white w-8': activeSlide === {{ $index }}, 'bg-white/40 w-2': activeSlide !== {{ $index }}}" class="h-1.5 rounded-full transition-all duration-500"></button>
                @endforeach
            </div>
            <div x-init="setInterval(() => { activeSlide = activeSlide === slides - 1 ? 0 : activeSlide + 1 }, 5000)"></div>
        @endif
    </section>
@else
    {{-- Static Fallback Hero --}}
    <section class="relative w-full h-[90vh] min-h-[560px] overflow-hidden bg-brand-light flex items-center justify-center">
        <a href="{{ route('frontend.products.index') }}" class="absolute inset-0 group">
            <img src="{{ asset('images/hero-full.png') }}" alt="Premium Streetwear" class="absolute inset-0 w-full h-full object-cover object-top opacity-90 group-hover:scale-105 transition-transform duration-[20s]">
            <div class="absolute inset-0 bg-gradient-to-b from-black/10 via-black/20 to-black/60 group-hover:from-black/5 transition-colors duration-700"></div>
            <div class="absolute inset-0 flex flex-col items-center justify-center z-10 text-center px-4 max-w-5xl mx-auto w-full">
                <p class="text-xs sm:text-sm font-bold uppercase tracking-[0.4em] text-white/80 mb-5">The New Standard in Streetwear</p>
                <h1 class="text-[11vw] sm:text-7xl md:text-8xl font-heading font-extrabold text-white leading-[0.9] mb-8 drop-shadow-2xl">
                    REDEFINE<br>YOUR STYLE
                </h1>
                <span class="inline-flex items-center justify-center gap-2 bg-white text-brand-dark text-sm font-bold uppercase tracking-[0.2em] px-10 py-4 group-hover:bg-brand-light transition-all duration-300 group-hover:shadow-2xl group-hover:-translate-y-0.5">
                    Shop Collection
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </span>
            </div>
        </a>
    </section>
@endif


{{-- ═══════════ CATEGORY SPLIT PANELS ═══════════ --}}
<section class="w-full">
    <div class="flex flex-col md:flex-row w-full">
        <a href="{{ route('frontend.products.index', ['category' => $headerCategories->where('name', 'like', '%men%')->first()->id ?? '']) }}" class="relative w-full md:w-1/2 aspect-[4/5] md:aspect-auto md:h-[700px] overflow-hidden group cursor-pointer block">
            <img src="{{ asset('images/banner-men.png') }}" alt="Shop Men" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent group-hover:from-black/80 transition-colors duration-500"></div>
            <div class="absolute bottom-8 left-6 sm:bottom-14 sm:left-12 z-10 text-white">
                <p class="text-xs font-bold uppercase tracking-[0.3em] text-white/60 mb-2">Collection</p>
                <h2 class="text-4xl sm:text-5xl font-heading font-extrabold mb-4">MENSWEAR</h2>
                <span class="inline-flex items-center gap-2 border-b-2 border-white pb-1 text-xs sm:text-sm font-bold uppercase tracking-widest group-hover:gap-4 transition-all duration-300">
                    Explore <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </span>
            </div>
        </a>

        <a href="{{ route('frontend.products.index', ['category' => $headerCategories->where('name', 'like', '%women%')->first()->id ?? '']) }}" class="relative w-full md:w-1/2 aspect-[4/5] md:aspect-auto md:h-[700px] overflow-hidden group cursor-pointer block">
            <img src="{{ asset('images/banner-women.png') }}" alt="Shop Women" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent group-hover:from-black/80 transition-colors duration-500"></div>
            <div class="absolute bottom-8 left-6 sm:bottom-14 sm:left-12 z-10 text-white">
                <p class="text-xs font-bold uppercase tracking-[0.3em] text-white/60 mb-2">Collection</p>
                <h2 class="text-4xl sm:text-5xl font-heading font-extrabold mb-4">WOMENSWEAR</h2>
                <span class="inline-flex items-center gap-2 border-b-2 border-white pb-1 text-xs sm:text-sm font-bold uppercase tracking-widest group-hover:gap-4 transition-all duration-300">
                    Explore <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </span>
            </div>
        </a>
    </div>
</section>

{{-- ═══════════ OVERSIZED COLLECTION BANNER ═══════════ --}}
<section class="py-16 bg-white">
    <div class="max-w-[1440px] mx-auto px-4 lg:px-8">
        <a href="{{ route('frontend.products.index', ['category' => $headerCategories->where('name', 'like', '%oversized%')->first()->id ?? '']) }}" class="relative w-full min-h-[480px] overflow-hidden group cursor-pointer block rounded-xl">
            <img src="{{ asset('images/banner-oversized.png') }}" alt="Oversized Collection" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
            <div class="absolute inset-0 bg-gradient-to-r from-black/85 via-black/50 to-transparent group-hover:from-black/90 transition-colors duration-500"></div>
            <div class="absolute inset-0 flex flex-col justify-center px-8 sm:px-16 md:px-24 z-10 text-white">
                <p class="text-xs font-bold uppercase tracking-[0.4em] text-white/50 mb-4 group-hover:text-white transition-colors duration-300">Signature Drop</p>
                <h2 class="text-5xl sm:text-6xl md:text-7xl font-heading font-extrabold mb-5 leading-tight group-hover:scale-105 origin-left transition-transform duration-500">OVERSIZED<br>ESSENTIALS</h2>
                <p class="text-base sm:text-lg text-gray-300 mb-8 max-w-md group-hover:text-white transition-colors duration-300">Premium heavyweight cotton. Drop shoulders. The perfect fit for everyday comfort and bold street style.</p>
                <div>
                    <span class="inline-flex items-center gap-3 bg-white text-brand-dark text-sm font-bold uppercase tracking-[0.2em] px-8 py-4 group-hover:bg-brand-light transition-all duration-300 group-hover:-translate-y-0.5 group-hover:shadow-2xl">
                        Shop Now <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </span>
                </div>
            </div>
        </a>
    </div>
</section>


{{-- ═══════════ TRENDING NOW (PRODUCTS) ═══════════ --}}
<section class="max-w-[1440px] mx-auto px-4 lg:px-8 py-16 sm:py-24">
    <div class="flex items-end justify-between mb-12">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.4em] text-brand-muted mb-2">Hot Right Now</p>
            <h2 class="text-3xl sm:text-4xl font-heading font-extrabold tracking-tight">TRENDING NOW</h2>
        </div>
        <a href="{{ route('frontend.products.index') }}" class="hidden sm:inline-flex items-center gap-2 border-b-2 border-brand-text pb-1 text-sm font-bold uppercase tracking-widest hover:text-brand-muted transition-colors hover:gap-4 duration-300">
            View All <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-8">
        @forelse($newArrivals->take(12) as $product)
            <a href="{{ route('frontend.products.show', $product->slug) }}" class="group block">
                <div class="relative w-full aspect-[3/4] bg-brand-light overflow-hidden mb-4">
                    @if($product->primary_image)
                        <img src="{{ $product->primary_image->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-200">
                            <span class="text-gray-400 text-sm">No Image</span>
                        </div>
                    @endif

                    {{-- Premium Discount Badge --}}
                    @if($product->discount_percent > 0)
                        <div class="absolute top-3 left-3 z-10">
                            <span class="inline-flex items-center gap-1 bg-gradient-to-r from-orange-500 to-red-500 text-white text-[10px] font-black px-2.5 py-1 uppercase tracking-wider rounded-sm shadow-lg">
                                {{ $product->discount_percent }}% OFF
                            </span>
                        </div>
                    @elseif($loop->first)
                        <div class="absolute top-3 left-3 z-10">
                            <span class="inline-flex items-center bg-brand-text text-white text-[10px] font-black px-2.5 py-1 uppercase tracking-wider rounded-sm shadow-lg">NEW</span>
                        </div>
                    @endif

                    <div class="absolute bottom-4 right-4 bg-white p-2.5 rounded-full shadow-xl opacity-0 translate-y-3 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 hover:bg-brand-text hover:text-white flex items-center justify-center z-10">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                </div>
                <h3 class="text-sm font-bold mb-1 line-clamp-1">{{ $product->name }}</h3>
                <p class="text-brand-muted text-xs mb-2">{{ $product->category->name ?? 'Streetwear' }}</p>
                @if($product->review_count > 0)
                    <div class="flex items-center gap-1 mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-3 h-3 {{ $i <= round($product->average_rating) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                        <span class="text-[10px] text-brand-muted">({{ $product->review_count }})</span>
                    </div>
                @endif
                <div class="flex items-center gap-2">
                    <p class="font-bold text-sm">₹{{ number_format($product->price) }}</p>
                    @if($product->compare_price > $product->price)
                        <p class="text-xs text-brand-muted line-through">₹{{ number_format($product->compare_price) }}</p>
                        <span class="text-[10px] font-bold text-orange-500">{{ $product->discount_percent }}% off</span>
                    @endif
                </div>
            </a>
        @empty
            <div class="col-span-full text-center text-gray-500 py-10">New collections dropping soon.</div>
        @endforelse
    </div>

    <div class="mt-12 text-center">
        <a href="{{ route('frontend.products.index') }}" class="inline-block border-b-2 border-brand-text pb-1 text-sm font-semibold uppercase tracking-widest hover:text-brand-muted hover:border-brand-muted transition-colors">View All Products</a>
    </div>
</section>


{{-- ═══════════ TESTIMONIALS SECTION ═══════════ --}}
@php
    $testimonials = \App\Models\Testimonial::active()->ordered()->get();
@endphp
@if($testimonials->count() > 0)
<section class="py-20 sm:py-28 bg-brand-off-white border-t border-brand-border overflow-hidden relative">
    <div class="max-w-[1440px] mx-auto px-4 lg:px-8 relative z-10">
        <div class="text-center mb-16">
            <p class="text-xs font-bold uppercase tracking-[0.4em] text-brand-muted mb-3">The Community</p>
            <h2 class="text-3xl sm:text-5xl font-heading font-extrabold tracking-tight">WHAT THEY SAY</h2>
        </div>

        {{-- VIDEO Testimonials --}}
        @php $videoTestimonials = $testimonials->filter(fn($t) => $t->video_url); @endphp
        @if($videoTestimonials->count() > 0)
        <div class="mb-20">
            <h3 class="text-xs font-bold uppercase tracking-widest text-brand-muted mb-8 text-center flex items-center justify-center gap-4">
                <span class="h-px bg-brand-border w-12"></span> 
                Video Reviews 
                <span class="h-px bg-brand-border w-12"></span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($videoTestimonials->take(3) as $t)
                @php
                    $videoId = '';
                    if(preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $t->video_url, $m)) {
                        $videoId = $m[1];
                    }
                @endphp
                <div class="bg-white rounded-[24px] overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.12)] transition-shadow duration-300 border border-brand-border group flex flex-col" x-data="{ playing: false }">
                    {{-- Thumbnail or iframe --}}
                    <div class="relative w-full pt-[133%] bg-brand-dark overflow-hidden shrink-0">
                        @if($videoId)
                            <div x-show="!playing" class="absolute inset-0">
                                <img src="https://img.youtube.com/vi/{{ $videoId }}/maxresdefault.jpg" onerror="this.src='https://img.youtube.com/vi/{{ $videoId }}/hqdefault.jpg'" alt="{{ $t->name }}'s review" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent flex items-center justify-center">
                                    <button @click="playing = true" class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center shadow-[0_0_40px_rgba(255,255,255,0.3)] hover:scale-110 hover:bg-white/30 transition-all duration-300 border border-white/40">
                                        <svg class="w-6 h-6 text-white ml-1 drop-shadow-md" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </button>
                                </div>
                            </div>
                            <iframe x-show="playing" :src="playing ? 'https://www.youtube.com/embed/{{ $videoId }}?autoplay=1&mute=0' : ''" class="absolute inset-0 w-full h-full" frameborder="0" allowfullscreen allow="autoplay"></iframe>
                        @else
                            <video controls class="absolute inset-0 w-full h-full object-cover" poster="{{ $t->photo_url }}">
                                <source src="{{ $t->video_url }}" type="video/mp4">
                            </video>
                        @endif
                        
                        <div class="absolute top-4 left-4 flex gap-1 z-10 pointer-events-none" x-show="!playing">
                            @for($i=1; $i<=5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $t->rating ? 'text-yellow-400 drop-shadow-md' : 'text-white/30' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                    </div>
                    <div class="p-6 flex-1 flex flex-col bg-white">
                        <p class="text-sm font-medium text-brand-dark leading-relaxed mb-4 flex-1 italic">"{{ $t->content }}"</p>
                        <div class="flex items-center gap-3 pt-4 border-t border-brand-border">
                            @if($t->photo_url && !$videoId)
                                <img src="{{ $t->photo_url }}" class="w-10 h-10 rounded-full object-cover shadow-sm" alt="{{ $t->name }}">
                            @else
                                <div class="w-10 h-10 rounded-full bg-brand-text text-white flex items-center justify-center text-sm font-bold shrink-0 shadow-sm">{{ strtoupper(substr($t->name, 0, 1)) }}</div>
                            @endif
                            <div>
                                <p class="text-sm font-bold text-brand-dark">{{ $t->name }}</p>
                                <p class="text-[11px] text-brand-muted uppercase tracking-wider font-semibold">{{ $t->designation ?? 'Verified Buyer' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- TEXT Testimonials --}}
        @php $textTestimonials = $testimonials->filter(fn($t) => !$t->video_url)->take(6); @endphp
        @if($textTestimonials->count() > 0)
        <div class="mb-8">
            <h3 class="text-xs font-bold uppercase tracking-widest text-brand-muted mb-8 text-center flex items-center justify-center gap-4">
                <span class="h-px bg-brand-border w-12"></span> 
                Reviews 
                <span class="h-px bg-brand-border w-12"></span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($textTestimonials as $i => $t)
                <div class="bg-white rounded-[24px] p-8 border border-brand-border shadow-[0_2px_12px_rgb(0,0,0,0.02)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-all duration-300 hover:-translate-y-1">
                    <div class="flex justify-between items-start mb-6">
                        <div class="flex items-center gap-1">
                            @for($j=1; $j<=5; $j++)
                                <svg class="w-4 h-4 {{ $j <= $t->rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <svg class="w-6 h-6 text-brand-border" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                    </div>
                    <p class="text-brand-dark text-sm leading-relaxed mb-8 font-medium">"{{ $t->content }}"</p>
                    <div class="flex items-center gap-3">
                        @if($t->photo_url)
                            <img src="{{ $t->photo_url }}" class="w-12 h-12 rounded-full object-cover shadow-sm" alt="{{ $t->name }}">
                        @else
                            <div class="w-12 h-12 rounded-full bg-brand-light text-brand-dark flex items-center justify-center text-sm font-bold shrink-0 border border-brand-border">{{ strtoupper(substr($t->name, 0, 1)) }}</div>
                        @endif
                        <div>
                            <p class="font-bold text-sm text-brand-dark">{{ $t->name }}</p>
                            <p class="text-[11px] font-semibold text-brand-muted uppercase tracking-wider">{{ $t->designation ?? 'Verified Buyer' }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
@endif


{{-- ═══════════ INSTAGRAM REELS ═══════════ --}}
<section class="w-full bg-white py-16 sm:py-24 border-t border-brand-border">
    <div class="max-w-[1440px] mx-auto px-4 lg:px-8 text-center overflow-hidden">
        <p class="text-xs font-bold uppercase tracking-[0.4em] text-brand-muted mb-3">Follow the movement</p>
        <h2 class="text-3xl sm:text-4xl font-heading font-extrabold tracking-tight mb-4">@THREADAX.CO.IN</h2>
        <p class="text-brand-muted text-sm mb-12 max-w-md mx-auto">Tag us in your fits. Follow us on Instagram for daily drops, styling inspiration, and exclusive previews.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 items-start justify-items-center">
            <div class="w-full flex justify-center">
                <blockquote class="instagram-media" data-instgrm-captioned data-instgrm-permalink="https://www.instagram.com/reel/DbtE0iGTHJJ/?utm_source=ig_embed&amp;utm_campaign=loading" data-instgrm-version="14" style=" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:540px; min-width:326px; padding:0; width:100%;"></blockquote>
            </div>
            <div class="w-full flex justify-center">
                <blockquote class="instagram-media" data-instgrm-captioned data-instgrm-permalink="https://www.instagram.com/reel/DbhlXPVRNFa/?utm_source=ig_embed&amp;utm_campaign=loading" data-instgrm-version="14" style=" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:540px; min-width:326px; padding:0; width:100%;"></blockquote>
            </div>
            <div class="w-full flex justify-center">
                <blockquote class="instagram-media" data-instgrm-captioned data-instgrm-permalink="https://www.instagram.com/reel/DbTMZGBRzUt/?utm_source=ig_embed&amp;utm_campaign=loading" data-instgrm-version="14" style=" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:540px; min-width:326px; padding:0; width:100%;"></blockquote>
            </div>
            <div class="w-full flex justify-center">
                <blockquote class="instagram-media" data-instgrm-captioned data-instgrm-permalink="https://www.instagram.com/reel/DbN7nP1xDUv/?utm_source=ig_embed&amp;utm_campaign=loading" data-instgrm-version="14" style=" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:540px; min-width:326px; padding:0; width:100%;"></blockquote>
            </div>
            <script async src="//www.instagram.com/embed.js"></script>
        </div>

        <div class="mt-12">
            <a href="https://www.instagram.com/threadax.co.in/" target="_blank" class="inline-flex items-center gap-3 bg-gradient-to-r from-purple-600 via-pink-500 to-orange-400 text-white text-sm font-bold uppercase tracking-widest px-10 py-4 rounded-full hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                Follow on Instagram
            </a>
        </div>
    </div>
</section>


{{-- ═══════════ OUR PROMISE (LIGHT & WATERMARK) ═══════════ --}}
<section class="py-24 relative overflow-hidden bg-brand-off-white border-t border-brand-border">
    <!-- ThreadAX Watermark -->
    <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-[0.03] overflow-hidden select-none">
        <h1 class="text-[25vw] font-heading font-extrabold whitespace-nowrap -rotate-12">THREADAX</h1>
    </div>

    <!-- Decorative Blur Blobs -->
    <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-brand-text/10 rounded-full blur-[120px] -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-0 w-[600px] h-[600px] bg-blue-500/5 rounded-full blur-[150px] translate-x-1/3 translate-y-1/3"></div>

    <div class="max-w-[1440px] mx-auto px-4 lg:px-8 relative z-10">
        <div class="text-center mb-16">
            <p class="text-xs font-bold uppercase tracking-[0.4em] text-brand-text mb-3">Our Promise</p>
            <h2 class="text-4xl sm:text-5xl font-heading font-extrabold text-brand-dark">THE THREADAX STANDARD</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Card 1 --}}
            <div class="group relative bg-white hover:bg-gray-50 border border-brand-border p-8 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-500 overflow-hidden text-center hover:-translate-y-2">
                <div class="absolute inset-0 bg-gradient-to-br from-brand-light/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-brand-light flex items-center justify-center border border-brand-border group-hover:scale-110 group-hover:rotate-3 transition-transform duration-500 text-brand-text">
                    <svg class="w-8 h-8 drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="font-heading font-bold text-lg text-brand-dark mb-3 tracking-wide uppercase">Premium Quality</h3>
                <p class="text-brand-muted text-sm leading-relaxed relative z-10">280GSM+ heavyweight cotton. Every stitch meticulously crafted for longevity and unmatched comfort.</p>
            </div>
            
            {{-- Card 2 --}}
            <div class="group relative bg-white hover:bg-gray-50 border border-brand-border p-8 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-500 overflow-hidden text-center hover:-translate-y-2 delay-100">
                <div class="absolute inset-0 bg-gradient-to-br from-brand-light/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-brand-light flex items-center justify-center border border-brand-border group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-500 text-brand-text">
                    <svg class="w-8 h-8 drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                </div>
                <h3 class="font-heading font-bold text-lg text-brand-dark mb-3 tracking-wide uppercase">Fast & Free Shipping</h3>
                <p class="text-brand-muted text-sm leading-relaxed relative z-10">Free pan-India delivery on all orders above ₹999. Fast, tracked, and extremely reliable.</p>
            </div>

            {{-- Card 3 --}}
            <div class="group relative bg-white hover:bg-gray-50 border border-brand-border p-8 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-500 overflow-hidden text-center hover:-translate-y-2 delay-200">
                <div class="absolute inset-0 bg-gradient-to-br from-brand-light/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-brand-light flex items-center justify-center border border-brand-border group-hover:scale-110 group-hover:rotate-3 transition-transform duration-500 text-brand-text">
                    <svg class="w-8 h-8 drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </div>
                <h3 class="font-heading font-bold text-lg text-brand-dark mb-3 tracking-wide uppercase">7-Day Returns</h3>
                <p class="text-brand-muted text-sm leading-relaxed relative z-10">Don't love the fit? Return it. We offer easy 7-day hassle-free returns with no questions asked.</p>
            </div>

            {{-- Card 4 --}}
            <div class="group relative bg-white hover:bg-gray-50 border border-brand-border p-8 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-500 overflow-hidden text-center hover:-translate-y-2 delay-300">
                <div class="absolute inset-0 bg-gradient-to-br from-brand-light/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-brand-light flex items-center justify-center border border-brand-border group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-500 text-brand-text">
                    <svg class="w-8 h-8 drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <h3 class="font-heading font-bold text-lg text-brand-dark mb-3 tracking-wide uppercase">Secure Checkout</h3>
                <p class="text-brand-muted text-sm leading-relaxed relative z-10">SSL encrypted and Razorpay powered. Your payment data and privacy is always 100% safe.</p>
            </div>
        </div>
    </div>
</section>


{{-- ═══════════ SEO TEXT + FAQ SECTION ═══════════ --}}
<section class="py-20 bg-brand-off-white">
    <div class="max-w-[1440px] mx-auto px-4 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">

            {{-- SEO Text / Brand Story --}}
            <div>
                <h2 class="text-2xl sm:text-3xl font-heading font-extrabold mb-6">Premium Streetwear Brand in India — ThreadAX</h2>
                <div class="space-y-4 text-brand-muted text-sm leading-relaxed">
                    <p>ThreadAX is India's fastest-growing premium streetwear brand, crafting bold, oversized apparel for those who refuse to blend in. Born from a love of street culture, we design every piece to be a statement — whether it's your daily hoodie or a statement tee.</p>
                    <p>Our clothing is made from <strong class="text-brand-text">280GSM+ heavyweight cotton</strong>, ensuring durability, comfort, and a premium feel that lasts wash after wash. Every drop is carefully designed in-house, bringing together authentic streetwear aesthetics with premium Indian craftsmanship.</p>
                    <p>Shop our curated collections including <a href="{{ route('frontend.products.index') }}" class="underline hover:text-brand-text transition-colors">Men's Streetwear</a>, <a href="{{ route('frontend.products.index') }}" class="underline hover:text-brand-text transition-colors">Women's Collections</a>, and our signature <a href="{{ route('frontend.products.index') }}" class="underline hover:text-brand-text transition-colors">Oversized Essentials</a> — all available with free shipping across India.</p>
                </div>
            </div>

            {{-- FAQ --}}
            <div>
                <h2 class="text-2xl sm:text-3xl font-heading font-extrabold mb-6">Frequently Asked Questions</h2>
                <div class="space-y-3" x-data="{ openFaq: null }">
                    @forelse($faqs ?? collect() as $faq)
                    <div class="bg-white border border-brand-border rounded-[16px] overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                        <button @click="openFaq = openFaq === {{ $faq->id }} ? null : {{ $faq->id }}" class="w-full flex items-center justify-between px-6 py-5 text-left text-sm font-bold transition-colors">
                            <span class="pr-4">{{ $faq->question }}</span>
                            <svg :class="openFaq === {{ $faq->id }} ? 'rotate-180 text-brand-text' : 'text-brand-muted'" class="w-5 h-5 shrink-0 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="openFaq === {{ $faq->id }}" x-collapse x-transition.duration.300ms>
                            <div class="px-6 pb-5 pt-1 text-sm text-brand-muted leading-relaxed border-t border-brand-light mt-1">
                                {!! nl2br(e($faq->answer)) !!}
                            </div>
                        </div>
                    </div>
                    @empty
                        <div class="text-sm text-brand-muted italic">FAQs will be added soon.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
