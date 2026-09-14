@extends('frontend.layouts.app')
@section('title', 'Frequently Asked Questions (FAQ) — ThreadAX')
@section('meta_description', 'Find answers to frequently asked questions about ThreadAX oversized streetwear, shipping timelines, returns, sizing guides, and payment options.')

@section('content')
<div class="bg-brand-white min-h-screen" x-data="{
    searchQuery: '',
    selectedCategory: 'all',
    openFaq: 1,
    matches(faqCategory, question, answer) {
        const query = this.searchQuery.toLowerCase().trim();
        const matchesCategory = this.selectedCategory === 'all' || faqCategory === this.selectedCategory;
        if (!matchesCategory) return false;
        if (!query) return true;
        return question.toLowerCase().includes(query) || answer.toLowerCase().includes(query);
    }
}">

    {{-- ═══════════ BREADCRUMB & HERO ═══════════ --}}
    <section class="border-b border-brand-border bg-brand-off-white/60 py-6 sm:py-10 lg:py-14">
        <div class="max-w-[1240px] mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Breadcrumbs --}}
            <nav class="flex items-center gap-2 text-xs text-brand-muted mb-3 sm:mb-4 font-medium" aria-label="Breadcrumb">
                <a href="{{ route('frontend.home') }}" class="hover:text-brand-dark transition-colors">Home</a>
                <svg class="w-3.5 h-3.5 text-brand-muted/60 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-brand-dark font-semibold truncate">Help & FAQ</span>
            </nav>

            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brand-dark text-white text-[11px] font-bold tracking-wider uppercase mb-3">
                    <svg class="w-3.5 h-3.5 text-brand-accent shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    ThreadAX Help Center
                </div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-heading font-extrabold text-brand-dark tracking-tight mb-2 sm:mb-3">
                    Frequently Asked Questions
                </h1>
                <p class="text-xs sm:text-sm md:text-base text-brand-muted leading-relaxed">
                    Have questions regarding orders, sizing, fabrics, returns, or deliveries? Browse our curated guide or search directly below.
                </p>
            </div>

            {{-- Live Search Input --}}
            <div class="mt-5 sm:mt-6 max-w-xl relative">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-brand-muted">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input 
                        type="text" 
                        x-model="searchQuery" 
                        placeholder="Search questions (e.g. sizing, return window, shipping time, UPI)..." 
                        class="w-full pl-10 pr-9 py-2.5 sm:py-3 bg-white border border-brand-border rounded-xl text-xs sm:text-sm text-brand-dark placeholder-brand-muted focus:outline-none focus:border-brand-dark focus:ring-1 focus:ring-brand-dark shadow-sm transition-all"
                    >
                    <button 
                        x-show="searchQuery.length > 0" 
                        @click="searchQuery = ''" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-brand-muted hover:text-brand-dark"
                        style="display: none;"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════ CATEGORY TABS (Scrollable on Mobile with Cues) ═══════════ --}}
    <section class="border-b border-brand-border bg-white sticky top-0 z-20 backdrop-blur-md bg-white/95 shadow-[0_2px_8px_rgba(0,0,0,0.02)]"
             x-data="{
                canScrollLeft: false,
                canScrollRight: true,
                checkFaqScroll() {
                    const el = this.$refs.faqTabContainer;
                    if (!el) return;
                    this.canScrollLeft = el.scrollLeft > 10;
                    this.canScrollRight = el.scrollLeft < (el.scrollWidth - el.clientWidth - 10);
                }
             }"
             x-init="$nextTick(() => checkFaqScroll())">
        <div class="max-w-[1240px] mx-auto px-4 sm:px-6 lg:px-8 relative">
            
            {{-- Left Fade Indicator --}}
            <div x-show="canScrollLeft" 
                 x-transition
                 class="absolute left-0 top-0 bottom-0 z-10 w-8 bg-gradient-to-r from-white via-white/80 to-transparent pointer-events-none sm:hidden"></div>

            <div x-ref="faqTabContainer"
                 @scroll.passive="checkFaqScroll()"
                 class="flex items-center gap-1.5 sm:gap-2 overflow-x-auto py-2.5 sm:py-3 no-scrollbar text-xs font-semibold scroll-smooth">
                <button 
                    @click="selectedCategory = 'all'; $nextTick(() => checkFaqScroll())" 
                    :class="selectedCategory === 'all' ? 'bg-brand-dark text-white shadow-sm' : 'bg-brand-off-white text-brand-muted hover:text-brand-dark border border-brand-border'" 
                    class="px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-lg shrink-0 transition-all text-xs cursor-pointer"
                >
                    All Questions
                </button>
                <button 
                    @click="selectedCategory = 'orders'; $nextTick(() => checkFaqScroll())" 
                    :class="selectedCategory === 'orders' ? 'bg-brand-dark text-white shadow-sm' : 'bg-brand-off-white text-brand-muted hover:text-brand-dark border border-brand-border'" 
                    class="px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-lg shrink-0 transition-all text-xs cursor-pointer"
                >
                    Orders & Shipping
                </button>
                <button 
                    @click="selectedCategory = 'returns'; $nextTick(() => checkFaqScroll())" 
                    :class="selectedCategory === 'returns' ? 'bg-brand-dark text-white shadow-sm' : 'bg-brand-off-white text-brand-muted hover:text-brand-dark border border-brand-border'" 
                    class="px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-lg shrink-0 transition-all text-xs cursor-pointer"
                >
                    Returns & Refunds
                </button>
                <button 
                    @click="selectedCategory = 'sizing'; $nextTick(() => checkFaqScroll())" 
                    :class="selectedCategory === 'sizing' ? 'bg-brand-dark text-white shadow-sm' : 'bg-brand-off-white text-brand-muted hover:text-brand-dark border border-brand-border'" 
                    class="px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-lg shrink-0 transition-all text-xs cursor-pointer"
                >
                    Sizing & Fit
                </button>
                <button 
                    @click="selectedCategory = 'payments'; $nextTick(() => checkFaqScroll())" 
                    :class="selectedCategory === 'payments' ? 'bg-brand-dark text-white shadow-sm' : 'bg-brand-off-white text-brand-muted hover:text-brand-dark border border-brand-border'" 
                    class="px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-lg shrink-0 transition-all text-xs cursor-pointer"
                >
                    Payments & Offers
                </button>
            </div>

            {{-- Right Fade Indicator --}}
            <div x-show="canScrollRight" 
                 x-transition
                 class="absolute right-0 top-0 bottom-0 z-10 w-8 bg-gradient-to-l from-white via-white/80 to-transparent pointer-events-none sm:hidden"></div>

        </div>
    </section>

    {{-- ═══════════ MAIN FAQ ACCORDIONS & SIDEBAR ═══════════ --}}
    <section class="py-8 sm:py-12 lg:py-16 bg-brand-off-white/40">
        <div class="max-w-[1240px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-12 items-start">
                
                {{-- Left Column: FAQ Accordion List --}}
                <div class="lg:col-span-8 space-y-3 sm:space-y-4 order-1">

                    @php
                        // Default curated FAQ array with categorization
                        $curatedFaqs = [
                            [
                                'id' => 1,
                                'category' => 'orders',
                                'question' => 'How much does shipping cost and how long does delivery take?',
                                'answer' => 'We offer FREE shipping across India on all orders above ₹999. For orders below ₹999, a flat shipping charge of ₹79 applies. Delivery typically takes 2-4 business days for metro cities and 4-7 business days for the rest of India.'
                            ],
                            [
                                'id' => 2,
                                'category' => 'returns',
                                'question' => 'What is ThreadAX\'s return and exchange policy?',
                                'answer' => 'We have a 7-day hassle-free return and exchange policy from the date of delivery. Items must be unworn, unwashed, and have all original tags intact. We offer free doorstep pickup and full refunds back to your original payment method or UPI.'
                            ],
                            [
                                'id' => 3,
                                'category' => 'sizing',
                                'question' => 'How do I choose the right size for oversized t-shirts?',
                                'answer' => 'Our apparel is designed with a modern drop-shoulder, boxy oversized fit. If you want the true streetwear oversized drape, pick your regular t-shirt size. If you prefer a more tailored/regular fit, we suggest sizing down by one size. Check our detailed size chart on every product page.'
                            ],
                            [
                                'id' => 4,
                                'category' => 'sizing',
                                'question' => 'What fabric and GSM do ThreadAX garments use?',
                                'answer' => 'All ThreadAX t-shirts and hoodies are made from 100% combed heavyweight cotton (280GSM+ for tees, 400GSM+ for fleece hoodies). The fabric is pre-shrunk, bio-washed, and colorfast for maximum durability and premium texture.'
                            ],
                            [
                                'id' => 5,
                                'category' => 'orders',
                                'question' => 'How do I track my shipment once dispatched?',
                                'answer' => 'As soon as your package is dispatched, we send you an SMS and email with your AWB tracking number and direct tracking link. You can also visit our Track Order page and enter your Order ID anytime.'
                            ],
                            [
                                'id' => 6,
                                'category' => 'payments',
                                'question' => 'What payment methods do you accept?',
                                'answer' => 'We support all major payment modes through our secure 256-bit encrypted Razorpay gateway: UPI (Google Pay, PhonePe, Paytm), Credit/Debit Cards (Visa, MasterCard, RuPay), and NetBanking from 50+ Indian banks.'
                            ],
                            [
                                'id' => 7,
                                'category' => 'returns',
                                'question' => 'How long does it take to receive my refund?',
                                'answer' => 'Once our warehouse team receives your return and performs a quick quality inspection, refunds are processed within 3-5 business days directly to your original payment source or specified UPI ID.'
                            ],
                            [
                                'id' => 8,
                                'category' => 'orders',
                                'question' => 'Can I modify or cancel my order after placing it?',
                                'answer' => 'Orders can be modified or cancelled within 2 hours of placing them before they enter the warehouse packing stage. Please contact our support desk immediately at ' . ($globalSettings['contact_email'] ?? 'support@threadax.co.in') . ' or call ' . ($globalSettings['contact_phone'] ?? '+91 98765 43210') . ' / WhatsApp.'
                            ]
                        ];

                        // If DB faqs exist, merge or append them
                        if (isset($faqs) && $faqs->count() > 0) {
                            $dbList = [];
                            foreach ($faqs as $dbFaq) {
                                $dbList[] = [
                                    'id' => 100 + $dbFaq->id,
                                    'category' => 'all',
                                    'question' => $dbFaq->question,
                                    'answer' => $dbFaq->answer
                                ];
                            }
                            $curatedFaqs = array_merge($dbList, $curatedFaqs);
                        }
                    @endphp

                    @foreach($curatedFaqs as $faqItem)
                        <div 
                            x-show="matches('{{ $faqItem['category'] }}', '{{ addslashes($faqItem['question']) }}', '{{ addslashes(strip_tags($faqItem['answer'])) }}')"
                            x-transition
                            class="bg-white border border-brand-border rounded-2xl overflow-hidden shadow-sm hover:border-brand-dark/30 transition-all"
                        >
                            <button 
                                @click="openFaq = openFaq === {{ $faqItem['id'] }} ? null : {{ $faqItem['id'] }}" 
                                class="w-full flex items-center justify-between p-4 sm:p-5 text-left transition-colors cursor-pointer select-none gap-3"
                            >
                                <span class="text-xs sm:text-sm md:text-base font-heading font-bold text-brand-dark leading-snug">
                                    {{ $faqItem['question'] }}
                                </span>
                                <div 
                                    class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-brand-light flex items-center justify-center text-brand-dark shrink-0 transition-transform duration-300"
                                    :class="openFaq === {{ $faqItem['id'] }} ? 'rotate-180 bg-brand-dark text-white' : ''"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </button>
                            
                            <div 
                                x-show="openFaq === {{ $faqItem['id'] }}" 
                                x-collapse 
                                x-transition.duration.250ms
                            >
                                <div class="px-4 sm:px-5 pb-4 sm:pb-5 pt-1 text-xs sm:text-sm text-brand-muted leading-relaxed border-t border-brand-light mt-1">
                                    {!! nl2br(e($faqItem['answer'])) !!}
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{-- Empty Search Fallback --}}
                    <div 
                        x-show="searchQuery.length > 0"
                        class="p-6 sm:p-10 text-center bg-white rounded-2xl border border-brand-border"
                        style="display: none;"
                    >
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-brand-light text-brand-muted flex items-center justify-center mx-auto mb-3">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="font-heading font-bold text-sm text-brand-dark mb-1">No matching questions found</h3>
                        <p class="text-xs text-brand-muted mb-4">Can't find what you're looking for? Reach out to our customer support team directly.</p>
                        <button @click="searchQuery = ''; selectedCategory = 'all'" class="btn-outline !py-2 !px-4 text-xs font-semibold">
                            Reset Search Filter
                        </button>
                    </div>

                </div>

                {{-- Right Column: Quick Help Cards Sidebar --}}
                <div class="lg:col-span-4 lg:sticky lg:top-28 space-y-4 order-2">
                    
                    {{-- Still Have Questions Card --}}
                    <div class="p-5 sm:p-6 rounded-2xl border border-brand-border bg-white shadow-sm space-y-3 sm:space-y-4">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-brand-dark text-white flex items-center justify-center shrink-0 shadow-sm">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-sm sm:text-base text-brand-dark mb-0.5 sm:mb-1">Still have questions?</h3>
                            <p class="text-xs text-brand-muted leading-relaxed">
                                Our support crew is happy to help you with order inquiries, tracking, and sizing advice.
                            </p>
                        </div>

                        @php
                            $faqEmail = $globalSettings['contact_email'] ?? 'support@threadax.co.in';
                            $faqPhone = $globalSettings['contact_phone'] ?? '+91 98765 43210';
                            $faqCleanPhone = preg_replace('/[^0-9]/', '', $faqPhone);
                            $faqWa = $globalSettings['whatsapp_number'] ?? '919876543210';
                            $faqCleanWa = preg_replace('/[^0-9]/', '', $faqWa);
                        @endphp
                        <div class="space-y-2 pt-1 sm:pt-2">
                            {{-- Phone / Call Link --}}
                            <a href="tel:+{{ $faqCleanPhone }}" class="w-full flex items-center justify-between p-2.5 sm:p-3 rounded-xl border border-brand-border bg-brand-off-white hover:bg-brand-light transition-all text-xs font-bold text-brand-dark">
                                <span class="flex items-center gap-2 truncate">
                                    <span class="text-sm">📞</span>
                                    <span class="truncate">{{ $faqPhone }}</span>
                                </span>
                                <svg class="w-3.5 h-3.5 text-brand-muted shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>

                            {{-- WhatsApp Link --}}
                            <a href="https://wa.me/{{ $faqCleanWa }}?text={{ urlencode('Hello ThreadAX! I have a question.') }}" target="_blank" rel="noopener noreferrer" class="w-full flex items-center justify-between p-2.5 sm:p-3 rounded-xl border border-emerald-200 bg-emerald-50/50 hover:bg-emerald-50 transition-all text-xs font-bold text-emerald-800">
                                <span class="flex items-center gap-2 truncate">
                                    <span class="text-sm">💬</span>
                                    <span class="truncate">WhatsApp: +{{ $faqCleanWa }}</span>
                                </span>
                                <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>

                            {{-- Email Link --}}
                            <a href="mailto:{{ $faqEmail }}" class="w-full flex items-center justify-between p-2.5 sm:p-3 rounded-xl border border-brand-border bg-brand-off-white hover:bg-brand-light transition-all text-xs font-bold text-brand-dark">
                                <span class="flex items-center gap-2 truncate">
                                    <svg class="w-3.5 h-3.5 text-brand-muted shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    <span class="truncate">{{ $faqEmail }}</span>
                                </span>
                                <svg class="w-3.5 h-3.5 text-brand-muted shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>

                            {{-- Contact Us Form Link --}}
                            <a href="{{ route('frontend.page.show', 'contact') }}" class="w-full flex items-center justify-center gap-2 btn-primary !py-2.5 text-xs font-semibold">
                                Send Message via Contact Form
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </div>

                    {{-- Quick Useful Links --}}
                    <div class="p-5 sm:p-6 rounded-2xl border border-brand-border bg-brand-off-white/80 space-y-2.5 sm:space-y-3">
                        <h4 class="font-heading font-bold text-xs uppercase tracking-wider text-brand-dark">
                            Helpful Links
                        </h4>
                        <div class="space-y-1.5 text-xs">
                            <a href="{{ route('frontend.page.show', 'returns-exchanges') }}" class="flex items-center justify-between text-brand-muted hover:text-brand-dark transition-colors py-1">
                                <span>Returns & Exchange Policy</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            <a href="{{ route('frontend.page.show', 'shipping-info') }}" class="flex items-center justify-between text-brand-muted hover:text-brand-dark transition-colors py-1">
                                <span>Shipping & Delivery Timelines</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            <a href="{{ route('frontend.page.show', 'track-order') }}" class="flex items-center justify-between text-brand-muted hover:text-brand-dark transition-colors py-1">
                                <span>Live Order Tracking</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

</div>
@endsection
