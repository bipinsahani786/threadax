@extends('frontend.layouts.app')

@section('title', 'Login — ThreadAX')
@section('meta_description', 'Login to your ThreadAX account. Passwordless login with email OTP or Google.')

@section('content')

<div x-data="authFlow()" class="relative w-full min-h-[calc(100vh-130px)] flex items-stretch overflow-hidden bg-white">

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

    {{-- Center Login & OTP Card (Full Height Top-to-Bottom, Touches Left & Right Images directly) --}}
    <div class="w-full lg:w-[500px] xl:w-[540px] shrink-0 bg-white flex flex-col justify-center px-6 sm:px-10 py-10 relative z-10 border-x border-brand-border shadow-2xl">
        
        {{-- STEP 1: EMAIL INPUT --}}
        <div x-show="step === 'email'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 -translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
            
            {{-- Branding & Heading --}}
            <div class="text-center mb-8">
                <a href="{{ route('frontend.home') }}" class="inline-flex items-center gap-1 font-heading text-2xl font-black tracking-tight mb-4">
                    <span class="text-brand-text">THREAD</span><span class="threadax-logo-box text-white">AX</span>
                </a>
                <h1 class="font-heading text-3xl font-extrabold text-brand-text tracking-tight mb-1">Welcome Back</h1>
                <p class="text-brand-muted text-sm">Enter your email address to receive a 6-digit OTP</p>
            </div>

            {{-- Dynamic Error Alert --}}
            <template x-if="errorMessage">
                <div class="mb-6 px-4 py-3 rounded-xl text-sm bg-red-50 border border-red-200 text-red-600 flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span x-text="errorMessage"></span>
                </div>
            </template>

            {{-- Form --}}
            <form @submit.prevent="sendOtp" class="space-y-5">
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-widest text-brand-muted mb-2">
                        Email Address
                    </label>
                    <input
                        type="email"
                        id="email"
                        x-model="email"
                        placeholder="you@example.com"
                        required
                        autofocus
                        class="w-full px-4 py-3.5 border border-brand-border rounded-xl text-sm text-brand-text bg-brand-light placeholder-brand-muted focus:outline-none focus:border-brand-text focus:bg-white transition-all"
                    >
                </div>

                <button type="submit" :disabled="loading" class="btn-primary w-full text-center text-sm uppercase tracking-wider py-4 rounded-xl font-bold flex items-center justify-center gap-2 group disabled:opacity-50">
                    <template x-if="!loading">
                        <div class="flex items-center gap-2">
                            <span>Send OTP</span>
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </div>
                    </template>
                    <template x-if="loading">
                        <div class="flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Sending OTP...</span>
                        </div>
                    </template>
                </button>
            </form>

            {{-- Divider --}}
            <div class="flex items-center gap-4 my-6">
                <div class="flex-1 h-px bg-brand-border"></div>
                <span class="text-[10px] text-brand-muted uppercase tracking-widest font-bold">Or Continue With</span>
                <div class="flex-1 h-px bg-brand-border"></div>
            </div>

            {{-- Google Button --}}
            <a href="{{ route('auth.google.redirect') }}" id="google-login-btn"
               class="flex items-center justify-center gap-3 w-full py-3.5 rounded-xl text-sm font-semibold text-brand-text border border-brand-border hover:border-brand-text hover:bg-brand-light transition-all">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                <span>Google Account</span>
            </a>

            {{-- Terms --}}
            <p class="text-brand-muted text-xs text-center mt-8">
                By logging in, you agree to our
                <a href="#" class="underline font-medium hover:text-brand-text">Terms</a> &
                <a href="#" class="underline font-medium hover:text-brand-text">Privacy Policy</a>
            </p>
        </div>

        {{-- STEP 2: OTP VERIFY --}}
        <div x-show="step === 'otp'" x-cloak x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
            
            {{-- Branding & Heading --}}
            <div class="text-center mb-6">
                <a href="{{ route('frontend.home') }}" class="inline-flex items-center gap-1 font-heading text-2xl font-black tracking-tight mb-3">
                    <span class="text-brand-text">THREAD</span><span class="threadax-logo-box text-white">AX</span>
                </a>
                <h1 class="font-heading text-3xl font-extrabold text-brand-text tracking-tight mb-1">Check Your Email</h1>
                <p class="text-brand-muted text-sm">
                    We sent a 6-digit verification code to<br>
                    <span class="font-bold text-brand-text break-all" x-text="email"></span>
                </p>
            </div>

            {{-- Dynamic Alerts --}}
            <template x-if="successMessage">
                <div class="mb-5 px-4 py-3 rounded-xl text-sm font-medium bg-green-50 border border-green-200 text-green-700 flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span x-text="successMessage"></span>
                </div>
            </template>

            <template x-if="errorMessage">
                <div class="mb-5 px-4 py-3 rounded-xl text-sm bg-red-50 border border-red-200 text-red-600 flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span x-text="errorMessage"></span>
                </div>
            </template>

            {{-- OTP Form with 6-Box Grid --}}
            <form @submit.prevent="verifyOtp" class="space-y-6" @paste="handleOtpPaste($event)">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-brand-muted mb-3 text-center">
                        Enter 6-Digit Code
                    </label>
                    
                    {{-- 6 Premium Individual Digit Boxes --}}
                    <div class="flex items-center justify-center gap-2 sm:gap-3 w-full" id="otp-boxes-container">
                        <template x-for="index in [0,1,2,3,4,5]" :key="index">
                            <input
                                type="text"
                                maxlength="1"
                                inputmode="numeric"
                                pattern="[0-9]"
                                :id="'otp-box-' + index"
                                x-model="otpDigits[index]"
                                @input="handleOtpInput(index, $event)"
                                @keydown.backspace="handleOtpBackspace(index, $event)"
                                @focus="$el.select()"
                                class="w-12 h-14 sm:w-14 sm:h-16 text-2xl sm:text-3xl font-black font-heading text-center text-brand-text bg-brand-light border-2 border-brand-border rounded-2xl focus:border-brand-text focus:bg-white focus:outline-none focus:ring-4 focus:ring-brand-text/10 transition-all duration-200 shadow-sm caret-brand-text"
                                autocomplete="one-time-code"
                            >
                        </template>
                    </div>

                    <p class="text-brand-muted text-xs text-center mt-3">Code expires in 10 minutes.</p>
                </div>

                <button type="submit" :disabled="loading" class="btn-primary w-full text-center text-sm uppercase tracking-wider py-4 rounded-xl font-bold flex items-center justify-center gap-2 group disabled:opacity-50">
                    <template x-if="!loading">
                        <div class="flex items-center gap-2">
                            <span>Verify & Login</span>
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </div>
                    </template>
                    <template x-if="loading">
                        <div class="flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Verifying Code...</span>
                        </div>
                    </template>
                </button>
            </form>

            {{-- Resend & Change Email Actions --}}
            <div class="flex items-center justify-center gap-4 mt-6 text-sm text-brand-muted">
                <button type="button" @click="resendOtp" :disabled="loading" class="font-bold text-brand-text hover:underline cursor-pointer">
                    Resend Code
                </button>
                <span class="text-brand-border">·</span>
                <button type="button" @click="changeEmail" class="font-medium hover:text-brand-text hover:underline cursor-pointer">
                    Change email
                </button>
            </div>

            {{-- Terms --}}
            <p class="text-brand-muted text-xs text-center mt-8">
                By logging in, you agree to our
                <a href="#" class="underline font-medium hover:text-brand-text">Terms</a> &
                <a href="#" class="underline font-medium hover:text-brand-text">Privacy Policy</a>
            </p>

        </div>

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

<script>
    function authFlow() {
        return {
            step: 'email',
            email: '',
            otpDigits: ['', '', '', '', '', ''],
            loading: false,
            errorMessage: '',
            successMessage: '',

            get code() {
                return this.otpDigits.join('');
            },

            async sendOtp() {
                if (!this.email) return;
                this.loading = true;
                this.errorMessage = '';
                this.successMessage = '';

                try {
                    const res = await fetch('{{ route("auth.otp.send") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ email: this.email })
                    });

                    const data = await res.json();
                    if (res.ok && data.success) {
                        this.successMessage = data.message;
                        this.step = 'otp';
                        this.$nextTick(() => {
                            document.getElementById('otp-box-0')?.focus();
                        });
                    } else {
                        this.errorMessage = data.message || 'Failed to send OTP.';
                    }
                } catch (e) {
                    this.errorMessage = 'Connection error. Please try again.';
                } finally {
                    this.loading = false;
                }
            },

            handleOtpInput(index, e) {
                const val = e.target.value.replace(/[^0-9]/g, '');
                this.otpDigits[index] = val ? val.slice(-1) : '';
                e.target.value = this.otpDigits[index];
                if (val && index < 5) {
                    this.$nextTick(() => document.getElementById('otp-box-' + (index + 1))?.focus());
                }
            },

            handleOtpBackspace(index, e) {
                if (!this.otpDigits[index] && index > 0) {
                    this.otpDigits[index - 1] = '';
                    this.$nextTick(() => document.getElementById('otp-box-' + (index - 1))?.focus());
                } else {
                    this.otpDigits[index] = '';
                }
                e.target.value = '';
            },

            handleOtpPaste(e) {
                e.preventDefault();
                const pasted = (e.clipboardData || window.clipboardData).getData('text').trim().replace(/[^0-9]/g, '');
                if (pasted.length >= 1) {
                    for (let i = 0; i < 6 && i < pasted.length; i++) {
                        this.otpDigits[i] = pasted[i];
                    }
                    const focusIdx = Math.min(pasted.length, 5);
                    this.$nextTick(() => document.getElementById('otp-box-' + focusIdx)?.focus());
                }
            },

            async verifyOtp() {
                if (this.code.length !== 6) {
                    this.errorMessage = 'Please enter all 6 digits of the OTP code.';
                    return;
                }
                this.loading = true;
                this.errorMessage = '';

                try {
                    const res = await fetch('{{ route("auth.otp.verify") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ email: this.email, code: this.code })
                    });

                    const data = await res.json();
                    if (res.ok && data.success) {
                        this.successMessage = 'Login successful! Redirecting...';
                        setTimeout(() => {
                            window.location.href = data.redirect || '{{ route("frontend.home") }}';
                        }, 400);
                    } else {
                        this.errorMessage = data.message || 'Invalid or expired OTP code.';
                    }
                } catch (e) {
                    this.errorMessage = 'Connection error. Please try again.';
                } finally {
                    this.loading = false;
                }
            },

            async resendOtp() {
                this.loading = true;
                this.errorMessage = '';
                try {
                    const res = await fetch('{{ route("auth.otp.send") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ email: this.email })
                    });
                    const data = await res.json();
                    if (res.ok && data.success) {
                        this.successMessage = 'A new 6-digit OTP code has been sent!';
                    } else {
                        this.errorMessage = data.message || 'Failed to resend OTP.';
                    }
                } catch (e) {
                    this.errorMessage = 'Connection error.';
                } finally {
                    this.loading = false;
                }
            },

            changeEmail() {
                this.step = 'email';
                this.otpDigits = ['', '', '', '', '', ''];
                this.errorMessage = '';
                this.successMessage = '';
            }
        };
    }
</script>

@endsection
