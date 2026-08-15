@extends('frontend.layouts.app')
@section('title', 'Contact Us — ThreadAX')
@section('meta_description', 'Get in touch with ThreadAX support for inquiries about orders, sizing, shipping, or returns.')

@section('content')
<div class="bg-brand-white min-h-screen">

    {{-- ═══════════ BREADCRUMB & HERO ═══════════ --}}
    <section class="border-b border-brand-border bg-brand-off-white/60 py-10 lg:py-14">
        <div class="max-w-[1240px] mx-auto px-4 lg:px-8">
            {{-- Breadcrumbs --}}
            <nav class="flex items-center gap-2 text-xs text-brand-muted mb-4 font-medium" aria-label="Breadcrumb">
                <a href="{{ route('frontend.home') }}" class="hover:text-brand-dark transition-colors">Home</a>
                <svg class="w-3.5 h-3.5 text-brand-muted/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-brand-dark font-semibold">Contact Support</span>
            </nav>

            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-dark text-white text-[11px] font-bold tracking-wider uppercase mb-4">
                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    Direct Support Desk
                </div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-heading font-extrabold text-brand-dark tracking-tight mb-3">
                    Contact Us
                </h1>
                <p class="text-sm sm:text-base text-brand-muted leading-relaxed">
                    Have questions about your order, sizing recommendations, or tracking? Reach out to our crew below.
                </p>
            </div>
        </div>
    </section>

    {{-- ═══════════ MAIN CONTENT ═══════════ --}}
    <section class="py-12 lg:py-16 bg-brand-off-white/40">
        <div class="max-w-[1240px] mx-auto px-4 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
                
                {{-- Contact Information Cards (Left Column) --}}
                <div class="lg:col-span-5 space-y-4">
                    <h2 class="text-base sm:text-lg font-heading font-extrabold text-brand-dark uppercase tracking-wider mb-2">
                        Get In Touch
                    </h2>
                    
                    {{-- Email Card --}}
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-brand-border flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-brand-dark text-white flex items-center justify-center shrink-0 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-brand-dark text-sm mb-0.5">Email Support</h3>
                            <p class="text-brand-muted text-xs mb-2">We typically reply within 2 to 4 business hours.</p>
                            <a href="mailto:support@threadax.co.in" class="text-brand-dark font-bold text-xs hover:underline flex items-center gap-1">
                                support@threadax.co.in
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </div>

                    {{-- WhatsApp Card --}}
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-brand-border flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-[#25D366]/15 text-[#128C7E] flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-brand-dark text-sm mb-0.5">WhatsApp Chat</h3>
                            <p class="text-brand-muted text-xs mb-2">Instant messaging support during business hours.</p>
                            <a href="https://wa.me/919999999999" target="_blank" class="text-emerald-600 font-bold text-xs hover:underline flex items-center gap-1">
                                Chat on WhatsApp
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </div>

                    {{-- Business Hours --}}
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-brand-border flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-brand-light text-brand-dark flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-brand-dark text-sm mb-0.5">Operational Hours</h3>
                            <p class="text-brand-muted text-xs">Mon – Sat: 10:00 AM – 7:00 PM IST<br>Sunday & National Holidays: Closed</p>
                        </div>
                    </div>
                </div>

                {{-- Contact Form (Right Column) --}}
                <div class="lg:col-span-7">
                    <div class="bg-white rounded-2xl shadow-sm border border-brand-border p-6 sm:p-8 relative overflow-hidden" 
                         x-data="{
                            submitting: false,
                            submitted: false,
                            errors: {},
                            formData: {
                                first_name: '',
                                last_name: '',
                                email: '',
                                phone: '',
                                order_number: '',
                                message: ''
                            },
                            submitForm() {
                                this.submitting = true;
                                this.errors = {};
                                
                                fetch('{{ route('frontend.contact.submit') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify(this.formData)
                                })
                                .then(response => response.json().then(data => ({status: response.status, body: data})))
                                .then(res => {
                                    if (res.status === 422) {
                                        this.errors = res.body.errors;
                                        this.submitting = false;
                                    } else if (res.status === 200) {
                                        this.submitted = true;
                                    } else {
                                        this.submitting = false;
                                        if (window.showToast) window.showToast('Something went wrong. Please try again later.', 'error');
                                    }
                                })
                                .catch(err => {
                                    this.submitting = false;
                                    if (window.showToast) window.showToast('A network error occurred. Please try again.', 'error');
                                });
                            }
                         }">
                         
                        {{-- Form State --}}
                        <div x-show="!submitted">
                            <h2 class="text-lg sm:text-xl font-heading font-extrabold text-brand-dark mb-1">
                                Send a Direct Message
                            </h2>
                            <p class="text-xs text-brand-muted mb-6">Fill out the details below and we will get back to you promptly.</p>

                            <form @submit.prevent="submitForm" class="space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-brand-dark uppercase tracking-wider mb-1.5">First Name</label>
                                        <input type="text" x-model="formData.first_name" class="w-full px-3.5 py-2.5 bg-brand-off-white border border-brand-border rounded-xl text-xs sm:text-sm text-brand-dark placeholder-brand-muted focus:outline-none focus:border-brand-dark focus:bg-white transition-all" :class="errors.first_name ? 'border-red-500' : ''" placeholder="John" required>
                                        <template x-if="errors.first_name">
                                            <p class="text-red-500 text-[11px] mt-1" x-text="errors.first_name[0]"></p>
                                        </template>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-brand-dark uppercase tracking-wider mb-1.5">Last Name</label>
                                        <input type="text" x-model="formData.last_name" class="w-full px-3.5 py-2.5 bg-brand-off-white border border-brand-border rounded-xl text-xs sm:text-sm text-brand-dark placeholder-brand-muted focus:outline-none focus:border-brand-dark focus:bg-white transition-all" :class="errors.last_name ? 'border-red-500' : ''" placeholder="Doe" required>
                                        <template x-if="errors.last_name">
                                            <p class="text-red-500 text-[11px] mt-1" x-text="errors.last_name[0]"></p>
                                        </template>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-brand-dark uppercase tracking-wider mb-1.5">Email Address <span class="text-rose-500">*</span></label>
                                        <input type="email" x-model="formData.email" class="w-full px-3.5 py-2.5 bg-brand-off-white border border-brand-border rounded-xl text-xs sm:text-sm text-brand-dark placeholder-brand-muted focus:outline-none focus:border-brand-dark focus:bg-white transition-all" :class="errors.email ? 'border-red-500' : ''" placeholder="john@example.com" required>
                                        <template x-if="errors.email">
                                            <p class="text-red-500 text-[11px] mt-1" x-text="errors.email[0]"></p>
                                        </template>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-brand-dark uppercase tracking-wider mb-1.5">Mobile / WhatsApp Number</label>
                                        <input type="tel" x-model="formData.phone" class="w-full px-3.5 py-2.5 bg-brand-off-white border border-brand-border rounded-xl text-xs sm:text-sm text-brand-dark placeholder-brand-muted focus:outline-none focus:border-brand-dark focus:bg-white transition-all" :class="errors.phone ? 'border-red-500' : ''" placeholder="e.g. 9876543210">
                                        <template x-if="errors.phone">
                                            <p class="text-red-500 text-[11px] mt-1" x-text="errors.phone[0]"></p>
                                        </template>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-brand-dark uppercase tracking-wider mb-1.5">Order Number (Optional)</label>
                                    <input type="text" x-model="formData.order_number" class="w-full px-3.5 py-2.5 bg-brand-off-white border border-brand-border rounded-xl text-xs sm:text-sm text-brand-dark placeholder-brand-muted focus:outline-none focus:border-brand-dark focus:bg-white transition-all" placeholder="e.g. THX-102938">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-brand-dark uppercase tracking-wider mb-1.5">Your Message</label>
                                    <textarea rows="4" x-model="formData.message" class="w-full px-3.5 py-2.5 bg-brand-off-white border border-brand-border rounded-xl text-xs sm:text-sm text-brand-dark placeholder-brand-muted focus:outline-none focus:border-brand-dark focus:bg-white transition-all resize-none" :class="errors.message ? 'border-red-500' : ''" placeholder="How can our support crew assist you?" required></textarea>
                                    <template x-if="errors.message">
                                        <p class="text-red-500 text-[11px] mt-1" x-text="errors.message[0]"></p>
                                    </template>
                                </div>

                                <div class="pt-2">
                                    <button type="submit" :disabled="submitting" class="btn-primary w-full !py-3 text-xs sm:text-sm font-semibold flex justify-center items-center gap-2 disabled:opacity-50">
                                        <span x-show="!submitting">Submit Message</span>
                                        <span x-show="submitting">Transmitting...</span>
                                        <svg x-show="!submitting" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- Success State --}}
                        <div x-cloak x-show="submitted" 
                             x-transition:enter="transition ease-out duration-500" 
                             x-transition:enter-start="opacity-0 scale-95" 
                             x-transition:enter-end="opacity-100 scale-100"
                             class="py-12 flex flex-col items-center text-center">
                            
                            <div class="w-16 h-16 rounded-full bg-brand-dark text-white flex items-center justify-center shadow-lg mb-5">
                                <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            
                            <h3 class="text-xl sm:text-2xl font-heading font-extrabold text-brand-dark mb-2">Message Sent!</h3>
                            <p class="text-xs sm:text-sm text-brand-muted max-w-sm mb-6 leading-relaxed">
                                Thank you, <span x-text="formData.first_name" class="font-bold text-brand-dark"></span>. Our support crew will review your ticket and reply to you at <span x-text="formData.email" class="font-bold text-brand-dark"></span> shortly.
                            </p>
                            
                            <button @click="submitted = false; formData = {first_name: '', last_name: '', email: '', order_number: '', message: ''}" class="btn-outline !py-2 !px-5 text-xs font-semibold">
                                Send Another Message
                            </button>
                        </div>

                        <style>
                            [x-cloak] { display: none !important; }
                        </style>

                    </div>
                </div>

            </div>
        </div>
    </section>

</div>
@endsection
