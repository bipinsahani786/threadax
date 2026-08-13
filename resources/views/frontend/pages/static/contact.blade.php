@extends('frontend.layouts.app')
@section('title', 'Contact Us — ThreadAX')

@section('content')
{{-- Clean Hero Section --}}
<section class="bg-brand-dark py-16 lg:py-24 flex items-center justify-center relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-white via-brand-dark to-brand-dark"></div>
    <div class="relative z-10 text-center px-4 max-w-3xl mx-auto">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-heading font-extrabold text-white mb-6 uppercase tracking-tight">
            Contact Support
        </h1>
        <p class="text-lg text-white/70">
            Have a question about your order, sizing, or our products? We're here to help.
        </p>
    </div>
</section>

<section class="py-20 lg:py-32 bg-brand-off-white">
    <div class="max-w-[1200px] mx-auto px-4 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-24">
            
            {{-- Contact Information --}}
            <div class="lg:col-span-5 space-y-8">
                <h2 class="text-3xl font-heading font-extrabold text-brand-dark mb-8">Get In Touch</h2>
                
                {{-- Email Card --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-brand-border flex items-start gap-4">
                    <div class="w-12 h-12 bg-brand-light rounded-full flex items-center justify-center text-brand-dark shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-brand-dark text-lg mb-1">Email Us</h3>
                        <p class="text-brand-muted text-sm mb-2">We typically reply within 24 hours.</p>
                        <a href="mailto:support@threadax.co.in" class="text-brand-text font-bold hover:underline">support@threadax.co.in</a>
                    </div>
                </div>

                {{-- WhatsApp Card --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-brand-border flex items-start gap-4">
                    <div class="w-12 h-12 bg-[#25D366]/10 rounded-full flex items-center justify-center text-[#25D366] shrink-0">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-brand-dark text-lg mb-1">WhatsApp Chat</h3>
                        <p class="text-brand-muted text-sm mb-2">Instant support during business hours.</p>
                        <a href="#" class="text-[#25D366] font-bold hover:underline">Chat Now</a>
                    </div>
                </div>

                {{-- Business Hours --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-brand-border flex items-start gap-4">
                    <div class="w-12 h-12 bg-brand-light rounded-full flex items-center justify-center text-brand-dark shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-brand-dark text-lg mb-1">Business Hours</h3>
                        <p class="text-brand-muted text-sm">Mon - Sat: 10:00 AM – 7:00 PM IST<br>Sunday: Closed</p>
                    </div>
                </div>
            </div>

            {{-- Contact Form --}}
            <div class="lg:col-span-7">
                <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-brand-border p-8 md:p-12 relative overflow-hidden" 
                     x-data="{
                        submitting: false,
                        submitted: false,
                        errors: {},
                        formData: {
                            first_name: '',
                            last_name: '',
                            email: '',
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
                                    alert('Something went wrong. Please try again later.');
                                }
                            })
                            .catch(err => {
                                this.submitting = false;
                                alert('A network error occurred. Please try again.');
                            });
                        }
                     }">
                     
                    {{-- Form State --}}
                    <div x-show="!submitted" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                        <h2 class="text-2xl font-heading font-extrabold text-brand-dark mb-6">Send a Message</h2>
                        <form @submit.prevent="submitForm" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-brand-dark mb-2">First Name</label>
                                    <input type="text" x-model="formData.first_name" class="input-field bg-brand-off-white" :class="errors.first_name ? 'border-red-500' : ''" placeholder="John" required>
                                    <template x-if="errors.first_name">
                                        <p class="text-red-500 text-xs mt-1" x-text="errors.first_name[0]"></p>
                                    </template>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-brand-dark mb-2">Last Name</label>
                                    <input type="text" x-model="formData.last_name" class="input-field bg-brand-off-white" :class="errors.last_name ? 'border-red-500' : ''" placeholder="Doe" required>
                                    <template x-if="errors.last_name">
                                        <p class="text-red-500 text-xs mt-1" x-text="errors.last_name[0]"></p>
                                    </template>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-brand-dark mb-2">Email Address</label>
                                <input type="email" x-model="formData.email" class="input-field bg-brand-off-white" :class="errors.email ? 'border-red-500' : ''" placeholder="john@example.com" required>
                                <template x-if="errors.email">
                                    <p class="text-red-500 text-xs mt-1" x-text="errors.email[0]"></p>
                                </template>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-brand-dark mb-2">Order Number (Optional)</label>
                                <input type="text" x-model="formData.order_number" class="input-field bg-brand-off-white" :class="errors.order_number ? 'border-red-500' : ''" placeholder="#THX-12345">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-brand-dark mb-2">Message</label>
                                <textarea rows="5" x-model="formData.message" class="input-field bg-brand-off-white resize-none" :class="errors.message ? 'border-red-500' : ''" placeholder="How can we help you?" required></textarea>
                                <template x-if="errors.message">
                                    <p class="text-red-500 text-xs mt-1" x-text="errors.message[0]"></p>
                                </template>
                            </div>

                            <button type="submit" :disabled="submitting" class="btn-primary w-full flex justify-center items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span x-show="!submitting">Send Message</span>
                                <span x-show="submitting">Sending...</span>
                                <svg x-show="!submitting" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                <svg x-show="submitting" class="w-5 h-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </button>
                        </form>
                    </div>

                    {{-- Success State (Premium & Animated - ThreadAX Light Theme) --}}
                    <div x-cloak x-show="submitted" 
                         x-transition:enter="transition ease-out duration-1000 delay-300" 
                         x-transition:enter-start="opacity-0 scale-95" 
                         x-transition:enter-end="opacity-100 scale-100"
                         class="w-full h-full min-h-[400px] flex flex-col items-center justify-center bg-white rounded-3xl p-8 md:p-12 text-center relative overflow-hidden border border-brand-border shadow-sm">
                        
                        {{-- Subtle Background Pattern --}}
                        <div class="absolute inset-0 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:16px_16px] opacity-30"></div>
                        
                        <div class="relative z-10 flex flex-col items-center">
                            {{-- ThreadAX Branding --}}
                            <div class="mb-6 font-heading font-extrabold text-brand-dark tracking-widest text-sm uppercase opacity-50">
                                ThreadAX Support
                            </div>

                            {{-- Premium Checkmark Animation (Minimalist Black) --}}
                            <div class="relative w-28 h-28 mb-8 flex items-center justify-center">
                                {{-- Outer pulsing ring --}}
                                <div class="absolute inset-0 border border-brand-dark/20 rounded-full animate-[ping_2.5s_cubic-bezier(0,0,0.2,1)_infinite]"></div>
                                
                                {{-- Solid Background Circle --}}
                                <div class="w-20 h-20 bg-brand-dark rounded-full flex items-center justify-center shadow-lg transform hover:scale-110 transition-transform duration-500">
                                    {{-- White SVG Checkmark --}}
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="24" stroke-dashoffset="24" class="animate-[stroke_0.6s_ease-out_0.5s_forwards]"></path>
                                    </svg>
                                </div>
                            </div>
                            
                            <h2 class="text-4xl md:text-5xl font-heading font-extrabold text-brand-dark mb-4 tracking-tight">Message Sent!</h2>
                            
                            <p class="text-brand-muted text-lg md:text-xl max-w-md mx-auto mb-8 font-medium leading-relaxed">
                                Thank you, <span x-text="formData.first_name" class="text-brand-dark font-bold"></span>. We've received your request and our team will reach out to you at <br>
                                <span x-text="formData.email" class="text-brand-dark font-bold mt-2 inline-block bg-brand-off-white px-5 py-2 rounded-xl border border-brand-border shadow-sm"></span>
                            </p>
                            
                            <button @click="submitted = false; formData = {first_name: '', last_name: '', email: '', order_number: '', message: ''}" class="btn-primary hover:scale-105 transition-all shadow-xl">
                                Send Another Message
                            </button>
                        </div>
                    </div>

                    <style>
                        @keyframes stroke {
                            to { stroke-dashoffset: 0; }
                        }
                        [x-cloak] { display: none !important; }
                    </style>

                </div>
            </div>

        </div>
    </div>
</section>

@endsection
