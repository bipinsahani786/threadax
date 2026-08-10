@extends('frontend.layouts.app')

@section('title', 'Admin Login — ThreadAX')
@section('meta_description', 'Secure login to ThreadAX Administration Panel.')

@section('content')

<div class="relative w-full min-h-[calc(100vh-130px)] flex items-stretch overflow-hidden bg-white">

    {{-- Left Image Banner (Stuck to Center Card, Touch Header & Footer) --}}
    <div class="hidden lg:block flex-1 relative overflow-hidden group">
        <img src="{{ asset('images/banner-men.png') }}" alt="ThreadAX Administration" class="w-full h-full object-cover object-center transition-transform duration-700 group-hover:scale-105">
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent flex items-end p-8">
            <div>
                <span class="text-white/70 text-xs font-bold uppercase tracking-widest block mb-1">System Access</span>
                <h3 class="text-white font-heading font-extrabold text-2xl tracking-tight">Admin Portal</h3>
            </div>
        </div>
    </div>

    {{-- Center Login Card --}}
    <div class="w-full lg:w-[500px] xl:w-[540px] shrink-0 bg-white flex flex-col justify-center px-6 sm:px-10 py-10 relative z-10 border-x border-brand-border shadow-2xl">
        
        {{-- Branding & Heading --}}
        <div class="text-center mb-8">
            <a href="{{ route('frontend.home') }}" class="inline-flex items-center gap-1 font-heading text-2xl font-black tracking-tight mb-4">
                <span class="text-brand-text">THREAD</span><span class="threadax-logo-box text-white">AX</span>
            </a>
            <h1 class="font-heading text-3xl font-extrabold text-brand-text tracking-tight mb-1">Secure Login</h1>
            <p class="text-brand-muted text-sm">Enter your administrative credentials</p>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="mb-6 px-4 py-3 rounded-xl text-sm font-medium bg-green-50 border border-green-200 text-green-700 flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 px-4 py-3 rounded-xl text-sm bg-red-50 border border-red-200 text-red-600 space-y-1">
                @foreach($errors->all() as $error)
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $error }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Form --}}
        <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-widest text-brand-muted mb-2">
                    Email Address
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="admin@threadax.com"
                    required
                    autofocus
                    class="w-full px-4 py-3.5 border border-brand-border rounded-xl text-sm text-brand-text bg-brand-light placeholder-brand-muted focus:outline-none focus:border-brand-text focus:bg-white transition-all"
                >
            </div>

            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-widest text-brand-muted mb-2">
                    Password
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="••••••••"
                    required
                    class="w-full px-4 py-3.5 border border-brand-border rounded-xl text-sm text-brand-text bg-brand-light placeholder-brand-muted focus:outline-none focus:border-brand-text focus:bg-white transition-all"
                >
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="remember" id="remember" class="w-4 h-4 border-brand-border rounded text-brand-text focus:ring-brand-text">
                <label for="remember" class="ml-2 block text-sm text-brand-muted">
                    Remember me
                </label>
            </div>

            <button type="submit" class="btn-primary w-full text-center text-sm uppercase tracking-wider py-4 rounded-xl font-bold flex items-center justify-center gap-2 group">
                <span>Access Dashboard</span>
                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>
        </form>

        {{-- Footer --}}
        <p class="text-brand-muted text-xs text-center mt-8">
            <a href="{{ route('frontend.home') }}" class="underline font-medium hover:text-brand-text">← Back to Store</a>
        </p>

    </div>

    {{-- Right Image Banner (Stuck to Center Card, Touch Header & Footer) --}}
    <div class="hidden lg:block flex-1 relative overflow-hidden group">
        <img src="{{ asset('images/banner-women.png') }}" alt="ThreadAX Administration" class="w-full h-full object-cover object-center transition-transform duration-700 group-hover:scale-105">
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent flex items-end p-8">
            <div>
                <span class="text-white/70 text-xs font-bold uppercase tracking-widest block mb-1">Management</span>
                <h3 class="text-white font-heading font-extrabold text-2xl tracking-tight">Control Center</h3>
            </div>
        </div>
    </div>

</div>

@endsection
