<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="@yield('meta_description', 'ThreadAx — Premium Streetwear. Oversized fits, premium fabrics, clean designs.')">
    <title>@yield('title', 'ThreadAx — Premium Streetwear')</title>
    <link rel="canonical" href="@yield('canonical_url', url()->current())">

    {{-- Google Search Console Verification --}}
    @php $googleVerification = config('services.analytics.google_site_verification', env('GOOGLE_SITE_VERIFICATION')); @endphp
    @if($googleVerification)
        <meta name="google-site-verification" content="{{ $googleVerification }}">
    @endif

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'ThreadAx — Premium Streetwear')">
    <meta property="og:description"
        content="@yield('meta_description', 'ThreadAx — Premium Streetwear. Oversized fits, premium fabrics, clean designs.')">
    <meta property="og:image" content="@yield('meta_image', asset('images/banner-men.png'))">

    {{-- Twitter Cards --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'ThreadAx — Premium Streetwear')">
    <meta name="twitter:description" content="@yield('meta_description', 'ThreadAx — Premium Streetwear. Oversized fits, premium fabrics, clean designs.')">
    <meta name="twitter:image" content="@yield('meta_image', asset('images/banner-men.png'))">

    {{-- JSON-LD Structured Data Schema for Google Indexing --}}
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => 'ThreadAX Streetwear',
        'url' => url('/'),
        'logo' => asset('images/logo.png'),
        'contactPoint' => [
            '@type' => 'ContactPoint',
            'telephone' => '+91-9876543210',
            'contactType' => 'customer service',
            'areaServed' => 'IN',
            'availableLanguage' => ['English', 'Hindi']
        ]
    ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => 'ThreadAX',
        'url' => url('/'),
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => url('/search') . '?q={search_term_string}',
            'query-input' => 'required name=search_term_string'
        ]
    ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Google Analytics 4 (GA4) / GTag --}}
    @php $gtagId = config('services.analytics.gtag_id'); @endphp
    @if($gtagId)
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gtagId }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag() { dataLayer.push(arguments); }
            gtag('js', new Date());
            gtag('config', '{{ $gtagId }}');
        </script>
    @endif

    {{-- Meta (Facebook) Pixel Code --}}
    @php $pixelId = config('services.analytics.pixel_id'); @endphp
    @if($pixelId)
        <script>
            !function (f, b, e, v, n, t, s) {
                if (f.fbq) return; n = f.fbq = function () {
                    n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                };
                if (!f._fbq) f._fbq = n; n.push = n; n.loaded = !0; n.version = '2.0';
                n.queue = []; t = b.createElement(e); t.async = !0;
                t.src = v; s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s)
            }(window, document, 'script',
                'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{{ $pixelId }}');
            fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
                src="https://www.facebook.com/tr?id={{ $pixelId }}&ev=PageView&noscript=1" /></noscript>
    @endif

    @stack('head')
    @stack('styles')
</head>

<body class="antialiased min-h-screen flex flex-col" x-data="globalApp">

    {{-- ═══════════ IMPERSONATION MODE BANNER ═══════════ --}}
    @if(session()->has('admin_impersonator_id'))
        <div class="w-full bg-gradient-to-r from-amber-500 via-amber-400 to-amber-500 text-slate-950 px-4 py-2.5 text-xs font-black flex items-center justify-between sticky top-0 z-[100] shadow-md border-b border-amber-600">
            <div class="flex items-center gap-2">
                <span class="text-base animate-pulse">⚠️</span>
                <span>
                    <strong>IMPERSONATION MODE:</strong> You are logged in as customer <strong class="underline">{{ session('impersonated_user_name') }}</strong> ({{ session('impersonated_user_email') }}).
                </span>
            </div>
            <a href="{{ route('impersonate.leave') }}" class="px-3.5 py-1 bg-slate-950 hover:bg-slate-900 text-white font-extrabold rounded-lg shadow-sm hover:shadow transition-all text-xs flex items-center gap-1.5 cursor-pointer">
                <span>Exit to Admin Panel</span>
                <span>➔</span>
            </a>
        </div>
    @endif

    {{-- ═══════════ TOP STRIP — Animated Marquee ═══════════ --}}
    <div class="w-full bg-brand-text text-white text-[11px] font-semibold tracking-wider overflow-hidden"
        style="height:36px">
        <div class="marquee-track flex items-center h-full"
            style="animation: marquee 30s linear infinite; white-space:nowrap; will-change:transform;">
            @php
                $stripItems = [
                    '📦 Free Shipping on orders above ₹999',
                    '🔄 Easy 7-Day No-Questions-Asked Returns',
                    '🏷️ Authentic Premium Streetwear',
                    '✨ 100% Heavy Cotton Fabrics',
                    '🚚 Fast Pan-India Delivery',
                    '🔒 Secure Payments via Razorpay',
                ];
            @endphp
            @foreach(array_merge($stripItems, $stripItems) as $item)
                <span class="px-10">{{ $item }}</span>
                <span class="opacity-40">•</span>
            @endforeach
        </div>
    </div>
    <style>
        @keyframes marquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .marquee-track:hover {
            animation-play-state: paused;
        }
    </style>

    {{-- ═══════════ MAIN HEADER ═══════════ --}}
    <header class="w-full bg-white sticky top-0 z-50 border-b border-brand-border shadow-sm">
        <div class="max-w-[1440px] mx-auto px-4 lg:px-8 flex items-center justify-between h-14 sm:h-16 relative">

            {{-- Mobile Hamburger (Left on mobile, hidden on desktop) --}}
            <button @click="mobileMenu = !mobileMenu" class="lg:hidden p-2 -ml-2 text-brand-text">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path x-show="!mobileMenu" stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    <path x-show="mobileMenu" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            {{-- Logo (Centered on mobile, Left on desktop) --}}
            <a href="{{ route('frontend.home') }}"
                class="absolute left-1/2 -translate-x-1/2 lg:static lg:translate-x-0 flex items-center gap-0 font-heading text-xl sm:text-2xl font-extrabold tracking-tight shrink-0">
                <span class="text-brand-text">THREAD</span><span class="threadax-logo-box ml-0.5 text-white">AX</span>
            </a>

            {{-- Nav Links — Desktop (Dynamic from DB) --}}
            <nav
                class="hidden lg:flex items-center gap-8 text-sm font-semibold uppercase tracking-wide text-brand-text ml-8">
                @if(isset($headerCategories) && $headerCategories->count() > 0)
                    @foreach($headerCategories as $cat)
                        <a href="{{ route('frontend.products.index', ['category' => $cat->id]) }}"
                            class="relative py-5 hover:text-brand-muted transition-colors group">
                            {{ $cat->name }}
                            <span
                                class="absolute bottom-0 left-0 w-0 h-0.5 bg-brand-text group-hover:w-full transition-all duration-300"></span>
                        </a>
                    @endforeach
                @else
                    {{-- Fallback if no header categories set yet --}}
                    <a href="{{ route('frontend.products.index') }}"
                        class="relative py-5 hover:text-brand-muted transition-colors group">
                        Shop All
                        <span
                            class="absolute bottom-0 left-0 w-0 h-0.5 bg-brand-text group-hover:w-full transition-all duration-300"></span>
                    </a>
                @endif
            </nav>

            {{-- Right Side — Icons --}}
            <div class="flex items-center gap-2 sm:gap-4 ml-auto lg:ml-0">

                {{-- Search Toggle (Mobile & Desktop) --}}
                <button @click="searchOpen = !searchOpen"
                    class="p-2 text-brand-text hover:text-brand-muted transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>

                {{-- Notifications (Auth Only) --}}
                @auth
                <div class="relative" x-data="{ open: false }" @click.outside="open = false" @mouseenter="if(window.innerWidth >= 1024) open = true" @mouseleave="if(window.innerWidth >= 1024) open = false">
                    <button type="button" @click.stop="open = !open" class="p-2 text-brand-text hover:text-brand-muted transition-colors flex items-center relative cursor-pointer" aria-label="Notifications">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="absolute top-1.5 right-1.5 flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                            </span>
                        @endif
                    </button>
                    
                    {{-- Dropdown (Clean, Minimal, Responsive) --}}
                    <div x-show="open" x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                        class="absolute right-0 top-full mt-2 w-[280px] sm:w-80 max-w-[calc(100vw-1.5rem)] bg-white rounded-xl shadow-lg border border-brand-border overflow-hidden z-50"
                        style="display:none;">
                        
                        <div class="px-4 py-2.5 border-b border-brand-border flex justify-between items-center bg-brand-off-white">
                            <h3 class="text-[11px] font-bold uppercase tracking-wider text-brand-dark">Notifications</h3>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <form action="{{ route('account.notifications.markAllRead') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-[10px] font-semibold text-brand-muted hover:text-brand-dark hover:underline uppercase">Mark all read</button>
                                </form>
                            @endif
                        </div>
                        
                        <div class="max-h-72 overflow-y-auto divide-y divide-brand-border/40">
                            @forelse(auth()->user()->notifications->take(5) as $notification)
                                <a href="{{ $notification->data['link'] ?? route('account.notifications.index') }}" class="block p-3 sm:p-3.5 hover:bg-brand-off-white transition-colors {{ is_null($notification->read_at) ? 'bg-blue-50/40' : '' }}">
                                    <div class="flex items-start gap-2.5">
                                        <div class="shrink-0 mt-0.5">
                                            @if(($notification->data['type'] ?? '') === 'order_status')
                                                <span class="w-7 h-7 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center border border-blue-100 text-xs font-bold">
                                                    {{ $notification->data['icon'] ?? '🛍️' }}
                                                </span>
                                            @elseif(($notification->data['type'] ?? '') === 'offer')
                                                <span class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-100">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 109.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1114.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                                                </span>
                                            @else
                                                <span class="w-7 h-7 rounded-lg bg-brand-light text-brand-dark flex items-center justify-center border border-brand-border">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" /></svg>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-brand-dark mb-0.5 truncate {{ is_null($notification->read_at) ? '' : 'text-brand-muted font-medium' }}">{{ $notification->data['title'] ?? 'Notification' }}</p>
                                            <p class="text-[11px] text-brand-muted line-clamp-2 leading-relaxed">{{ $notification->data['message'] ?? '' }}</p>
                                            <p class="text-[10px] text-brand-muted/70 mt-1 uppercase font-medium">{{ $notification->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="py-7 px-4 text-center">
                                    <div class="w-8 h-8 rounded-full bg-brand-light flex items-center justify-center text-brand-muted/60 mx-auto mb-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" /></svg>
                                    </div>
                                    <p class="text-xs font-medium text-brand-muted">No notifications yet</p>
                                </div>
                            @endforelse
                        </div>
                        
                        <a href="{{ route('account.notifications.index') }}" class="block px-4 py-2.5 bg-brand-off-white text-center text-[11px] font-bold uppercase tracking-wider text-brand-dark hover:bg-brand-light transition-colors border-t border-brand-border">
                            View All Notifications
                        </a>
                    </div>
                </div>
                @endauth

                {{-- Account (Desktop Only) --}}
                <div class="hidden lg:block relative group" x-data="{ open: false }" @mouseenter="open = true"
                    @mouseleave="open = false">
                    @auth
                        <a href="{{ route('account.dashboard') }}"
                            class="p-2 text-brand-text hover:text-brand-muted transition-colors flex items-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                        </a>
                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                            class="absolute right-0 top-full mt-2 w-64 bg-white rounded-2xl shadow-2xl border border-brand-border/60 overflow-hidden z-50"
                            style="display:none;">

                            {{-- User Info Header --}}
                            <div class="px-4 py-4 bg-brand-light border-b border-brand-border">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-brand-text flex items-center justify-center shrink-0">
                                        <span
                                            class="text-white text-sm font-bold font-heading">{{ strtoupper(substr(auth()->user()->name ?? auth()->user()->email, 0, 1)) }}</span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-brand-text truncate">
                                            {{ auth()->user()->name ?? 'Guest' }}
                                        </p>
                                        <p class="text-xs text-brand-muted truncate">{{ auth()->user()->email }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Menu Items --}}
                            <div class="py-2">
                                <a href="{{ route('account.dashboard') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-brand-text hover:bg-brand-light transition-colors group">
                                    <svg class="w-4 h-4 text-brand-muted group-hover:text-brand-text transition-colors"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>My Account</span>
                                </a>
                                <a href="{{ route('account.orders') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-brand-text hover:bg-brand-light transition-colors group">
                                    <svg class="w-4 h-4 text-brand-muted group-hover:text-brand-text transition-colors"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                                    </svg>
                                    <span>My Orders</span>
                                </a>
                                <a href="{{ route('account.wishlist') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-brand-text hover:bg-brand-light transition-colors group">
                                    <svg class="w-4 h-4 text-brand-muted group-hover:text-brand-text transition-colors"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                    </svg>
                                    <span>Wishlist</span>
                                </a>
                                <a href="{{ route('account.profile') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-brand-text hover:bg-brand-light transition-colors group">
                                    <svg class="w-4 h-4 text-brand-muted group-hover:text-brand-text transition-colors"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>Profile</span>
                                </a>
                            </div>

                            {{-- Logout --}}
                            <div class="border-t border-brand-border/60 py-2">
                                <form method="POST" action="{{ route('auth.logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition-colors group">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                        </svg>
                                        <span>Sign Out</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('auth.login') }}"
                            class="p-2 text-brand-text hover:text-brand-muted transition-colors flex items-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                        </a>
                    @endauth
                </div>

                {{-- Wishlist (Desktop Only) --}}
                <a href="{{ route('account.wishlist') }}"
                    class="hidden lg:block p-2 text-brand-text hover:text-brand-muted transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                    </svg>
                </a>

                {{-- Bag (Desktop) --}}
                <button @click.prevent="cartOpen = true"
                    class="p-2 -mr-2 sm:mr-0 text-brand-text hover:text-brand-muted transition-colors relative hidden lg:block">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z" />
                    </svg>
                    <span x-show="cartSummary.item_count > 0" x-text="cartSummary.item_count"
                        class="absolute top-0 right-0 w-4 h-4 bg-brand-text text-white text-[10px] font-bold rounded-full flex items-center justify-center"></span>
                </button>
            </div>
        </div>

        {{-- Expanded Search Bar --}}
        <div x-show="searchOpen" x-transition class="w-full bg-white border-t border-brand-border px-4 py-3">
            <form action="{{ route('frontend.products.index') }}" method="GET"
                class="max-w-[1440px] mx-auto flex items-center bg-brand-light rounded-md px-4 py-2">
                <svg class="w-4 h-4 text-brand-muted mr-3 shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search for products..."
                    class="bg-transparent text-sm w-full focus:outline-none text-brand-text placeholder:text-brand-muted">
                <button type="button" @click="searchOpen = false" class="ml-2 text-brand-muted hover:text-brand-text">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </form>
        </div>

        {{-- Mobile Menu (Dynamic from DB) --}}
        <div x-show="mobileMenu" x-transition
            class="lg:hidden w-full bg-white border-t border-brand-border absolute top-full left-0 shadow-lg">
            <nav class="flex flex-col text-sm font-semibold uppercase tracking-wide">
                @if(isset($headerCategories) && $headerCategories->count() > 0)
                    @foreach($headerCategories as $cat)
                        <a href="{{ route('frontend.products.index', ['category' => $cat->id]) }}"
                            class="px-6 py-4 hover:bg-brand-light border-b border-brand-border transition-colors">{{ $cat->name }}</a>
                    @endforeach
                @else
                    <a href="{{ route('frontend.products.index') }}"
                        class="px-6 py-4 hover:bg-brand-light border-b border-brand-border transition-colors">Shop All</a>
                @endif
                <div class="p-6 bg-brand-off-white flex items-center gap-4">
                    @auth
                        <a href="{{ route('account.dashboard') }}"
                            class="flex-1 text-center py-2 bg-brand-text text-white rounded">My Account</a>
                        <form method="POST" action="{{ route('auth.logout') }}" class="flex-1">
                            @csrf
                            <button type="submit"
                                class="w-full text-center py-2 border border-brand-text text-brand-text rounded">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('auth.login') }}"
                            class="w-full text-center py-2 bg-brand-text text-white rounded">Login / Sign Up</a>
                    @endauth
                </div>
            </nav>
        </div>
    </header>

    {{-- ═══════════ MAIN CONTENT ═══════════ --}}
    <main class="flex-grow pb-24 lg:pb-0">
        @yield('content')
    </main>

    {{-- ═══════════ FOOTER ═══════════ --}}
    <footer class="bg-brand-off-white border-t border-brand-border mt-auto pb-24 lg:pb-0">
        <div class="max-w-[1440px] mx-auto px-4 lg:px-8 py-10 lg:py-12">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-y-10 gap-x-6">
                {{-- Company --}}
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest text-brand-text mb-4">Company</h4>
                    <ul class="space-y-2 text-sm text-brand-muted">
                        <li><a href="{{ route('frontend.page.show', 'about-us') }}"
                                class="hover:text-brand-text transition-colors">About Us</a></li>
                        <li><a href="{{ route('frontend.page.show', 'contact') }}"
                                class="hover:text-brand-text transition-colors">Contact</a></li>
                        <li><a href="{{ route('frontend.blog.index') }}"
                                class="hover:text-brand-text transition-colors">Blog</a></li>
                    </ul>
                </div>

                {{-- Help --}}
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest text-brand-text mb-4">Help</h4>
                    <ul class="space-y-2 text-sm text-brand-muted">
                        <li><a href="{{ route('frontend.page.show', 'track-order') }}"
                                class="hover:text-brand-text transition-colors">Track Order</a></li>
                        <li><a href="{{ route('frontend.page.show', 'returns-exchanges') }}"
                                class="hover:text-brand-text transition-colors">Returns & Exchanges</a></li>
                        <li><a href="{{ route('frontend.page.show', 'shipping-info') }}"
                                class="hover:text-brand-text transition-colors">Shipping Info</a></li>
                        <li><a href="{{ route('frontend.page.show', 'faq') }}"
                                class="hover:text-brand-text transition-colors">FAQ</a></li>
                    </ul>
                </div>

                {{-- Shop (Dynamic from DB) --}}
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest text-brand-text mb-4">Shop</h4>
                    <ul class="space-y-2 text-sm text-brand-muted">
                        @if(isset($headerCategories) && $headerCategories->count() > 0)
                            @foreach($headerCategories->take(5) as $cat)
                                <li><a href="{{ route('frontend.products.index', ['category' => $cat->id]) }}"
                                        class="hover:text-brand-text transition-colors">{{ $cat->name }}</a></li>
                            @endforeach
                        @endif
                        <li><a href="{{ route('frontend.products.index') }}"
                                class="hover:text-brand-text transition-colors font-semibold">Shop All →</a></li>
                    </ul>
                </div>

                {{-- Connect & Newsletter --}}
                <div class="col-span-2 lg:col-span-1">
                    <h4 class="text-xs font-bold uppercase tracking-widest text-brand-text mb-4">Connect</h4>
                    <div class="flex items-center gap-3 mb-6">
                        {{-- Instagram --}}
                        <a href="https://www.instagram.com/threadax.co.in/" target="_blank"
                            class="w-10 h-10 rounded-full bg-brand-light border border-brand-border flex items-center justify-center text-brand-muted hover:bg-brand-text hover:text-white hover:border-brand-text transition-all"
                            aria-label="Instagram">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                            </svg>
                        </a>
                        {{-- Twitter / X --}}
                        <a href="#"
                            class="w-10 h-10 rounded-full bg-brand-light border border-brand-border flex items-center justify-center text-brand-muted hover:bg-brand-text hover:text-white hover:border-brand-text transition-all"
                            aria-label="Twitter">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                            </svg>
                        </a>
                        {{-- Facebook --}}
                        <a href="#"
                            class="w-10 h-10 rounded-full bg-brand-light border border-brand-border flex items-center justify-center text-brand-muted hover:bg-[#1877F2] hover:text-white hover:border-[#1877F2] transition-all"
                            aria-label="Facebook">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                        {{-- YouTube --}}
                        <a href="#"
                            class="w-10 h-10 rounded-full bg-brand-light border border-brand-border flex items-center justify-center text-brand-muted hover:bg-[#FF0000] hover:text-white hover:border-[#FF0000] transition-all"
                            aria-label="YouTube">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                            </svg>
                        </a>
                    </div>
                    <div x-data="newsletterForm()">
                        <p class="text-xs font-bold uppercase tracking-widest text-brand-text mb-3">Newsletter</p>
                        <form @submit.prevent="submit" class="flex gap-2 relative">
                            <input type="email" x-model="email" placeholder="your@email.com" required
                                class="input-field text-xs !py-2 flex-1 w-full max-w-[250px]" :disabled="isLoading">
                            <button type="submit"
                                class="bg-brand-text text-white text-xs font-semibold px-4 py-2 rounded-md hover:bg-brand-dark/80 transition-colors shrink-0"
                                :disabled="isLoading">
                                <span x-show="!isLoading">Join</span>
                                <span x-show="isLoading" class="animate-pulse">...</span>
                            </button>
                        </form>
                        <p x-show="message" x-text="message" x-transition
                            :class="isSuccess ? 'text-green-600' : 'text-red-500'" class="text-xs mt-2 font-medium"></p>
                    </div>
                </div>
            </div>


            {{-- Bottom Bar --}}
            <div
                class="mt-10 pt-6 border-t border-brand-border flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-brand-muted">
                <div class="flex items-center gap-1">
                    <span class="font-heading font-extrabold text-sm text-brand-text">THREAD</span><span
                        class="threadax-logo-box text-[10px] text-white">AX</span>
                    <span class="ml-2">&copy; {{ date('Y') }} All rights reserved.</span>
                </div>
                <div class="flex gap-4 flex-wrap justify-center">
                    <a href="{{ route('frontend.page.show', 'privacy-policy') }}"
                        class="hover:text-brand-text transition-colors">Privacy Policy</a>
                    <a href="{{ route('frontend.page.show', 'terms-of-service') }}"
                        class="hover:text-brand-text transition-colors">Terms of Service</a>
                    <a href="{{ route('frontend.page.show', 'contact') }}"
                        class="hover:text-brand-text transition-colors">Contact</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- WhatsApp Floating Button --}}
    @php
        $whatsappNumber = $globalSettings['whatsapp_number'] ?? env('WHATSAPP_NUMBER');
    @endphp
    @if($whatsappNumber)
        <a href="https://wa.me/{{ $whatsappNumber }}?text={{ urlencode('Hello ThreadAX! I have a query.') }}"
            target="_blank" rel="noopener noreferrer"
            class="fixed bottom-6 right-6 z-50 bg-[#25D366] text-white p-3 sm:p-4 rounded-full shadow-lg hover:scale-110 hover:shadow-xl transition-all duration-300 flex items-center justify-center"
            aria-label="Chat on WhatsApp">
            <svg class="w-7 h-7 sm:w-8 sm:h-8" fill="currentColor" viewBox="0 0 24 24">
                <path
                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
            </svg>
        </a>
    @endif


    {{-- ═══════════ MOBILE BOTTOM NAVIGATION (5 ICONS) ═══════════ --}}
    <div
        class="lg:hidden fixed bottom-0 left-0 right-0 bg-white rounded-t-2xl border-t border-brand-border z-40 flex justify-between items-end px-2 pt-2 pb-3 text-[10px] font-bold text-brand-muted shadow-[0_-5px_20px_rgba(0,0,0,0.08)]">

        {{-- 1. Home --}}
        <a href="{{ route('frontend.home') }}"
            class="flex flex-col items-center gap-1 p-2 flex-1 {{ request()->routeIs('frontend.home') ? 'text-brand-text' : 'hover:text-brand-text' }}">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <span>Home</span>
        </a>

        {{-- 2. Explore --}}
        <a href="{{ route('frontend.products.index') }}"
            class="flex flex-col items-center gap-1 p-2 flex-1 hover:text-brand-text">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
            </svg>
            <span>Explore</span>
        </a>

        {{-- 3. Bag (Center Floating Highlight) --}}
        <button @click.prevent="cartOpen = true" class="flex flex-col items-center p-0 flex-1 relative -mt-6 pb-1">
            <div
                class="w-14 h-14 bg-brand-text text-white rounded-full flex items-center justify-center shadow-lg border-[4px] border-white relative transition-transform hover:scale-105">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z" />
                </svg>
                <span x-show="cartSummary.item_count > 0" x-text="cartSummary.item_count"
                    class="absolute top-0 right-0 w-4 h-4 bg-red-500 rounded-full border-2 border-brand-text text-[10px] font-bold flex items-center justify-center"></span>
            </div>
            <span class="mt-1">Bag</span>
        </button>

        {{-- 4. Wishlist --}}
        <a href="{{ route('account.wishlist') }}"
            class="flex flex-col items-center gap-1 p-2 flex-1 {{ request()->routeIs('account.wishlist') ? 'text-brand-text' : 'hover:text-brand-text' }}">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
            </svg>
            <span>Wishlist</span>
        </a>

        {{-- 5. Profile --}}
        <a href="{{ route('account.dashboard') }}"
            class="flex flex-col items-center gap-1 p-2 flex-1 {{ request()->is('account*') ? 'text-brand-text' : 'hover:text-brand-text' }}">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
            </svg>
            <span>Profile</span>
        </a>
    </div>

    {{-- ═══════════ CART DRAWER ═══════════ --}}
    <div x-show="cartOpen" class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title" role="dialog"
        aria-modal="true" style="display: none;">
        <div class="absolute inset-0 overflow-hidden">
            <div x-show="cartOpen" x-transition.opacity class="absolute inset-0 bg-black/50 transition-opacity"
                @click="cartOpen = false"></div>

            <div class="fixed inset-y-0 right-0 max-w-full flex">
                <div x-show="cartOpen"
                    x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                    x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                    x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                    x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                    class="w-screen max-w-md">

                    <div class="h-full flex flex-col bg-white shadow-2xl">

                        {{-- Header --}}
                        <div class="px-5 py-4 sm:px-6 sm:py-5 border-b border-brand-border flex items-center justify-between bg-brand-off-white">
                            <div class="flex items-center gap-2.5">
                                <span class="w-7 h-7 rounded-lg bg-brand-dark text-white flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z" />
                                    </svg>
                                </span>
                                <h2 class="text-sm sm:text-base font-heading font-bold text-brand-dark" id="slide-over-title">
                                    Your Bag (<span x-text="cartSummary.item_count"></span>)
                                </h2>
                            </div>
                            <button @click="cartOpen = false" class="w-8 h-8 rounded-full flex items-center justify-center text-brand-muted hover:text-brand-dark hover:bg-brand-light transition-all cursor-pointer" aria-label="Close cart">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        {{-- Free Delivery Dynamic Progress Bar --}}
                        <div class="px-5 py-3.5 bg-brand-light/70 border-b border-brand-border" x-show="cartItems.length > 0">
                            <div class="flex items-center justify-between text-xs mb-2">
                                <span class="font-semibold text-brand-dark flex items-center gap-1.5" x-show="cartSummary.subtotal < 999">
                                    <span>🚚</span>
                                    <span>Add <span class="font-extrabold text-brand-dark" x-text="formatPrice(999 - cartSummary.subtotal)"></span> more to get <strong class="text-brand-dark underline underline-offset-2">FREE Delivery</strong></span>
                                </span>
                                <span class="font-bold text-emerald-700 flex items-center gap-1.5" x-show="cartSummary.subtotal >= 999">
                                    <span>🎉</span>
                                    <span>You unlocked <strong>FREE Standard Delivery!</strong></span>
                                </span>
                                <span class="text-[10px] font-extrabold px-1.5 py-0.5 rounded bg-white border border-brand-border text-brand-dark"
                                      x-text="Math.min(100, Math.round((cartSummary.subtotal / 999) * 100)) + '%'"></span>
                            </div>
                            <div class="w-full h-2 bg-brand-border/60 rounded-full overflow-hidden p-0.5">
                                <div class="h-full rounded-full transition-all duration-500 ease-out shadow-xs"
                                     :class="cartSummary.subtotal >= 999 ? 'bg-emerald-500' : 'bg-brand-dark'"
                                     :style="'width: ' + Math.min(100, Math.round((cartSummary.subtotal / 999) * 100)) + '%'"></div>
                            </div>
                        </div>

                        {{-- Items Area --}}
                        <div class="flex-1 overflow-y-auto px-4 py-4 sm:px-6 sm:py-5">

                            {{-- Empty State --}}
                            <template x-if="cartItems.length === 0">
                                <div class="h-full min-h-[320px] flex flex-col items-center justify-center text-center px-4">
                                    <div class="w-16 h-16 rounded-2xl bg-brand-light flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-brand-muted/70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-base font-heading font-bold text-brand-dark mb-1">Your bag is empty</h3>
                                    <p class="text-xs text-brand-muted max-w-xs mb-6">Looks like you haven't added any streetwear items to your cart yet.</p>
                                    <a href="{{ route('frontend.products.index') }}" @click="cartOpen = false" class="inline-flex items-center gap-2 bg-brand-dark text-white text-xs font-bold px-6 py-3 rounded-xl hover:bg-brand-text transition-colors">
                                        Explore Collections
                                    </a>
                                </div>
                            </template>

                            {{-- Item List --}}
                            <div class="flow-root" x-show="cartItems.length > 0">
                                <ul role="list" class="divide-y divide-brand-border/60">
                                    <template x-for="item in cartItems" :key="item.id">
                                        <li class="py-4 sm:py-5 flex gap-3 sm:gap-4 first:pt-0">
                                            {{-- Product Image --}}
                                            <div class="w-20 h-24 sm:w-22 sm:h-28 border border-brand-border bg-brand-off-white overflow-hidden rounded-xl shrink-0 shadow-2xs relative">
                                                <img :src="item.image || 'https://via.placeholder.com/150'" :alt="item.product_name" class="w-full h-full object-center object-cover">
                                            </div>

                                            {{-- Product Info --}}
                                            <div class="flex-1 min-w-0 flex flex-col justify-between">
                                                <div>
                                                    <div class="flex justify-between items-start gap-2">
                                                        <h3 class="text-xs sm:text-sm font-bold text-brand-dark line-clamp-2 leading-snug">
                                                            <a :href="'/product/' + item.product_slug" class="hover:underline" x-text="item.product_name"></a>
                                                        </h3>
                                                        <div class="text-right shrink-0">
                                                            <p class="text-xs sm:text-sm font-extrabold text-brand-dark" x-text="formatPrice(item.price * item.quantity)"></p>
                                                            <p x-show="item.quantity > 1" class="text-[10px] text-brand-muted mt-0.5" x-text="'(' + formatPrice(item.price) + ' ea)'"></p>
                                                        </div>
                                                    </div>

                                                    {{-- Variant Attributes --}}
                                                    <div class="flex flex-wrap items-center gap-1.5 mt-1.5">
                                                        <template x-if="item.size">
                                                            <span class="inline-flex items-center gap-1 bg-brand-off-white border border-brand-border px-2 py-0.5 rounded-md text-[10px] font-semibold text-brand-dark">
                                                                Size: <strong x-text="item.size"></strong>
                                                            </span>
                                                        </template>
                                                        <template x-if="item.color">
                                                            <span class="inline-flex items-center gap-1 bg-brand-off-white border border-brand-border px-2 py-0.5 rounded-md text-[10px] font-semibold text-brand-dark">
                                                                Color: <strong x-text="item.color"></strong>
                                                            </span>
                                                        </template>
                                                    </div>
                                                </div>

                                                {{-- Bottom Row: Quantity & Remove --}}
                                                <div class="flex items-center justify-between pt-2 mt-1">
                                                    {{-- Quantity Control --}}
                                                    <div class="flex items-center bg-brand-off-white border border-brand-border rounded-lg h-7 sm:h-8 shadow-2xs">
                                                        <button type="button" @click="updateCartItem(item.id, item.quantity - 1)" :disabled="isUpdatingCart" class="w-6 sm:w-7 h-full flex items-center justify-center text-brand-dark hover:bg-white active:scale-95 disabled:opacity-40 transition-all font-bold text-sm cursor-pointer" aria-label="Decrease quantity">&minus;</button>
                                                        <span class="w-6 sm:w-7 text-center font-bold text-xs text-brand-dark select-none" x-text="item.quantity"></span>
                                                        <button type="button" @click="updateCartItem(item.id, item.quantity + 1)" :disabled="isUpdatingCart" class="w-6 sm:w-7 h-full flex items-center justify-center text-brand-dark hover:bg-white active:scale-95 disabled:opacity-40 transition-all font-bold text-sm cursor-pointer" aria-label="Increase quantity">&plus;</button>
                                                    </div>

                                                    {{-- Remove Link --}}
                                                    <button type="button" @click="removeCartItem(item.id)" :disabled="isUpdatingCart" class="flex items-center gap-1 text-[11px] font-semibold text-red-500 hover:text-red-700 hover:underline disabled:opacity-50 transition-colors cursor-pointer">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                                        </svg>
                                                        Remove
                                                    </button>
                                                </div>
                                            </div>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>

                        {{-- Footer / Summary --}}
                        <div class="border-t border-brand-border px-5 py-4 sm:px-6 sm:py-5 bg-brand-off-white" x-show="cartItems.length > 0">
                            {{-- Cost Breakdown --}}
                            <div class="space-y-1.5 text-xs text-brand-muted mb-4">
                                <div class="flex justify-between">
                                    <span>Subtotal</span>
                                    <span class="font-bold text-brand-dark" x-text="formatPrice(cartSummary.subtotal)"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span>Estimated Shipping</span>
                                    <span :class="cartSummary.subtotal >= 999 ? 'text-emerald-700 font-bold uppercase tracking-wider text-[11px]' : 'text-brand-dark font-medium'"
                                          x-text="cartSummary.subtotal >= 999 ? 'FREE' : 'Calculated at checkout'"></span>
                                </div>
                                <div class="flex justify-between items-center pt-2 border-t border-brand-border text-sm">
                                    <span class="font-bold text-brand-dark uppercase tracking-wider">Total</span>
                                    <span class="font-extrabold font-heading text-base text-brand-dark" x-text="formatPrice(cartSummary.subtotal)"></span>
                                </div>
                            </div>

                            {{-- Checkout CTA --}}
                            <div class="space-y-2">
                                <a href="{{ route('frontend.checkout.index') }}" class="w-full flex items-center justify-center gap-2 py-3.5 px-6 bg-brand-dark text-white rounded-xl font-bold text-xs sm:text-sm uppercase tracking-wider hover:bg-brand-text transition-all shadow-sm hover:shadow-md active:scale-[0.99] cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                                    </svg>
                                    <span>Proceed to Checkout</span>
                                </a>
                            </div>

                            {{-- Trust Points --}}
                            <div class="flex items-center justify-between text-[10px] text-brand-muted font-medium pt-3 mt-3 border-t border-brand-border/60">
                                <span>🔒 Secure Checkout</span>
                                <span>🔄 7-Day Returns</span>
                                <span>⚡ Express Dispatch</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @stack('scripts')

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('globalApp', () => ({
                mobileMenu: false,
                searchOpen: false,
                cartOpen: false,
                cartItems: [],
                cartSummary: { item_count: 0, subtotal: 0, total: 0 },
                isUpdatingCart: false,

                init() {
                    this.fetchCart();

                    // Listen for a global event to add to cart
                    window.addEventListener('add-to-cart', (e) => {
                        this.addToCart(e.detail.variant_id, e.detail.quantity);
                    });
                },

                formatPrice(price) {
                    return '₹' + new Intl.NumberFormat('en-IN').format(price);
                },

                async fetchCart() {
                    try {
                        let res = await fetch('/cart/data', {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        let data = await res.json();
                        if (data.success) {
                            this.cartItems = data.items;
                            this.cartSummary = data.summary;
                        }
                    } catch (e) {
                        console.error("Cart fetch error:", e);
                    }
                },

                async addToCart(variantId, quantity) {
                    this.isUpdatingCart = true;
                    try {
                        let res = await fetch('/cart/add', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ variant_id: variantId, quantity: quantity })
                        });
                        let data = await res.json();
                        if (data.success) {
                            this.cartItems = data.items;
                            this.cartSummary = data.summary;
                            this.cartOpen = true; // Open drawer

                            // Trigger GA4 Add to Cart
                            if (typeof gtag === 'function') {
                                gtag('event', 'add_to_cart', {
                                    currency: 'INR',
                                    value: this.cartSummary.subtotal,
                                    items: this.cartItems.map(item => ({
                                        item_id: item.variant ? (item.variant.sku || item.variant.id) : item.id,
                                        item_name: item.product_name,
                                        price: item.price,
                                        quantity: item.quantity
                                    }))
                                });
                            }

                            // Trigger Meta Pixel Add to Cart
                            if (typeof fbq === 'function') {
                                fbq('track', 'AddToCart', {
                                    value: this.cartSummary.subtotal,
                                    currency: 'INR',
                                    content_ids: this.cartItems.map(item => item.variant_id || item.id),
                                    content_type: 'product'
                                });
                            }

                        } else {
                            if (window.showToast) window.showToast(data.message || 'Error adding to cart', 'error');
                        }
                    } catch (e) {
                        console.error(e);
                        if (window.showToast) window.showToast('Something went wrong!', 'error');
                    }
                    this.isUpdatingCart = false;
                },

                async removeCartItem(itemId) {
                    this.isUpdatingCart = true;
                    try {
                        let res = await fetch('/cart/remove', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ item_id: itemId })
                        });
                        let data = await res.json();
                        if (data.success) {
                            this.cartItems = data.items;
                            this.cartSummary = data.summary;
                        }
                    } catch (e) {
                        console.error(e);
                    }
                    this.isUpdatingCart = false;
                },

                async updateCartItem(itemId, qty) {
                    if (qty < 1) {
                        return this.removeCartItem(itemId);
                    }

                    // Instant optimistic UI update
                    const item = this.cartItems.find(i => i.id === itemId);
                    const oldQty = item ? item.quantity : 1;
                    if (item) {
                        item.quantity = qty;
                        this.cartSummary.subtotal += (qty - oldQty) * item.price;
                        this.cartSummary.item_count += (qty - oldQty);
                    }

                    this.isUpdatingCart = true;
                    try {
                        let res = await fetch('/cart/update', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ item_id: itemId, quantity: qty })
                        });
                        let data = await res.json();
                        if (data.success) {
                            this.cartItems = data.items;
                            this.cartSummary = data.summary;
                        } else {
                            if (item) item.quantity = oldQty;
                            this.fetchCart();
                            if (window.showToast) window.showToast(data.message || 'Error updating cart', 'error');
                        }
                    } catch (e) {
                        console.error(e);
                        this.fetchCart();
                    }
                    this.isUpdatingCart = false;
                }
            }));

            Alpine.data('newsletterForm', () => ({
                email: '',
                isLoading: false,
                message: '',
                isSuccess: false,

                async submit() {
                    if (!this.email) return;
                    this.isLoading = true;
                    this.message = '';

                    try {
                        let res = await fetch('{{ route('frontend.newsletter.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ email: this.email })
                        });

                        let data = await res.json();
                        if (data.success) {
                            this.message = data.message;
                            this.isSuccess = true;
                            this.email = '';
                            if (window.showToast) window.showToast(data.message, 'success');
                        } else {
                            this.message = data.message || 'Something went wrong.';
                            this.isSuccess = false;
                            if (window.showToast) window.showToast(this.message, 'error');
                        }
                    } catch (e) {
                        console.error(e);
                        this.message = 'Something went wrong. Please try again.';
                        this.isSuccess = false;
                        if (window.showToast) window.showToast(this.message, 'error');
                    }
                    this.isLoading = false;
                }
            }));
        });

        // Global Toast Dispatcher Function
        window.showToast = function(message, type = 'success') {
            window.dispatchEvent(new CustomEvent('toast', { detail: { message, type } }));
        };
    </script>
    {{-- Global Toast Notification System (Sleek Horizontal Top Banner/Pill) --}}
    <div x-data="{
            show: false,
            message: '',
            type: 'success',
            timeout: null,
            init() {
                // Check for Laravel session flashes
                @if(session('success'))
                    this.notify('{{ session('success') }}', 'success');
                @endif
                @if(session('error'))
                    this.notify('{{ session('error') }}', 'error');
                @endif
                @if(session('info'))
                    this.notify('{{ session('info') }}', 'info');
                @endif

                // Listen for custom events
                window.addEventListener('toast', (e) => {
                    this.notify(e.detail.message, e.detail.type || 'success');
                });
            },
            notify(msg, t) {
                if (!msg) return;
                this.message = msg;
                this.type = t;
                this.show = true;
                if (this.timeout) clearTimeout(this.timeout);
                this.timeout = setTimeout(() => { this.show = false; }, 4000);
            }
        }"
        x-show="show"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="-translate-y-8 opacity-0 scale-95"
        x-transition:enter-end="translate-y-0 opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="translate-y-0 opacity-100 scale-100"
        x-transition:leave-end="-translate-y-8 opacity-0 scale-95"
        class="fixed top-3 sm:top-5 left-1/2 -translate-x-1/2 z-[150] w-[calc(100%-1.5rem)] sm:w-auto max-w-md pointer-events-none"
        style="display: none;"
    >
        <div class="pointer-events-auto bg-[#14171f]/95 text-white backdrop-blur-xl border border-white/10 rounded-xl sm:rounded-full px-3.5 py-2.5 sm:px-4 sm:py-2.5 shadow-2xl flex items-center gap-2.5">
            
            {{-- Status Icon --}}
            <div class="shrink-0">
                <template x-if="type === 'success'">
                    <span class="w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center border border-emerald-500/30">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    </span>
                </template>
                <template x-if="type === 'error'">
                    <span class="w-6 h-6 rounded-full bg-rose-500/20 text-rose-400 flex items-center justify-center border border-rose-500/30">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                    </span>
                </template>
                <template x-if="type === 'info' || type === 'warning'">
                    <span class="w-6 h-6 rounded-full bg-amber-500/20 text-amber-400 flex items-center justify-center border border-amber-500/30">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                    </span>
                </template>
            </div>
            
            {{-- Message Content (Horizontal, no vertical breaking) --}}
            <div class="flex-1 min-w-0 pr-1">
                <p class="text-xs sm:text-sm font-semibold text-neutral-100 leading-snug line-clamp-2" x-text="message"></p>
            </div>
            
            {{-- Dismiss Button --}}
            <button @click="show = false" class="text-neutral-400 hover:text-white transition-colors p-1 rounded-full hover:bg-white/10 shrink-0 cursor-pointer" aria-label="Dismiss notification">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    {{-- Firebase Push Notification Opt-in Prompt & Client SDK --}}
    <div x-data="{
            showPrompt: false,
            loading: false,
            init() {
                // Check if already subscribed or dismissed
                if (!('Notification' in window) || !('serviceWorker' in navigator)) return;
                if (Notification.permission === 'granted') {
                    this.registerDeviceToken();
                    return;
                }
                if (Notification.permission === 'default' && !localStorage.getItem('threadax_push_dismissed')) {
                    setTimeout(() => { this.showPrompt = true; }, 4000);
                }
            },
            async subscribePush() {
                this.loading = true;
                try {
                    const permission = await Notification.requestPermission();
                    if (permission === 'granted') {
                        this.showPrompt = false;
                        await this.registerDeviceToken();
                        window.dispatchEvent(new CustomEvent('notify', {
                            detail: { message: '🎉 VIP Push Notifications enabled! You will receive drop alerts.', type: 'success' }
                        }));
                    } else {
                        this.showPrompt = false;
                        localStorage.setItem('threadax_push_dismissed', '1');
                    }
                } catch (e) {
                    console.error('Push subscription failed:', e);
                } finally {
                    this.loading = false;
                }
            },
            dismiss() {
                this.showPrompt = false;
                localStorage.setItem('threadax_push_dismissed', '1');
            },
            async registerDeviceToken() {
                if (!('serviceWorker' in navigator)) return;
                try {
                    const reg = await navigator.serviceWorker.register('/firebase-messaging-sw.js');
                    if (typeof firebase !== 'undefined' && firebase.messaging) {
                        const messaging = firebase.messaging();
                        const vapidKey = '{{ config('services.firebase.vapid_key') }}';
                        const token = await messaging.getToken({
                            serviceWorkerRegistration: reg,
                            vapidKey: vapidKey || undefined
                        });
                        if (token) {
                            fetch('{{ route('push-tokens.save') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    token: token,
                                    device_type: window.innerWidth < 768 ? 'android' : 'web',
                                    browser: navigator.userAgent
                                })
                            });
                        }
                    }
                } catch (err) {
                    console.debug('FCM Token sync skipped:', err.message);
                }
            }
         }"
         x-show="showPrompt"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="translate-y-8 opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="translate-y-0 opacity-100"
         x-transition:leave-end="translate-y-8 opacity-0"
         class="fixed bottom-4 left-4 right-4 sm:left-auto sm:right-6 sm:max-w-sm z-[140]"
         style="display: none;"
    >
        <div class="bg-slate-950 text-white rounded-2xl p-4 sm:p-5 shadow-2xl border border-white/15 backdrop-blur-xl">
            <div class="flex items-start gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center font-bold text-lg shrink-0 border border-amber-500/30">
                    🔔
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-xs sm:text-sm font-extrabold text-white tracking-wide">Never Miss a Drop!</h4>
                    <p class="text-[11px] text-slate-300 mt-1 leading-relaxed">
                        Get instant mobile notifications for exclusive streetwear drops & live order tracking.
                    </p>
                    <div class="flex items-center gap-2 mt-3.5">
                        <button type="button" 
                                @click="subscribePush()" 
                                :disabled="loading"
                                class="px-3.5 py-1.5 rounded-lg bg-white text-slate-950 text-xs font-extrabold hover:bg-slate-100 transition-colors cursor-pointer disabled:opacity-50">
                            <span x-text="loading ? 'Enabling...' : 'Enable Alerts ➔'"></span>
                        </button>
                        <button type="button" 
                                @click="dismiss()" 
                                class="px-3 py-1.5 rounded-lg text-[11px] font-bold text-slate-400 hover:text-white transition-colors cursor-pointer">
                            Later
                        </button>
                    </div>
                </div>
                <button type="button" @click="dismiss()" class="text-slate-400 hover:text-white transition-colors p-1" aria-label="Dismiss">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Firebase App & Messaging SDK --}}
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-messaging-compat.js"></script>
    <script>
        const firebaseConfig = {
            apiKey: "{{ config('services.firebase.api_key', 'AIzaSy_DEFAULT') }}",
            authDomain: "{{ config('services.firebase.auth_domain', 'threadax.firebaseapp.com') }}",
            projectId: "{{ config('services.firebase.project_id', 'threadax-ecommerce') }}",
            storageBucket: "{{ config('services.firebase.storage_bucket', 'threadax.appspot.com') }}",
            messagingSenderId: "{{ config('services.firebase.messaging_sender_id', '100000000000') }}",
            appId: "{{ config('services.firebase.app_id', '1:100000000000:web:threadax') }}"
        };
        try {
            if (typeof firebase !== 'undefined') {
                firebase.initializeApp(firebaseConfig);
            }
        } catch (e) {
            console.debug('Firebase config init:', e.message);
        }
    </script>
</body>

</html>