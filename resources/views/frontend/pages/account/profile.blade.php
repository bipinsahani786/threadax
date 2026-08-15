@extends('frontend.layouts.account')

@section('account_content')
<div class="space-y-5 sm:space-y-6">

    {{-- Top Profile Overview Card --}}
    <div class="bg-white border border-brand-border rounded-2xl p-4 sm:p-6 shadow-xs flex flex-col sm:flex-row items-center sm:items-start gap-4 sm:gap-5">
        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-brand-dark text-white flex items-center justify-center font-heading font-black text-2xl sm:text-3xl shadow-sm shrink-0">
            {{ strtoupper(substr($user->name ?? $user->email, 0, 1)) }}
        </div>
        <div class="flex-1 text-center sm:text-left min-w-0">
            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                <h2 class="text-lg sm:text-xl font-heading font-extrabold text-brand-dark truncate">{{ $user->name ?? 'Streetwear Enthusiast' }}</h2>
                <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold px-2 py-0.5 rounded-full">
                    <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    Verified Member
                </span>
            </div>
            <p class="text-xs sm:text-sm text-brand-muted mt-0.5">{{ $user->email }}</p>
            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3 mt-2 text-xs text-brand-muted font-medium">
                <span class="inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-brand-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                    Member since {{ $user->created_at->format('M Y') }}
                </span>
                <span>•</span>
                <span class="inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-brand-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/></svg>
                    {{ $user->orders()->count() }} {{ Str::plural('Order', $user->orders()->count()) }}
                </span>
            </div>
        </div>
    </div>

    {{-- Grid: Form + Side Info --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        
        {{-- Main Profile Form --}}
        <div class="lg:col-span-2 bg-white border border-brand-border rounded-2xl p-5 sm:p-7 shadow-xs">
            <div class="mb-5 pb-4 border-b border-brand-border/60">
                <h3 class="text-sm sm:text-base font-heading font-extrabold text-brand-dark">Personal Information</h3>
                <p class="text-xs text-brand-muted mt-0.5">Manage your display name and contact phone number.</p>
            </div>

            @if(session('success'))
                <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold px-4 py-3 rounded-xl flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('account.profile.update') }}" method="POST" class="space-y-4 sm:space-y-5">
                @csrf
                
                {{-- Full Name --}}
                <div>
                    <label for="name" class="block text-xs font-bold text-brand-dark uppercase tracking-wider mb-1.5">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-brand-muted">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                        </div>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required placeholder="Your full name"
                            class="w-full pl-10 pr-3.5 py-2.5 bg-brand-off-white/80 border border-brand-border rounded-xl text-xs sm:text-sm font-semibold text-brand-dark placeholder:text-brand-muted focus:bg-white focus:outline-none focus:border-brand-dark focus:ring-1 focus:ring-brand-dark transition-all">
                    </div>
                    @error('name') <span class="text-red-500 text-[11px] font-medium mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Phone Number --}}
                <div>
                    <label for="phone" class="block text-xs font-bold text-brand-dark uppercase tracking-wider mb-1.5">
                        Phone Number
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-brand-muted">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                        </div>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" placeholder="+91 98765 43210"
                            class="w-full pl-10 pr-3.5 py-2.5 bg-brand-off-white/80 border border-brand-border rounded-xl text-xs sm:text-sm font-semibold text-brand-dark placeholder:text-brand-muted focus:bg-white focus:outline-none focus:border-brand-dark focus:ring-1 focus:ring-brand-dark transition-all">
                    </div>
                    <p class="text-[10px] text-brand-muted mt-1">Used for order tracking SMS and delivery coordination.</p>
                    @error('phone') <span class="text-red-500 text-[11px] font-medium mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Email Address (Locked) --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="email" class="block text-xs font-bold text-brand-dark uppercase tracking-wider">
                            Email Address
                        </label>
                        <span class="text-[10px] font-bold text-brand-muted uppercase flex items-center gap-1">
                            <svg class="w-3 h-3 text-brand-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                            Locked for Security
                        </span>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-brand-muted/60">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                        </div>
                        <input type="email" name="email" id="email" value="{{ $user->email }}" disabled
                            class="w-full pl-10 pr-10 py-2.5 bg-brand-off-white/40 border border-brand-border/60 rounded-xl text-xs sm:text-sm font-semibold text-brand-muted cursor-not-allowed">
                        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-brand-muted/60">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                        </div>
                    </div>
                    <div class="bg-blue-50/70 border border-blue-200/70 rounded-xl p-2.5 mt-2 flex items-start gap-2 text-[11px] text-blue-900 leading-snug">
                        <svg class="w-3.5 h-3.5 text-blue-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                        <span>Your primary login email is verified. To change your registered email, contact ThreadAx Support.</span>
                    </div>
                </div>

                {{-- Action Submit --}}
                <div class="pt-4 border-t border-brand-border/60 flex items-center justify-end">
                    <button type="submit" class="bg-brand-dark text-white hover:bg-brand-text font-bold text-xs uppercase tracking-wider px-6 py-2.5 sm:py-3 rounded-xl transition-all shadow-sm hover:shadow flex items-center gap-2 active:scale-95 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        <span>Save Changes</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Side Security & Quick Links Cards --}}
        <div class="space-y-4">
            {{-- Account Protection Card --}}
            <div class="bg-white border border-brand-border rounded-2xl p-5 shadow-xs">
                <h4 class="text-xs font-extrabold uppercase tracking-wider text-brand-dark mb-3 pb-2 border-b border-brand-border flex items-center gap-2">
                    <span>🛡️</span>
                    <span>Account Security</span>
                </h4>
                <div class="space-y-3 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-brand-muted">OTP Login</span>
                        <span class="font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">Active</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-brand-muted">Encryption</span>
                        <span class="font-bold text-brand-dark">256-bit SSL</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-brand-muted">Account Status</span>
                        <span class="font-bold text-emerald-700">Good Standing</span>
                    </div>
                </div>
            </div>

            {{-- Quick Links Card --}}
            <div class="bg-brand-off-white/60 border border-brand-border rounded-2xl p-5 shadow-xs">
                <h4 class="text-xs font-extrabold uppercase tracking-wider text-brand-dark mb-3 pb-2 border-b border-brand-border flex items-center gap-2">
                    <span>⚡</span>
                    <span>Quick Shortcuts</span>
                </h4>
                <div class="space-y-2 text-xs font-bold">
                    <a href="{{ route('account.addresses') }}" class="flex items-center justify-between p-2 rounded-xl bg-white border border-brand-border hover:border-brand-dark transition-all text-brand-dark">
                        <span>Manage Addresses</span>
                        <span>→</span>
                    </a>
                    <a href="{{ route('account.orders') }}" class="flex items-center justify-between p-2 rounded-xl bg-white border border-brand-border hover:border-brand-dark transition-all text-brand-dark">
                        <span>My Orders</span>
                        <span>→</span>
                    </a>
                    <a href="{{ route('account.wishlist') }}" class="flex items-center justify-between p-2 rounded-xl bg-white border border-brand-border hover:border-brand-dark transition-all text-brand-dark">
                        <span>Saved Wishlist</span>
                        <span>→</span>
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
