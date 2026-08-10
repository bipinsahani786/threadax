@extends('frontend.layouts.app')

@section('title', 'Verify OTP — ThreadAX')
@section('meta_description', 'Enter your one-time password to login to ThreadAX.')

@section('content')

<div class="relative w-full min-h-[calc(100vh-130px)] flex items-stretch overflow-hidden bg-white" x-data="{ otpDigits: ['', '', '', '', '', ''], get code() { return this.otpDigits.join(''); } }">

    {{-- Left Image Banner (Stuck to Center Card, Touch Header & Footer) --}}
    <div class="hidden lg:block flex-1 relative overflow-hidden group">
        <img src="{{ asset('images/banner-men.png') }}" alt="ThreadAX Men" class="w-full h-full object-cover object-center transition-transform duration-700 group-hover:scale-105">
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent flex items-end p-8">
            <div>
                <span class="text-white/70 text-xs font-bold uppercase tracking-widest block mb-1">New Season</span>
                <h3 class="text-white font-heading font-extrabold text-2xl tracking-tight">Oversized Fits</h3>
            </div>
        </div>
    </div>

    {{-- Center OTP Card (Full Height Top-to-Bottom, Touches Left & Right Images directly) --}}
    <div class="w-full lg:w-[500px] xl:w-[540px] shrink-0 bg-white flex flex-col justify-center px-6 sm:px-10 py-10 relative z-10 border-x border-brand-border shadow-2xl">
        
        {{-- Branding & Heading --}}
        <div class="text-center mb-6">
            <a href="{{ route('frontend.home') }}" class="inline-flex items-center gap-1 font-heading text-2xl font-black tracking-tight mb-4">
                <span class="text-brand-text">THREAD</span><span class="threadax-logo-box text-white">AX</span>
            </a>
            <h1 class="font-heading text-3xl font-extrabold text-brand-text tracking-tight mb-1">Check Your Email</h1>
            <p class="text-brand-muted text-sm">
                We sent a 6-digit verification code to<br>
                <span class="font-bold text-brand-text break-all">{{ $email }}</span>
            </p>
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
        <form action="{{ route('auth.otp.verify') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="code" :value="code">

            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-brand-muted mb-3 text-center">
                    Enter 6-Digit Code
                </label>
                
                {{-- 6 Fluid Individual Box Inputs --}}
                <div id="otp-container" class="flex items-center justify-between gap-1.5 sm:gap-2.5 w-full max-w-sm mx-auto">
                    <template x-for="(digit, index) in otpDigits" :key="index">
                        <input
                            type="text"
                            maxlength="1"
                            inputmode="numeric"
                            pattern="[0-9]"
                            x-model="otpDigits[index]"
                            class="otp-input flex-1 min-w-0 h-12 sm:h-14 text-xl sm:text-2xl font-extrabold font-heading text-center text-brand-text bg-brand-light border-2 border-brand-border rounded-xl focus:border-brand-text focus:bg-white focus:outline-none focus:ring-4 focus:ring-brand-text/10 transition-all shadow-sm"
                            required
                        >
                    </template>
                </div>

                <p class="text-brand-muted text-xs text-center mt-3">Code expires in 10 minutes.</p>
            </div>

            <button type="submit" id="verify-otp-btn" class="btn-primary w-full text-center text-sm uppercase tracking-wider py-4 rounded-xl font-bold flex items-center justify-center gap-2 group">
                <span>Verify & Login</span>
                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>
        </form>

        {{-- Resend & Change email --}}
        <div class="flex items-center justify-center gap-4 mt-6 text-sm text-brand-muted">
            <form action="{{ route('auth.otp.send') }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <button type="submit" class="font-bold text-brand-text hover:underline cursor-pointer">
                    Resend Code
                </button>
            </form>
            <span class="text-brand-border">·</span>
            <a href="{{ route('auth.login') }}" class="font-medium hover:text-brand-text hover:underline">
                Change email
            </a>
        </div>

        {{-- Terms --}}
        <p class="text-brand-muted text-xs text-center mt-8">
            By logging in, you agree to our
            <a href="#" class="underline font-medium hover:text-brand-text">Terms</a> &
            <a href="#" class="underline font-medium hover:text-brand-text">Privacy Policy</a>
        </p>

    </div>

    {{-- Right Image Banner (Stuck to Center Card, Touch Header & Footer) --}}
    <div class="hidden lg:block flex-1 relative overflow-hidden group">
        <img src="{{ asset('images/banner-women.png') }}" alt="ThreadAX Women" class="w-full h-full object-cover object-center transition-transform duration-700 group-hover:scale-105">
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent flex items-end p-8">
            <div>
                <span class="text-white/70 text-xs font-bold uppercase tracking-widest block mb-1">Exclusive Drop</span>
                <h3 class="text-white font-heading font-extrabold text-2xl tracking-tight">Streetwear Women</h3>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('otp-container');
    if (!container) return;
    
    container.addEventListener('input', function(e) {
        if (e.target.classList.contains('otp-input')) {
            if (e.target.value.length === 1) {
                let inputs = Array.from(document.querySelectorAll('.otp-input'));
                let idx = inputs.indexOf(e.target);
                if (idx !== -1 && idx < inputs.length - 1) {
                    inputs[idx + 1].focus();
                }
            }
        }
    });

    container.addEventListener('keydown', function(e) {
        if (e.target.classList.contains('otp-input') && e.key === 'Backspace') {
            if (e.target.value === '') {
                let inputs = Array.from(document.querySelectorAll('.otp-input'));
                let idx = inputs.indexOf(e.target);
                if (idx > 0) {
                    inputs[idx - 1].focus();
                }
            }
        }
    });
});
</script>
@endpush

@endsection
