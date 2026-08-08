@extends('frontend.layouts.app')

@section('title', 'Login / Sign Up')

@section('content')
<div class="w-full min-h-[calc(100vh-220px)] flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-[420px]" x-data="{ state: '{{ session('otp_sent') ? 'otp' : 'email' }}', email: '{{ session('email', '') }}' }">

        {{-- Card --}}
        <div class="bg-white border border-brand-border rounded-xl p-8 shadow-sm">

            {{-- Logo --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center gap-0.5 font-heading text-2xl font-extrabold mb-3">
                    <span>THREAD</span><span class="threadax-logo-box">AX</span>
                </div>
                <h1 class="text-xl font-heading font-bold text-brand-text" x-show="state === 'email'">Login <span class="text-brand-muted font-normal">or</span> Sign Up</h1>
                <h1 class="text-xl font-heading font-bold text-brand-text" x-show="state === 'otp'" x-cloak>Verify Your Email</h1>
            </div>

            {{-- Errors --}}
            @if ($errors->any())
                <div class="mb-5 p-3 rounded-lg bg-red-50 border border-red-100 text-red-600 text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{-- Step 1: Email --}}
            <form x-show="state === 'email'" method="POST" action="{{ route('auth.otp.send') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-brand-muted mb-1.5">Email Address</label>
                    <input type="email" name="email" id="email" x-model="email" required autofocus
                        class="input-field"
                        placeholder="name@example.com">
                </div>

                <p class="text-xs text-brand-muted leading-relaxed">
                    By continuing, I agree to the <a href="#" class="text-brand-accent font-medium">Terms of Use</a> & <a href="#" class="text-brand-accent font-medium">Privacy Policy</a>.
                </p>

                <button type="submit" class="btn-primary w-full text-sm text-center">
                    Continue
                </button>
            </form>

            {{-- Step 2: OTP --}}
            <form x-show="state === 'otp'" x-cloak method="POST" action="{{ route('auth.otp.verify') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="email" :value="email">

                <div class="text-center mb-2">
                    <p class="text-sm text-brand-muted">We've sent a 6-digit code to</p>
                    <p class="text-sm font-semibold text-brand-text mt-1" x-text="email"></p>
                    <button type="button" @click="state = 'email'" class="text-xs text-brand-accent font-medium mt-1 hover:underline">Change</button>
                </div>

                <div>
                    <label for="code" class="block text-xs font-bold uppercase tracking-wider text-brand-muted mb-1.5">Enter OTP</label>
                    <input type="text" name="code" id="code" required maxlength="6" pattern="[0-9]{6}" autocomplete="one-time-code"
                        class="input-field text-center text-xl tracking-[0.4em] font-bold"
                        placeholder="● ● ● ● ● ●">
                </div>

                <button type="submit" class="btn-primary w-full text-sm text-center">
                    Verify & Login
                </button>

                <div class="text-center">
                    <button type="button" class="text-xs text-brand-muted hover:text-brand-accent transition-colors">Resend OTP</button>
                </div>
            </form>

            {{-- Divider --}}
            <div class="flex items-center gap-3 my-6">
                <div class="h-px bg-brand-border flex-1"></div>
                <span class="text-xs font-medium text-brand-muted uppercase tracking-wider">or</span>
                <div class="h-px bg-brand-border flex-1"></div>
            </div>

            {{-- Google OAuth --}}
            <a href="{{ route('auth.google.redirect') }}" class="btn-outline w-full text-sm flex items-center justify-center gap-3">
                <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Continue with Google
            </a>
        </div>

        {{-- Trust Badges --}}
        <div class="mt-6 flex items-center justify-center gap-6 text-brand-muted text-[11px]">
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                100% Secure
            </span>
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                Encrypted Data
            </span>
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                Safe Payments
            </span>
        </div>
    </div>
</div>
@endsection
