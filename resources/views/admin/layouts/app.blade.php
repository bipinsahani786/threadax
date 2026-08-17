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
        
        @php
            $sidebarPendingOrders = \App\Models\Order::whereIn('status', ['pending', 'processing'])->count();
            $sidebarPendingReturns = \App\Models\OrderReturn::where('status', 'requested')->count();
            $sidebarNewLeads = \App\Models\ContactLead::where('status', 'new')->count();
        @endphp

        {{-- Logo & Brand Bar --}}
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between shrink-0 bg-white">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 group">
                <div class="w-8 h-8 rounded-xl bg-slate-950 flex items-center justify-center text-white font-black text-xs shadow-md group-hover:scale-105 transition-transform">
                    TX
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-heading font-black tracking-wider text-slate-950 uppercase leading-none">
                        Thread<span class="text-slate-400">AX</span>
                    </span>
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Admin Console</span>
                    </span>
                </div>
            </a>
            
            {{-- Mobile Close Button --}}
            <button @click="sidebarOpen = false" class="lg:hidden p-1.5 text-slate-400 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Navigation Menu --}}
        <nav class="flex-1 py-3 px-3 overflow-y-auto sidebar-scroll space-y-5 text-xs">
            
            {{-- CORE --}}
            <div>
                <div class="px-3 mb-1.5 text-[10px] font-bold uppercase tracking-widest text-slate-400">Core</div>
                <div class="space-y-0.5">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center justify-between px-3 py-2 rounded-xl font-semibold transition-all duration-150 {{ request()->routeIs('admin.dashboard') ? 'bg-slate-950 text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-950 hover:bg-slate-100/70' }}">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                            <span>Dashboard</span>
                        </div>
                    </a>
                    <a href="{{ route('admin.reports.index') }}" 
                       class="flex items-center justify-between px-3 py-2 rounded-xl font-semibold transition-all duration-150 {{ request()->routeIs('admin.reports.*') ? 'bg-slate-950 text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-950 hover:bg-slate-100/70' }}">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.reports.*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                            <span>Analytics & Reports</span>
                        </div>
                    </a>
                </div>
            </div>

            {{-- COMMERCE --}}
            <div>
                <div class="px-3 mb-1.5 text-[10px] font-bold uppercase tracking-widest text-slate-400">Commerce</div>
                <div class="space-y-0.5">
                    <a href="{{ route('admin.orders.index') }}" 
                       class="flex items-center justify-between px-3 py-2 rounded-xl font-semibold transition-all duration-150 {{ request()->routeIs('admin.orders.*') ? 'bg-slate-950 text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-950 hover:bg-slate-100/70' }}">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.orders.*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                            <span>Orders</span>
                        </div>
                        @if($sidebarPendingOrders > 0)
                            <span class="px-1.5 py-0.5 rounded-full text-[10px] font-black {{ request()->routeIs('admin.orders.*') ? 'bg-amber-400 text-slate-950' : 'bg-amber-100 text-amber-800' }}">
                                {{ $sidebarPendingOrders }}
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('admin.returns.index') }}" 
                       class="flex items-center justify-between px-3 py-2 rounded-xl font-semibold transition-all duration-150 {{ request()->routeIs('admin.returns.*') ? 'bg-slate-950 text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-950 hover:bg-slate-100/70' }}">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.returns.*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                            <span>Returns & Exchanges</span>
                        </div>
                        @if($sidebarPendingReturns > 0)
                            <span class="px-1.5 py-0.5 rounded-full text-[10px] font-black {{ request()->routeIs('admin.returns.*') ? 'bg-amber-400 text-slate-950' : 'bg-amber-100 text-amber-800' }}">
                                {{ $sidebarPendingReturns }}
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('admin.transactions.index') }}" 
                       class="flex items-center justify-between px-3 py-2 rounded-xl font-semibold transition-all duration-150 {{ request()->routeIs('admin.transactions.*') ? 'bg-slate-950 text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-950 hover:bg-slate-100/70' }}">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.transactions.*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                            <span>Transactions & Logs</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.products.index') }}" 
                       class="flex items-center justify-between px-3 py-2 rounded-xl font-semibold transition-all duration-150 {{ request()->routeIs('admin.products.*') ? 'bg-slate-950 text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-950 hover:bg-slate-100/70' }}">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.products.*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/></svg>
                            <span>Products Catalog</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.categories.index') }}" 
                       class="flex items-center justify-between px-3 py-2 rounded-xl font-semibold transition-all duration-150 {{ request()->routeIs('admin.categories.*') ? 'bg-slate-950 text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-950 hover:bg-slate-100/70' }}">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.categories.*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg>
                            <span>Categories</span>
                        </div>
                    </a>
                </div>
            </div>

            {{-- CUSTOMERS & SUPPORT --}}
            <div>
                <div class="px-3 mb-1.5 text-[10px] font-bold uppercase tracking-widest text-slate-400">Customers & Support</div>
                <div class="space-y-0.5">
                    <a href="{{ route('admin.leads.index') }}" 
                       class="flex items-center justify-between px-3 py-2 rounded-xl font-semibold transition-all duration-150 {{ request()->routeIs('admin.leads.*') ? 'bg-slate-950 text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-950 hover:bg-slate-100/70' }}">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.leads.*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0017.25 4.5h-10.5a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                            <span>Contact Leads</span>
                        </div>
                        @if($sidebarNewLeads > 0)
                            <span class="px-1.5 py-0.5 rounded-full text-[10px] font-black {{ request()->routeIs('admin.leads.*') ? 'bg-blue-400 text-slate-950' : 'bg-blue-100 text-blue-800' }}">
                                {{ $sidebarNewLeads }}
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('admin.customers.index') }}" 
                       class="flex items-center justify-between px-3 py-2 rounded-xl font-semibold transition-all duration-150 {{ request()->routeIs('admin.customers.*') ? 'bg-slate-950 text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-950 hover:bg-slate-100/70' }}">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.customers.*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                            <span>Customers</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.reviews.index') }}" 
                       class="flex items-center justify-between px-3 py-2 rounded-xl font-semibold transition-all duration-150 {{ request()->routeIs('admin.reviews.*') ? 'bg-slate-950 text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-950 hover:bg-slate-100/70' }}">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.reviews.*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                            <span>Product Reviews</span>
                        </div>
                    </a>
                </div>
            </div>

            {{-- MARKETING --}}
            <div>
                <div class="px-3 mb-1.5 text-[10px] font-bold uppercase tracking-widest text-slate-400">Marketing</div>
                <div class="space-y-0.5">
                    <a href="{{ route('admin.banners.index') }}" 
                       class="flex items-center justify-between px-3 py-2 rounded-xl font-semibold transition-all duration-150 {{ request()->routeIs('admin.banners.*') ? 'bg-slate-950 text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-950 hover:bg-slate-100/70' }}">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.banners.*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                            <span>Hero Banners</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.coupons.index') }}" 
                       class="flex items-center justify-between px-3 py-2 rounded-xl font-semibold transition-all duration-150 {{ request()->routeIs('admin.coupons.*') ? 'bg-slate-950 text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-950 hover:bg-slate-100/70' }}">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.coupons.*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z"/></svg>
                            <span>Discount Coupons</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.notifications.create') }}" 
                       class="flex items-center justify-between px-3 py-2 rounded-xl font-semibold transition-all duration-150 {{ request()->routeIs('admin.notifications.*') ? 'bg-slate-950 text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-950 hover:bg-slate-100/70' }}">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.notifications.*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                            <span>Push Notifications</span>
                        </div>
                    </a>
                </div>
            </div>

            {{-- CONTENT & CMS --}}
            <div>
                <div class="px-3 mb-1.5 text-[10px] font-bold uppercase tracking-widest text-slate-400">Content & CMS</div>
                <div class="space-y-0.5">
                    <a href="{{ route('admin.blogs.index') }}" 
                       class="flex items-center justify-between px-3 py-2 rounded-xl font-semibold transition-all duration-150 {{ request()->routeIs('admin.blogs.*') ? 'bg-slate-950 text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-950 hover:bg-slate-100/70' }}">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.blogs.*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z"/></svg>
                            <span>Blog Articles</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.pages.index') }}" 
                       class="flex items-center justify-between px-3 py-2 rounded-xl font-semibold transition-all duration-150 {{ request()->routeIs('admin.pages.*') ? 'bg-slate-950 text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-950 hover:bg-slate-100/70' }}">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.pages.*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                            <span>Static Pages</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.faqs.index') }}" 
                       class="flex items-center justify-between px-3 py-2 rounded-xl font-semibold transition-all duration-150 {{ request()->routeIs('admin.faqs.*') ? 'bg-slate-950 text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-950 hover:bg-slate-100/70' }}">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.faqs.*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/></svg>
                            <span>FAQs</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.testimonials.index') }}" 
                       class="flex items-center justify-between px-3 py-2 rounded-xl font-semibold transition-all duration-150 {{ request()->routeIs('admin.testimonials.*') ? 'bg-slate-950 text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-950 hover:bg-slate-100/70' }}">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.testimonials.*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/></svg>
                            <span>Testimonials</span>
                        </div>
                    </a>
                </div>
            </div>

            {{-- SETTINGS --}}
            <div>
                <div class="px-3 mb-1.5 text-[10px] font-bold uppercase tracking-widest text-slate-400">Settings</div>
                <div class="space-y-0.5">
                    <a href="{{ route('admin.settings.index') }}" 
                       class="flex items-center justify-between px-3 py-2 rounded-xl font-semibold transition-all duration-150 {{ request()->routeIs('admin.settings.*') ? 'bg-slate-950 text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-950 hover:bg-slate-100/70' }}">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.settings.*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Store Settings</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.logs.index') }}" 
                       class="flex items-center justify-between px-3 py-2 rounded-xl font-semibold transition-all duration-150 {{ request()->routeIs('admin.logs.*') ? 'bg-slate-950 text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-950 hover:bg-slate-100/70' }}">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.logs.*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z"/></svg>
                            <span>Logs & Optimizer</span>
                        </div>
                    </a>
                </div>
            </div>

        </nav>

        {{-- Footer Admin Profile & Quick Logout --}}
        <div class="p-3 border-t border-slate-100 bg-slate-50/70 shrink-0">
            <div class="flex items-center justify-between p-2 rounded-2xl bg-white border border-slate-200/80 shadow-2xs">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 rounded-xl bg-slate-950 text-white font-black text-xs flex items-center justify-center shrink-0 shadow-2xs">
                        {{ strtoupper(substr(Auth::guard('admin')->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-slate-900 truncate">{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</p>
                        <p class="text-[10px] text-slate-400 capitalize truncate">{{ str_replace('_', ' ', Auth::guard('admin')->user()->role ?? 'Super Admin') }}</p>
                    </div>
                </div>

                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" 
                            title="Sign out of Admin Panel"
                            class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="lg:ml-[280px] min-h-screen flex flex-col transition-all duration-300">
        
        {{-- Topbar Header --}}
        @php
            $lastRead = session('admin_notifications_last_read');
            $unreadOrdersQuery = \App\Models\Order::whereIn('status', ['pending', 'processing']);
            if ($lastRead) {
                $unreadOrdersQuery->where('created_at', '>', $lastRead);
            }
            $unreadOrders = $unreadOrdersQuery->latest()->take(4)->get();
            $recentOrdersList = \App\Models\Order::latest()->take(4)->get();
            $adminLowStock = \App\Models\ProductVariant::with('product.images')->where('stock', '<=', 5)->latest()->take(3)->get();
            $totalNotificationCount = $unreadOrders->count() + ($lastRead ? 0 : $adminLowStock->count());
        @endphp

        <header class="bg-white border-b border-slate-200/80 h-16 flex items-center justify-between px-3 sm:px-6 lg:px-8 shrink-0 sticky top-0 z-30 shadow-2xs"
                x-data="{
                    searchQuery: '',
                    searchFocused: false,
                    mobileSearchOpen: false,
                    notifOpen: false,
                    profileOpen: false,
                    menuItems: [
                        { name: 'Dashboard Overview', url: '{{ route('admin.dashboard') }}', group: 'Navigation', icon: '⚡' },
                        { name: 'Orders Management', url: '{{ route('admin.orders.index') }}', group: 'Orders', icon: '📦' },
                        { name: 'Products Catalog', url: '{{ route('admin.products.index') }}', group: 'Catalog', icon: '👕' },
                        { name: '+ Add New Product', url: '{{ route('admin.products.create') }}', group: 'Actions', icon: '➕' },
                        { name: 'Categories', url: '{{ route('admin.categories.index') }}', group: 'Catalog', icon: '🏷️' },
                        { name: 'Customers & Users', url: '{{ route('admin.customers.index') }}', group: 'Users', icon: '👥' },
                        { name: 'Coupons & Discounts', url: '{{ route('admin.coupons.index') }}', group: 'Marketing', icon: '🎟️' },
                        { name: 'Analytics & Sales Reports', url: '{{ route('admin.reports.index') }}', group: 'System', icon: '📊' },
                        { name: 'Store Settings', url: '{{ route('admin.settings.index') }}', group: 'System', icon: '⚙️' },
                        { name: 'Customer Reviews', url: '{{ route('admin.reviews.index') }}', group: 'Feedback', icon: '⭐' },
                        { name: 'Hero Banners', url: '{{ route('admin.banners.index') }}', group: 'Marketing', icon: '🖼️' },
                        { name: 'Push Notifications', url: '{{ route('admin.notifications.create') }}', group: 'Marketing', icon: '🔔' }
                    ],
                    get filteredItems() {
                        if (!this.searchQuery) return this.menuItems.slice(0, 5);
                        return this.menuItems.filter(item => 
                            item.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                            item.group.toLowerCase().includes(this.searchQuery.toLowerCase())
                        );
                    }
                }"
                @keydown.window.ctrl.k.prevent="$refs.adminSearchInput?.focus(); searchFocused = true"
                @keydown.window.meta.k.prevent="$refs.adminSearchInput?.focus(); searchFocused = true"
                @keydown.escape.window="searchFocused = false; mobileSearchOpen = false; notifOpen = false; profileOpen = false">

            {{-- Normal Header State --}}
            <div x-show="!mobileSearchOpen" class="flex items-center justify-between w-full">
                
                {{-- Left Side: Hamburger & Title --}}
                <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                    <button @click="sidebarOpen = true" class="lg:hidden p-1.5 -ml-1 text-slate-600 hover:text-slate-950 hover:bg-slate-100 rounded-lg transition-colors focus:outline-none shrink-0" aria-label="Open Sidebar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div class="flex items-center gap-2 min-w-0">
                        <h2 class="text-sm sm:text-base lg:text-lg font-heading font-extrabold text-slate-900 truncate">@yield('page-title', 'Overview')</h2>
                        <span class="hidden md:inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>Live</span>
                        </span>
                    </div>
                </div>

                {{-- Center: Desktop Live Search Bar --}}
                <div class="hidden md:block relative flex-1 max-w-md mx-6" @click.outside="searchFocused = false">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                        </div>
                        <input type="text" 
                               x-ref="adminSearchInput"
                               x-model="searchQuery"
                               @focus="searchFocused = true"
                               placeholder="Search orders, products, settings... (Ctrl + K)" 
                               class="w-full pl-9 pr-14 py-2 text-xs font-medium text-slate-900 placeholder-slate-400 bg-slate-100/80 hover:bg-slate-100 focus:bg-white border border-slate-200/80 focus:border-slate-400 rounded-xl outline-none transition-all">
                        
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none">
                            <kbd class="text-[9px] font-semibold text-slate-400 bg-white px-1.5 py-0.5 rounded border border-slate-200">⌘K</kbd>
                        </div>
                    </div>

                    {{-- Live Search Autocomplete Dropdown --}}
                    <div x-cloak x-show="searchFocused"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="absolute left-0 right-0 top-full mt-2 bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden z-50">
                        
                        <div class="p-1.5 divide-y divide-slate-100 max-h-64 overflow-y-auto">
                            <template x-for="(item, idx) in filteredItems" :key="idx">
                                <a :href="item.url" 
                                   class="flex items-center justify-between px-3 py-2 rounded-xl hover:bg-slate-50 transition-colors group">
                                    <div class="flex items-center gap-2.5">
                                        <span class="text-sm" x-text="item.icon"></span>
                                        <div>
                                            <p class="text-xs font-bold text-slate-800 group-hover:text-slate-950" x-text="item.name"></p>
                                            <p class="text-[9px] text-slate-400 uppercase tracking-wider" x-text="item.group"></p>
                                        </div>
                                    </div>
                                    <svg class="w-3.5 h-3.5 text-slate-300 group-hover:text-slate-600 group-hover:translate-x-0.5 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                                </a>
                            </template>

                            <div x-show="filteredItems.length === 0" class="p-4 text-center text-xs text-slate-400">
                                No menu item matching "<span x-text="searchQuery"></span>"
                            </div>
                        </div>

                        <div class="px-3 py-2 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-[10px] text-slate-400">
                            <span>Quick Navigation</span>
                            <span>Press <kbd class="bg-white px-1 border rounded">ESC</kbd> to close</span>
                        </div>
                    </div>
                </div>

                {{-- Right Side Actions --}}
                <div class="flex items-center gap-1.5 sm:gap-2.5 shrink-0">
                    
                    {{-- Mobile Search Trigger Icon --}}
                    <button type="button" 
                            @click="mobileSearchOpen = true; $nextTick(() => $refs.mobileSearchInput?.focus())"
                            class="md:hidden p-2 text-slate-600 hover:text-slate-950 hover:bg-slate-100 rounded-xl transition-colors" aria-label="Search">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    </button>

                    {{-- Notification Bell Popover --}}
                    <div class="relative" @click.outside="notifOpen = false">
                        <button type="button" 
                                @click="notifOpen = !notifOpen; profileOpen = false"
                                class="relative p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-all cursor-pointer" aria-label="Notifications">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                            </svg>
                            @if($totalNotificationCount > 0)
                                <span class="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-rose-500 text-[9px] font-black text-white ring-2 ring-white">
                                    {{ $totalNotificationCount > 9 ? '9+' : $totalNotificationCount }}
                                </span>
                            @endif
                        </button>

                        {{-- Notification Dropdown Card --}}
                        <div x-cloak x-show="notifOpen"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                             class="absolute right-0 top-full mt-2 w-72 sm:w-96 rounded-2xl bg-white border border-slate-200 shadow-xl overflow-hidden z-50">
                            
                            <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between bg-slate-50/60">
                                <div>
                                    <h4 class="text-xs font-heading font-extrabold text-slate-900 uppercase tracking-wider">Notifications</h4>
                                    <p class="text-[10px] text-slate-400">{{ $totalNotificationCount }} new alert{{ $totalNotificationCount == 1 ? '' : 's' }}</p>
                                </div>
                                @if($totalNotificationCount > 0)
                                    <form action="{{ route('admin.notifications.markRead') }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-[10px] font-extrabold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-2.5 py-1 rounded-lg transition-colors cursor-pointer uppercase tracking-wider">
                                            ✓ Mark all read
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">✓ All Caught Up</span>
                                @endif
                            </div>

                            <div class="max-h-80 overflow-y-auto divide-y divide-slate-100 text-xs">
                                @if($unreadOrders->count() > 0)
                                    @foreach($unreadOrders as $uo)
                                        <a href="{{ route('admin.orders.show', $uo->id) }}" class="flex items-start gap-3 p-3.5 hover:bg-slate-50 transition-colors bg-blue-50/20">
                                            <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 mt-0.5 font-bold">
                                                📦
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between">
                                                    <p class="font-bold text-slate-900 truncate">New Order #{{ $uo->order_number ?? 'TX-'.$uo->id }}</p>
                                                    <span class="text-[10px] text-slate-400">{{ $uo->created_at->diffForHumans() }}</span>
                                                </div>
                                                <p class="text-[11px] text-slate-500 mt-0.5">₹{{ number_format($uo->total) }} • {{ $uo->items->count() }} items • {{ ucfirst($uo->payment_method) }}</p>
                                            </div>
                                        </a>
                                    @endforeach
                                @else
                                    @foreach($recentOrdersList as $ro)
                                        <a href="{{ route('admin.orders.show', $ro->id) }}" class="flex items-start gap-3 p-3.5 hover:bg-slate-50 transition-colors opacity-80">
                                            <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 mt-0.5">
                                                📦
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between">
                                                    <p class="font-bold text-slate-800 truncate">Order #{{ $ro->order_number ?? 'TX-'.$ro->id }}</p>
                                                    <span class="text-[10px] text-slate-400">{{ $ro->created_at->diffForHumans() }}</span>
                                                </div>
                                                <p class="text-[11px] text-slate-500 mt-0.5">₹{{ number_format($ro->total) }} • {{ ucfirst($ro->status) }}</p>
                                            </div>
                                        </a>
                                    @endforeach
                                @endif

                                @foreach($adminLowStock as $als)
                                    <a href="{{ route('admin.products.variants', $als->product_id) }}" class="flex items-start gap-3 p-3.5 hover:bg-slate-50 transition-colors">
                                        <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 mt-0.5">
                                            ⚠️
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-slate-900 truncate">{{ $als->product->name ?? 'Product' }}</p>
                                            <p class="text-[11px] text-amber-700 font-semibold mt-0.5">Only {{ $als->stock }} left ({{ $als->size ?? 'Std' }})</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>

                            <div class="p-2.5 border-t border-slate-100 bg-slate-50/50 text-center">
                                <a href="{{ route('admin.orders.index') }}" class="text-[11px] font-bold text-slate-900 hover:underline">
                                    View All Orders →
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- View Store Button (Desktop only) --}}
                    <a href="{{ route('frontend.home') }}" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 hover:text-slate-900 text-xs font-bold transition-all border border-slate-200/80 shadow-2xs">
                        <span>View Store</span>
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                    </a>

                    {{-- Profile Pill Menu --}}
                    <div class="relative" @click.outside="profileOpen = false">
                        <button type="button" 
                                @click="profileOpen = !profileOpen; notifOpen = false"
                                class="flex items-center gap-1.5 p-1 sm:px-2 rounded-xl hover:bg-slate-100 transition-colors border border-transparent hover:border-slate-200 cursor-pointer">
                            <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-slate-900 text-white font-black text-xs flex items-center justify-center shadow-2xs">
                                {{ substr(Auth::guard('admin')->user()->name ?? 'A', 0, 1) }}
                            </div>
                            <div class="hidden xl:block text-left">
                                <p class="text-xs font-bold text-slate-900 leading-tight truncate max-w-[90px]">{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</p>
                                <p class="text-[9px] text-slate-400 leading-tight capitalize">{{ str_replace('_', ' ', Auth::guard('admin')->user()->role ?? 'Admin') }}</p>
                            </div>
                            <svg class="hidden sm:block w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                        </button>

                        {{-- Profile Menu Dropdown --}}
                        <div x-cloak x-show="profileOpen"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                             class="absolute right-0 top-full mt-2 w-48 rounded-xl bg-white border border-slate-200 shadow-xl py-1.5 text-xs z-50">
                            
                            <div class="px-3 py-2 border-b border-slate-100 mb-1">
                                <p class="font-bold text-slate-900">{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</p>
                                <p class="text-[10px] text-slate-400 truncate">{{ Auth::guard('admin')->user()->email ?? '' }}</p>
                            </div>

                            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-2 px-3 py-2 text-slate-700 hover:bg-slate-50 font-medium">
                                <span>⚙️</span> Store Settings
                            </a>
                            <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-2 px-3 py-2 text-slate-700 hover:bg-slate-50 font-medium">
                                <span>📊</span> Analytics Reports
                            </a>

                            <div class="border-t border-slate-100 my-1"></div>

                            <form action="{{ route('admin.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-rose-600 hover:bg-rose-50 font-bold text-left cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>

                </div>

            </div>

            {{-- Mobile Full Width Search Overlay --}}
            <div x-cloak x-show="mobileSearchOpen" 
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="absolute inset-0 bg-white z-40 flex items-center px-3 gap-2">
                
                <div class="relative flex-1">
                    <input type="text" 
                           x-ref="mobileSearchInput"
                           x-model="searchQuery"
                           placeholder="Search orders, products, settings..." 
                           class="w-full pl-8 pr-3 py-2 text-xs font-medium text-slate-900 bg-slate-100 border border-slate-200 rounded-xl outline-none">
                    <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                </div>
                <button type="button" @click="mobileSearchOpen = false; searchQuery = ''" class="p-2 text-xs font-bold text-slate-500 hover:text-slate-900">
                    Cancel
                </button>

                {{-- Mobile Live Results Popover --}}
                <div x-show="searchQuery.length > 0" 
                     class="absolute left-3 right-3 top-full mt-1 bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden z-50 max-h-64 overflow-y-auto">
                    <div class="p-1.5 divide-y divide-slate-100">
                        <template x-for="(item, idx) in filteredItems" :key="idx">
                            <a :href="item.url" 
                               class="flex items-center justify-between px-3 py-2.5 hover:bg-slate-50">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm" x-text="item.icon"></span>
                                    <div>
                                        <p class="text-xs font-bold text-slate-800" x-text="item.name"></p>
                                        <p class="text-[9px] text-slate-400 uppercase" x-text="item.group"></p>
                                    </div>
                                </div>
                                <span class="text-slate-300 text-xs">→</span>
                            </a>
                        </template>
                        <div x-show="filteredItems.length === 0" class="p-4 text-center text-xs text-slate-400">
                            No menu item matching "<span x-text="searchQuery"></span>"
                        </div>
                    </div>
                </div>
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
