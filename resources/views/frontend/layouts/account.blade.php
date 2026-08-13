@extends('frontend.layouts.app')

@section('title', 'My Account — ThreadAX')

@section('content')
<div class="bg-gradient-to-b from-brand-light via-white to-brand-light min-h-screen">

    {{-- Hero Banner with Background Image --}}
    <div class="bg-brand-text relative overflow-hidden">
        {{-- Background Image --}}
        <img src="{{ asset('images/banner-oversized.png') }}" alt="" class="absolute inset-0 w-full h-full object-cover object-top opacity-20 mix-blend-luminosity">
        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/60 to-black/40"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 relative z-10">
            <div class="flex items-center gap-5 sm:gap-6">
                {{-- Avatar --}}
                <div class="w-18 h-18 sm:w-24 sm:h-24 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center ring-2 ring-white/20 shrink-0 shadow-2xl" style="width:4.5rem;height:4.5rem;">
                    <span class="text-white text-2xl sm:text-4xl font-black font-heading">{{ strtoupper(substr(auth()->user()->name ?? auth()->user()->email, 0, 1)) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl sm:text-3xl font-heading font-black text-white tracking-tight truncate">{{ auth()->user()->name ?? 'Member' }}</h1>
                    <p class="text-white/60 text-sm mt-0.5 truncate">{{ auth()->user()->email }}</p>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="inline-flex items-center gap-1.5 text-xs text-white/40 font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                            Member since {{ auth()->user()->created_at->format('M Y') }}
                        </span>
                        @if(auth()->user()->phone)
                            <span class="inline-flex items-center gap-1.5 text-xs text-white/40 font-medium">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                                {{ auth()->user()->phone }}
                            </span>
                        @endif
                    </div>
                </div>
                {{-- Edit Profile Button (Desktop) --}}
                <a href="{{ route('account.profile') }}" class="hidden sm:inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm text-white text-xs font-bold uppercase tracking-wider px-5 py-2.5 rounded-xl border border-white/20 hover:bg-white/20 transition-all shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                    Edit Profile
                </a>
            </div>
        </div>
    </div>

    {{-- Navigation Tabs --}}
    <div class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-brand-border shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center gap-0 overflow-x-auto no-scrollbar -mb-px">
                @php
                    $tabs = [
                        ['route' => 'account.dashboard', 'label' => 'Dashboard', 'icon' => 'M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z', 'match' => 'account.dashboard'],
                        ['route' => 'account.orders', 'label' => 'Orders', 'icon' => 'M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12', 'match' => 'account.orders*'],
                        ['route' => 'account.addresses', 'label' => 'Addresses', 'icon' => 'M15 10.5a3 3 0 11-6 0 3 3 0 016 0z M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z', 'match' => 'account.addresses'],
                        ['route' => 'account.wishlist', 'label' => 'Wishlist', 'icon' => 'M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z', 'match' => 'account.wishlist'],
                        ['route' => 'account.reviews', 'label' => 'Reviews', 'icon' => 'M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z', 'match' => 'account.reviews'],
                        ['route' => 'account.profile', 'label' => 'Profile', 'icon' => 'M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z', 'match' => 'account.profile'],
                    ];
                @endphp
                @foreach($tabs as $tab)
                    <a href="{{ route($tab['route']) }}" 
                       class="flex items-center gap-2 px-4 sm:px-5 py-4 text-xs sm:text-sm font-bold uppercase tracking-wider whitespace-nowrap border-b-2 transition-colors {{ request()->routeIs($tab['match']) ? 'border-brand-text text-brand-text' : 'border-transparent text-brand-muted hover:text-brand-text hover:border-brand-border' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $tab['icon'] }}"/></svg>
                        <span>{{ $tab['label'] }}</span>
                    </a>
                @endforeach
            </nav>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        @yield('account_content')
    </div>

</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
