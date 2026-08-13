@extends('frontend.layouts.app')
@section('title', 'Track Your Order — ThreadAX')

@section('content')
<section class="bg-brand-off-white min-h-[70vh] flex items-center justify-center py-20 relative">
    {{-- Decorative Background --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 right-0 w-[40rem] h-[40rem] bg-brand-light rounded-full blur-[100px] opacity-50 translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 left-0 w-[30rem] h-[30rem] bg-brand-border rounded-full blur-[80px] opacity-30 -translate-x-1/2 translate-y-1/2"></div>
    </div>

    <div class="max-w-2xl w-full mx-auto px-4 relative z-10">
        <div class="bg-white rounded-3xl shadow-[0_20px_50px_rgba(23,23,23,0.05)] border border-brand-border p-8 md:p-16">
            <div class="text-center mb-10">
                <div class="w-16 h-16 bg-brand-dark rounded-full flex items-center justify-center text-white mx-auto mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h1 class="text-3xl md:text-4xl font-heading font-extrabold text-brand-dark uppercase tracking-tight mb-3">Track Your Order</h1>
                <p class="text-brand-muted">Enter your order details below to see the real-time shipping status.</p>
            </div>

            <form action="#" method="GET" class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-brand-dark mb-2">Order ID</label>
                    <input type="text" class="input-field bg-brand-off-white text-lg py-4" placeholder="e.g. THX-12345678" required>
                    <p class="text-xs text-brand-muted mt-2">Find this in your order confirmation email.</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-brand-dark mb-2">Email or Phone Number</label>
                    <input type="text" class="input-field bg-brand-off-white text-lg py-4" placeholder="Enter email or phone used at checkout" required>
                </div>

                <button type="submit" class="btn-primary w-full py-4 text-lg mt-4 flex items-center justify-center gap-2">
                    Track Package
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>
        </div>
        
        <p class="text-center text-brand-muted text-sm mt-8">
            Having trouble finding your order? <a href="{{ route('frontend.page.show', 'contact') }}" class="text-brand-dark font-bold underline">Contact Support</a>
        </p>
    </div>
</section>
@endsection
