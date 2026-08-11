<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — ThreadAX Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; background: #F8FAFC; color: #0F172A; }
        
        /* Custom Scrollbar for sidebar */
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 4px; }
        .sidebar-scroll:hover::-webkit-scrollbar-thumb { background: #94A3B8; }

        .nav-item { display: flex; align-items: center; gap: 10px; padding: 8px 12px; border-radius: 8px; color: #64748B; font-size: 13.5px; font-weight: 500; text-decoration: none; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); margin: 2px 16px; border: 1px solid transparent; }
        .nav-item:hover { background: #F8FAFC; color: #0F172A; }
        .nav-item.active { background: #F1F5F9; color: #0F172A; font-weight: 600; box-shadow: inset 0 2px 4px 0 rgba(0,0,0,0.02); }
        .nav-item.active svg { color: #0F172A; }
        
        .nav-section { font-size: 11px; font-weight: 700; color: #94A3B8; letter-spacing: 0.05em; text-transform: uppercase; padding: 16px 20px 6px; }
        .stat-card { background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 24px; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); transition: box-shadow 0.2s; }
        .stat-card:hover { box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06); }
        .badge-dark { background: #F1F5F9; border: 1px solid #E2E8F0; color: #0F172A; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px; letter-spacing: 0.05em; text-transform: uppercase; display: inline-block;}
        
        /* Alpine transitions */
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body x-data="{ sidebarOpen: false }" class="min-h-screen relative antialiased selection:bg-slate-900 selection:text-white" :class="{ 'overflow-hidden': sidebarOpen }">

    {{-- Mobile Sidebar Backdrop --}}
    <div x-cloak x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden" 
         @click="sidebarOpen = false"></div>

    {{-- Sidebar --}}
    <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
         class="fixed inset-y-0 left-0 z-50 w-[280px] bg-white border-r border-slate-200 flex flex-col transition-transform duration-300 ease-in-out lg:translate-x-0 shadow-2xl lg:shadow-none">
        
        {{-- Logo --}}
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between shrink-0">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-black tracking-[0.15em] text-slate-900 flex items-center" style="font-family:'Space Grotesk',sans-serif;">
                    THREAD<span class="bg-slate-900 text-white px-1.5 py-0.5 rounded ml-0.5">AX</span>
                </a>
            </div>
            {{-- Mobile Close Button --}}
            <button @click="sidebarOpen = false" class="lg:hidden p-2 text-slate-400 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 py-4 overflow-y-auto sidebar-scroll">
            <div class="nav-section">Main</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>

            <div class="nav-section">Catalog</div>
            <a href="{{ route('admin.products.index') }}" class="nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Products
            </a>
            <a href="{{ route('admin.categories.index') }}" class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Categories
            </a>

            <div class="nav-section">Orders</div>
            <a href="{{ route('admin.orders.index') }}" class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Orders
            </a>

            <div class="nav-section">Marketing & Feedback</div>
            <a href="{{ route('admin.banners.index') }}" class="nav-item {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Banners
            </a>
            <a href="{{ route('admin.coupons.index') }}" class="nav-item {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Coupons
            </a>
            <a href="{{ route('admin.reviews.index') }}" class="nav-item {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                Reviews
            </a>

            <div class="nav-section">Users</div>
            <a href="{{ route('admin.customers.index') }}" class="nav-item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Customers
            </a>
            
            <div class="nav-section">System</div>
            <a href="{{ route('admin.reports.index') }}" class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Reports
            </a>
            <a href="{{ route('admin.settings.index') }}" class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Settings
            </a>
        </nav>

        {{-- Admin info & Logout --}}
        <div class="p-4 border-t border-slate-100 bg-slate-50/50 shrink-0">
            <div class="flex items-center gap-3 p-2 rounded-xl bg-white border border-slate-200 shadow-sm mb-3">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center text-sm font-bold text-white bg-slate-900 shrink-0">
                    {{ strtoupper(substr(Auth::guard('admin')->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-slate-900 truncate">{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</p>
                    <p class="text-xs text-slate-500 truncate capitalize">{{ str_replace('_', ' ', Auth::guard('admin')->user()->role ?? '') }}</p>
                </div>
            </div>
            
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-bold text-red-600 bg-red-50 hover:bg-red-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="lg:ml-[280px] min-h-screen flex flex-col transition-all duration-300">
        
        {{-- Topbar --}}
        <header class="bg-white/80 backdrop-blur-md border-b border-slate-200 h-16 flex items-center justify-between px-4 sm:px-8 shrink-0 sticky top-0 z-30">
            <div class="flex items-center gap-4">
                {{-- Hamburger Mobile Button --}}
                <button @click="sidebarOpen = true" class="lg:hidden p-2 -ml-2 text-slate-500 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h2 class="text-lg font-bold text-slate-800 hidden sm:block" style="font-family:'Space Grotesk',sans-serif;">@yield('page-title', 'Dashboard')</h2>
            </div>
            
            <div class="flex items-center gap-4">
                <a href="{{ route('frontend.home') }}" target="_blank" class="px-4 py-2 rounded-lg bg-slate-50 border border-slate-200 text-slate-600 hover:text-slate-900 hover:bg-slate-100 text-sm flex items-center gap-2 font-semibold transition-all">
                    <span class="hidden sm:inline">View Store</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 p-4 sm:p-8">
            @if(session('success'))
                <div class="mb-6 px-4 py-3 rounded-xl text-sm bg-green-50 border border-green-200 text-green-700 font-medium flex items-center gap-3 shadow-sm">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @yield('content')
        </main>

    </div>

@stack('scripts')
</body>
</html>
