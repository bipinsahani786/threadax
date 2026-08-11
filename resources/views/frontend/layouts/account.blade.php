@extends('frontend.layouts.app')

@section('title', 'My Account — ThreadAX')

@section('content')
<div class="bg-brand-light min-h-screen py-10 sm:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="text-3xl font-heading font-black text-brand-text uppercase tracking-tight">My Account</h1>
            <p class="text-brand-muted mt-2 text-sm">Manage your profile, orders, and addresses.</p>
        </div>

        <div class="flex flex-col md:flex-row gap-8">
            
            {{-- Sidebar Navigation --}}
            <aside class="w-full md:w-64 shrink-0">
                <nav class="space-y-1">
                    <a href="{{ route('account.dashboard') }}" class="flex items-center px-4 py-3 text-sm font-bold uppercase tracking-wider rounded-lg transition-colors {{ request()->routeIs('account.dashboard') ? 'bg-brand-text text-white' : 'text-brand-muted hover:bg-white hover:text-brand-text shadow-sm' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('account.orders') }}" class="flex items-center px-4 py-3 text-sm font-bold uppercase tracking-wider rounded-lg transition-colors {{ request()->routeIs('account.orders*') ? 'bg-brand-text text-white' : 'text-brand-muted hover:bg-white hover:text-brand-text shadow-sm' }}">
                        My Orders
                    </a>
                    <a href="{{ route('account.addresses') }}" class="flex items-center px-4 py-3 text-sm font-bold uppercase tracking-wider rounded-lg transition-colors {{ request()->routeIs('account.addresses') ? 'bg-brand-text text-white' : 'text-brand-muted hover:bg-white hover:text-brand-text shadow-sm' }}">
                        Addresses
                    </a>
                    <a href="{{ route('account.wishlist') }}" class="flex items-center px-4 py-3 text-sm font-bold uppercase tracking-wider rounded-lg transition-colors {{ request()->routeIs('account.wishlist') ? 'bg-brand-text text-white' : 'text-brand-muted hover:bg-white hover:text-brand-text shadow-sm' }}">
                        Wishlist
                    </a>
                    <a href="{{ route('account.reviews') }}" class="flex items-center px-4 py-3 text-sm font-bold uppercase tracking-wider rounded-lg transition-colors {{ request()->routeIs('account.reviews') ? 'bg-brand-text text-white' : 'text-brand-muted hover:bg-white hover:text-brand-text shadow-sm' }}">
                        My Reviews
                    </a>
                    <a href="{{ route('account.profile') }}" class="flex items-center px-4 py-3 text-sm font-bold uppercase tracking-wider rounded-lg transition-colors {{ request()->routeIs('account.profile') ? 'bg-brand-text text-white' : 'text-brand-muted hover:bg-white hover:text-brand-text shadow-sm' }}">
                        Profile Details
                    </a>
                    <form method="POST" action="{{ route('auth.logout') }}" class="mt-4 border-t border-brand-border pt-4">
                        @csrf
                        <button type="submit" class="flex w-full items-center px-4 py-3 text-sm font-bold uppercase tracking-wider rounded-lg transition-colors text-red-500 hover:bg-red-50 hover:text-red-700">
                            Logout
                        </button>
                    </form>
                </nav>
            </aside>

            {{-- Main Content Area --}}
            <main class="flex-1">
                @if(session('success'))
                    <div class="mb-6 px-4 py-3 rounded-lg text-sm font-medium bg-green-50 border border-green-200 text-green-700 flex items-center gap-2 shadow-sm">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-6 px-4 py-3 rounded-lg text-sm font-medium bg-red-50 border border-red-200 text-red-700 flex items-center gap-2 shadow-sm">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('account_content')
            </main>

        </div>
    </div>
</div>
@endsection
