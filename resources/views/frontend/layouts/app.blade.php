<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="ThreadAx — Premium Streetwear. Oversized fits, premium fabrics, clean designs.">
    <title>@yield('title', 'ThreadAx') — Premium Streetwear</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased min-h-screen flex flex-col" x-data="{ mobileMenu: false, searchOpen: false }">

    {{-- ═══════════ TOP STRIP ═══════════ --}}
    <div class="w-full bg-brand-off-white border-b border-brand-border text-[10px] sm:text-xs text-brand-muted">
        <div class="max-w-[1440px] mx-auto px-2 sm:px-4 lg:px-8 flex items-center justify-between h-8">
            <div class="flex items-center gap-2 sm:gap-4">
                <span>📦 Free Shipping on ₹999+</span>
                <span class="hidden sm:inline">•</span>
                <span class="hidden sm:inline">🔄 Easy 7-Day Returns</span>
            </div>
            <div class="hidden sm:flex items-center gap-4">
                <a href="#" class="hover:text-brand-text transition-colors">Track Order</a>
                <a href="#" class="hover:text-brand-text transition-colors">Help</a>
            </div>
        </div>
    </div>

    {{-- ═══════════ MAIN HEADER ═══════════ --}}
    <header class="w-full bg-white sticky top-0 z-50 border-b border-brand-border shadow-sm">
        <div class="max-w-[1440px] mx-auto px-4 lg:px-8 flex items-center justify-between h-14 sm:h-16 relative">

            {{-- Mobile Hamburger (Left on mobile, hidden on desktop) --}}
            <button @click="mobileMenu = !mobileMenu" class="lg:hidden p-2 -ml-2 text-brand-text">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path x-show="!mobileMenu" stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    <path x-show="mobileMenu" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            {{-- Logo (Centered on mobile, Left on desktop) --}}
            <a href="{{ route('home') }}" class="absolute left-1/2 -translate-x-1/2 lg:static lg:translate-x-0 flex items-center gap-0 font-heading text-xl sm:text-2xl font-extrabold tracking-tight shrink-0">
                <span class="text-brand-text">THREAD</span><span class="threadax-logo-box ml-0.5 text-white">AX</span>
            </a>

            {{-- Nav Links — Desktop --}}
            <nav class="hidden lg:flex items-center gap-8 text-sm font-semibold uppercase tracking-wide text-brand-text ml-8">
                <a href="#" class="relative py-5 hover:text-brand-muted transition-colors group">
                    Men
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-brand-text group-hover:w-full transition-all duration-300"></span>
                </a>
                <a href="#" class="relative py-5 hover:text-brand-muted transition-colors group">
                    Women
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-brand-text group-hover:w-full transition-all duration-300"></span>
                </a>
                <a href="#" class="relative py-5 hover:text-brand-muted transition-colors group">
                    Oversized
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-brand-text group-hover:w-full transition-all duration-300"></span>
                </a>
                <a href="#" class="relative py-5 hover:text-brand-muted transition-colors group">
                    New Arrivals
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-brand-text group-hover:w-full transition-all duration-300"></span>
                </a>
            </nav>

            {{-- Right Side — Icons --}}
            <div class="flex items-center gap-2 sm:gap-4 ml-auto lg:ml-0">
                
                {{-- Search Toggle (Mobile & Desktop) --}}
                <button @click="searchOpen = !searchOpen" class="p-2 text-brand-text hover:text-brand-muted transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>

                {{-- Account (Desktop Only) --}}
                <div class="hidden lg:block relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    @auth
                        <a href="{{ route('account.dashboard') }}" class="p-2 text-brand-text hover:text-brand-muted transition-colors flex items-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                            </svg>
                        </a>
                        <div x-show="open" x-transition.opacity class="absolute right-0 top-full w-48 bg-white border border-brand-border rounded-lg shadow-xl py-2 z-50">
                            <a href="{{ route('account.dashboard') }}" class="block px-4 py-2 text-sm hover:bg-brand-light transition-colors">My Account</a>
                            <a href="#" class="block px-4 py-2 text-sm hover:bg-brand-light transition-colors">Orders</a>
                            <form method="POST" action="{{ route('auth.logout') }}" class="border-t border-brand-border mt-1">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition-colors">Logout</button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('auth.login') }}" class="p-2 text-brand-text hover:text-brand-muted transition-colors flex items-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                            </svg>
                        </a>
                    @endauth
                </div>

                {{-- Wishlist (Desktop Only) --}}
                <a href="#" class="hidden lg:block p-2 text-brand-text hover:text-brand-muted transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                    </svg>
                </a>

                {{-- Bag --}}
                <a href="#" class="p-2 -mr-2 sm:mr-0 text-brand-text hover:text-brand-muted transition-colors relative">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/>
                    </svg>
                    <span class="absolute top-1.5 right-1.5 w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                </a>
            </div>
        </div>

        {{-- Expanded Search Bar --}}
        <div x-show="searchOpen" x-transition class="w-full bg-white border-t border-brand-border px-4 py-3">
            <div class="max-w-[1440px] mx-auto flex items-center bg-brand-light rounded-md px-4 py-2">
                <svg class="w-4 h-4 text-brand-muted mr-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" placeholder="Search for products..." class="bg-transparent text-sm w-full focus:outline-none text-brand-text placeholder:text-brand-muted">
                <button @click="searchOpen = false" class="ml-2 text-brand-muted hover:text-brand-text">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileMenu" x-transition class="lg:hidden w-full bg-white border-t border-brand-border absolute top-full left-0 shadow-lg">
            <nav class="flex flex-col text-sm font-semibold uppercase tracking-wide">
                <a href="#" class="px-6 py-4 hover:bg-brand-light border-b border-brand-border transition-colors">Men</a>
                <a href="#" class="px-6 py-4 hover:bg-brand-light border-b border-brand-border transition-colors">Women</a>
                <a href="#" class="px-6 py-4 hover:bg-brand-light border-b border-brand-border transition-colors">Oversized</a>
                <a href="#" class="px-6 py-4 hover:bg-brand-light border-b border-brand-border transition-colors">New Arrivals</a>
                <div class="p-6 bg-brand-off-white flex items-center gap-4">
                    @auth
                        <a href="{{ route('account.dashboard') }}" class="flex-1 text-center py-2 bg-brand-text text-white rounded">My Account</a>
                        <form method="POST" action="{{ route('auth.logout') }}" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full text-center py-2 border border-brand-text text-brand-text rounded">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('auth.login') }}" class="w-full text-center py-2 bg-brand-text text-white rounded">Login / Sign Up</a>
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
                        <li><a href="#" class="hover:text-brand-text transition-colors">About Us</a></li>
                        <li><a href="#" class="hover:text-brand-text transition-colors">Careers</a></li>
                        <li><a href="#" class="hover:text-brand-text transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-brand-text transition-colors">Press</a></li>
                    </ul>
                </div>

                {{-- Help --}}
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest text-brand-text mb-4">Help</h4>
                    <ul class="space-y-2 text-sm text-brand-muted">
                        <li><a href="#" class="hover:text-brand-text transition-colors">Track Order</a></li>
                        <li><a href="#" class="hover:text-brand-text transition-colors">Returns & Exchanges</a></li>
                        <li><a href="#" class="hover:text-brand-text transition-colors">Shipping Info</a></li>
                        <li><a href="#" class="hover:text-brand-text transition-colors">FAQ</a></li>
                    </ul>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest text-brand-text mb-4">Shop</h4>
                    <ul class="space-y-2 text-sm text-brand-muted">
                        <li><a href="#" class="hover:text-brand-text transition-colors">Men</a></li>
                        <li><a href="#" class="hover:text-brand-text transition-colors">Women</a></li>
                        <li><a href="#" class="hover:text-brand-text transition-colors">Oversized</a></li>
                        <li><a href="#" class="hover:text-brand-text transition-colors">New Arrivals</a></li>
                    </ul>
                </div>

                {{-- Connect & Newsletter --}}
                <div class="col-span-2 lg:col-span-1">
                    <h4 class="text-xs font-bold uppercase tracking-widest text-brand-text mb-4">Connect</h4>
                    <ul class="space-y-2 text-sm text-brand-muted flex gap-4 lg:block lg:space-y-2 lg:gap-0">
                        <li><a href="https://www.instagram.com/threadax.co.in/" target="_blank" class="hover:text-brand-text transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5 lg:w-4 lg:h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                            <span class="hidden lg:inline">Instagram</span>
                        </a></li>
                        <li><a href="#" class="hover:text-brand-text transition-colors flex items-center gap-2">
                            <span class="lg:hidden text-lg">𝕏</span><span class="hidden lg:inline">Twitter</span>
                        </a></li>
                        <li><a href="#" class="hover:text-brand-text transition-colors flex items-center gap-2">
                            <span class="lg:hidden text-lg">f</span><span class="hidden lg:inline">Facebook</span>
                        </a></li>
                    </ul>
                    <div class="mt-6">
                        <p class="text-xs font-bold uppercase tracking-widest text-brand-text mb-3">Newsletter</p>
                        <div class="flex gap-2">
                            <input type="email" placeholder="your@email.com" class="input-field text-xs !py-2 flex-1 w-full max-w-[250px]">
                            <button class="bg-brand-text text-white text-xs font-semibold px-4 py-2 rounded-md hover:bg-brand-dark/80 transition-colors shrink-0">Join</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bottom Bar --}}
            <div class="mt-10 pt-6 border-t border-brand-border flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-brand-muted">
                <div class="flex items-center gap-1">
                    <span class="font-heading font-extrabold text-sm text-brand-text">THREAD</span><span class="threadax-logo-box text-[10px] text-white">AX</span>
                    <span class="ml-2">&copy; {{ date('Y') }} All rights reserved.</span>
                </div>
                <div class="flex gap-4 flex-wrap justify-center">
                    <a href="#" class="hover:text-brand-text transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-brand-text transition-colors">Terms of Service</a>
                    <a href="#" class="hover:text-brand-text transition-colors">Cookie Preferences</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- ═══════════ MOBILE BOTTOM NAVIGATION (5 ICONS) ═══════════ --}}
    <div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white rounded-t-2xl border-t border-brand-border z-50 flex justify-between items-end px-2 pt-2 pb-3 text-[10px] font-bold text-brand-muted shadow-[0_-5px_20px_rgba(0,0,0,0.08)]">
        
        {{-- 1. Home --}}
        <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 p-2 flex-1 {{ request()->routeIs('home') ? 'text-brand-text' : 'hover:text-brand-text' }}">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <span>Home</span>
        </a>

        {{-- 2. Explore --}}
        <a href="#" class="flex flex-col items-center gap-1 p-2 flex-1 hover:text-brand-text">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
            </svg>
            <span>Explore</span>
        </a>

        {{-- 3. Bag (Center Floating Highlight) --}}
        <a href="#" class="flex flex-col items-center p-0 flex-1 relative -mt-6 pb-1">
            <div class="w-14 h-14 bg-brand-text text-white rounded-full flex items-center justify-center shadow-lg border-[4px] border-white relative transition-transform hover:scale-105">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z" />
                </svg>
                <span class="absolute top-0 right-0 w-3.5 h-3.5 bg-red-500 rounded-full border-2 border-brand-text"></span>
            </div>
            <span class="mt-1">Bag</span>
        </a>

        {{-- 4. Wishlist --}}
        <a href="#" class="flex flex-col items-center gap-1 p-2 flex-1 hover:text-brand-text">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
            </svg>
            <span>Wishlist</span>
        </a>

        {{-- 5. Profile --}}
        <a href="{{ route('account.dashboard') }}" class="flex flex-col items-center gap-1 p-2 flex-1 {{ request()->is('account*') ? 'text-brand-text' : 'hover:text-brand-text' }}">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
            </svg>
            <span>Profile</span>
        </a>

    </div>

</body>
</html>
