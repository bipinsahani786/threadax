@extends('admin.layouts.app')

@section('title', 'Store Settings')
@section('page-title', 'Store Settings')

@section('content')

<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" id="settingsForm">
    @csrf
    @method('PUT')

    {{-- Top Header Action Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <h2 class="text-xl font-heading font-black text-slate-900 tracking-tight flex items-center gap-2">
                <span>⚙️ Store Configuration</span>
            </h2>
            <p class="text-xs text-slate-500 mt-1">
                Manage your storefront branding, support contacts, logistics thresholds, and policies.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2 sm:gap-3 w-full sm:w-auto">
            <a href="{{ route('admin.dashboard') }}" class="flex-1 sm:flex-initial text-center px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition-all">
                Cancel
            </a>
            <button type="submit" class="flex-2 sm:flex-initial inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold shadow-md hover:shadow-lg active:scale-95 transition-all cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                <span>Save All Settings</span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
        
        {{-- LEFT COLUMN (2/3 width): Contact, Logistics & Shipping Policy --}}
        <div class="lg:col-span-2 space-y-6 sm:space-y-8">
            
            {{-- 1. Contact & Support Information --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-7 shadow-xs">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-base">
                        📞
                    </div>
                    <div>
                        <h3 class="text-sm font-heading font-extrabold text-slate-900">Support & Contact Channels</h3>
                        <p class="text-xs text-slate-400">Customer communication details shown on website & footer</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- Contact Email --}}
                    <div>
                        <label for="contact_email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Support Email Address <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">✉️</span>
                            <input type="email" 
                                   name="contact_email" 
                                   id="contact_email" 
                                   value="{{ $settings['contact_email'] ?? 'support@threadax.co.in' }}" 
                                   required
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1.5">Used for customer order confirmations and inquiries.</p>
                    </div>

                    {{-- Contact Phone / Mobile --}}
                    <div>
                        <label for="contact_phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Customer Support Calling / Phone Number <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">📞</span>
                            <input type="text" 
                                   name="contact_phone" 
                                   id="contact_phone" 
                                   value="{{ $settings['contact_phone'] ?? '+91 98765 43210' }}" 
                                   placeholder="e.g., +91 98765 43210"
                                   required
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all font-mono">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1.5">Direct phone calling number displayed on website, invoices &amp; labels.</p>
                    </div>

                    {{-- WhatsApp Number --}}
                    <div>
                        <label for="whatsapp_number" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            WhatsApp Support Number
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">💬</span>
                            <input type="text" 
                                   name="whatsapp_number" 
                                   id="whatsapp_number" 
                                   value="{{ $settings['whatsapp_number'] ?? '919876543210' }}" 
                                   placeholder="e.g., 919876543210"
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all font-mono">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1.5">Include country code without + or spaces (e.g. 919876543210).</p>
                    </div>

                    {{-- Store Physical Address --}}
                    <div class="sm:col-span-2">
                        <label for="store_address" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Registered Store Office Address
                        </label>
                        <textarea name="store_address" 
                                  id="store_address" 
                                  rows="2" 
                                  placeholder="e.g. ThreadAx HQ, 402 Urban Heights, Fashion District, Mumbai, Maharashtra 400001"
                                  class="w-full p-3 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all">{{ $settings['store_address'] ?? 'ThreadAx HQ, Mumbai, Maharashtra, India' }}</textarea>
                    </div>
                </div>
            </div>

            {{-- 2. Business Legal Entity, GST & Tax Invoice Details --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-7 shadow-xs">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-base">
                            🏢
                        </div>
                        <div>
                            <h3 class="text-sm font-heading font-extrabold text-slate-900">Business Entity, GST & Tax Invoice</h3>
                            <p class="text-xs text-slate-400">All company info dynamically rendered on Tax Invoices, Shipping Labels & Emails</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-blue-700 bg-blue-50 px-2.5 py-1 rounded-full border border-blue-200">
                        <span>GST Compliant</span>
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- Brand Name --}}
                    <div>
                        <label for="brand_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Brand Name (Trading As) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               name="brand_name" 
                               id="brand_name" 
                               value="{{ $settings['brand_name'] ?? 'THREADAX' }}" 
                               placeholder="e.g. THREADAX"
                               required
                               class="w-full px-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-900 outline-none transition-all">
                    </div>

                    {{-- Brand Tagline --}}
                    <div>
                        <label for="brand_tagline" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Brand Tagline / Slogan
                        </label>
                        <input type="text" 
                               name="brand_tagline" 
                               id="brand_tagline" 
                               value="{{ $settings['brand_tagline'] ?? 'PREMIUM STREETWEAR & APPAREL' }}" 
                               placeholder="e.g. PREMIUM STREETWEAR & APPAREL"
                               class="w-full px-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all">
                    </div>

                    {{-- Registered Company Legal Name --}}
                    <div class="sm:col-span-2">
                        <label for="company_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Registered Company Legal Entity Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               name="company_name" 
                               id="company_name" 
                               value="{{ $settings['company_name'] ?? 'THREADAX APPARELS PRIVATE LIMITED' }}" 
                               placeholder="e.g. THREADAX APPARELS PRIVATE LIMITED"
                               required
                               class="w-full px-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-900 outline-none transition-all">
                    </div>

                    {{-- GSTIN --}}
                    <div>
                        <label for="company_gstin" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            GSTIN (Goods & Services Tax Number)
                        </label>
                        <input type="text" 
                               name="company_gstin" 
                               id="company_gstin" 
                               value="{{ $settings['company_gstin'] ?? '27AAACT9988F1Z2' }}" 
                               placeholder="e.g. 27AAACT9988F1Z2"
                               class="w-full px-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-mono font-bold text-slate-900 outline-none transition-all">
                    </div>

                    {{-- Corporate PAN --}}
                    <div>
                        <label for="company_pan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Permanent Account Number (PAN)
                        </label>
                        <input type="text" 
                               name="company_pan" 
                               id="company_pan" 
                               value="{{ $settings['company_pan'] ?? 'AAACT9988F' }}" 
                               placeholder="e.g. AAACT9988F"
                               class="w-full px-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-mono font-bold text-slate-900 outline-none transition-all">
                    </div>

                    {{-- State of Registration / Supply --}}
                    <div>
                        <label for="company_state" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            State of Registration / Origin
                        </label>
                        <input type="text" 
                               name="company_state" 
                               id="company_state" 
                               value="{{ $settings['company_state'] ?? 'Maharashtra (State Code: 27)' }}" 
                               placeholder="e.g. Maharashtra (State Code: 27)"
                               class="w-full px-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all">
                    </div>

                    {{-- Default Apparel HSN Code --}}
                    <div>
                        <label for="default_hsn_code" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Default HSN / SAC Code
                        </label>
                        <input type="text" 
                               name="default_hsn_code" 
                               id="default_hsn_code" 
                               value="{{ $settings['default_hsn_code'] ?? '610910' }}" 
                               placeholder="e.g. 610910"
                               class="w-full px-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-mono font-bold text-slate-900 outline-none transition-all">
                    </div>

                    {{-- Invoice Prefix --}}
                    <div>
                        <label for="invoice_prefix" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Invoice Number Prefix
                        </label>
                        <input type="text" 
                               name="invoice_prefix" 
                               id="invoice_prefix" 
                               value="{{ $settings['invoice_prefix'] ?? 'INV-' }}" 
                               placeholder="e.g. INV-"
                               class="w-full px-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-mono font-bold text-slate-900 outline-none transition-all">
                    </div>

                    {{-- Invoice Signatory Entity --}}
                    <div>
                        <label for="invoice_signatory_title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Invoice Signatory Header
                        </label>
                        <input type="text" 
                               name="invoice_signatory_title" 
                               id="invoice_signatory_title" 
                               value="{{ $settings['invoice_signatory_title'] ?? 'THREADAX APPARELS PVT. LTD.' }}" 
                               placeholder="e.g. THREADAX APPARELS PVT. LTD."
                               class="w-full px-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-900 outline-none transition-all">
                    </div>

                    {{-- Digital Stamp Text --}}
                    <div class="sm:col-span-2">
                        <label for="invoice_stamp_text" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Digital Stamp / Watermark Text
                        </label>
                        <input type="text" 
                               name="invoice_stamp_text" 
                               id="invoice_stamp_text" 
                               value="{{ $settings['invoice_stamp_text'] ?? 'DIGITALLY AUTHORIZED' }}" 
                               placeholder="e.g. DIGITALLY AUTHORIZED"
                               class="w-full px-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-mono font-bold text-slate-900 outline-none transition-all">
                    </div>

                    {{-- Invoice Terms & Conditions --}}
                    <div class="sm:col-span-2">
                        <label for="invoice_terms" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Invoice Terms & Conditions (Legal Notice)
                        </label>
                        <textarea name="invoice_terms" 
                                  id="invoice_terms" 
                                  rows="3" 
                                  placeholder="Terms & Conditions displayed at the bottom of the tax invoice..."
                                  class="w-full p-3 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all leading-relaxed">{{ $settings['invoice_terms'] ?? "1. This is a computer-generated tax invoice and requires no physical signature.\n2. Goods once sold are eligible for return / exchange within 7 days in unworn condition with original tags.\n3. All disputes are subject to Mumbai Jurisdiction only." }}</textarea>
                    </div>
                </div>
            </div>

            {{-- 3. Shipping & Returns Policy with CKEditor --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-7 shadow-xs">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-base">
                            📜
                        </div>
                        <div>
                            <h3 class="text-sm font-heading font-extrabold text-slate-900">Shipping & Returns Policy</h3>
                            <p class="text-xs text-slate-400">Dynamically rendered on all product detail pages</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                        <span>Dynamic Live Policy</span>
                    </span>
                </div>

                <div class="mb-3">
                    <label for="shipping_policy" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Policy Content (Rich Text / Formatted)
                    </label>
                    <textarea name="shipping_policy" 
                              id="shipping_policy" 
                              rows="6" 
                              class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs">{!! $settings['shipping_policy'] ?? "<p><strong class=\"text-brand-text\">Free Shipping:</strong> On all orders above ₹999.</p>\n<p><strong class=\"text-brand-text\">Delivery Time:</strong> Standard delivery within 3-5 business days. Metro cities within 1-2 business days.</p>\n<p><strong class=\"text-brand-text\">Returns:</strong> Easy 7-day returns and exchanges. Product must be unwashed and unworn with original tags attached.</p>" !!}</textarea>
                </div>

                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-start gap-2.5 text-xs text-slate-500">
                    <span class="text-base">💡</span>
                    <p class="text-[11px] leading-relaxed">
                        Use bold styling, bullets, and highlights above. Changes made here will instantly reflect inside the <strong>"Shipping & Returns"</strong> tab on every single product page!
                    </p>
                </div>
            </div>

            {{-- 3. Shiprocket Logistics & Automated Fulfillment --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-7 shadow-xs">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-base">
                            🚀
                        </div>
                        <div>
                            <h3 class="text-sm font-heading font-extrabold text-slate-900">Shiprocket Courier & Logistics Integration</h3>
                            <p class="text-xs text-slate-400">Automated AWB generation, multi-courier dispatch & real-time tracking</p>
                        </div>
                    </div>
                    @if(!empty($settings['shiprocket_email']))
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            <span>API Configured</span>
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-700 bg-amber-50 px-2.5 py-1 rounded-full border border-amber-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            <span>Sandbox / Mock Mode</span>
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- Shiprocket Email --}}
                    <div>
                        <label for="shiprocket_email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Shiprocket API Email
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">✉️</span>
                            <input type="email" 
                                   name="shiprocket_email" 
                                   id="shiprocket_email" 
                                   value="{{ $settings['shiprocket_email'] ?? '' }}" 
                                   placeholder="e.g. logistics@threadax.co.in"
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all">
                        </div>
                    </div>

                    {{-- Shiprocket Password --}}
                    <div>
                        <label for="shiprocket_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Shiprocket API Password
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">🔑</span>
                            <input type="password" 
                                   name="shiprocket_password" 
                                   id="shiprocket_password" 
                                   value="{{ $settings['shiprocket_password'] ?? '' }}" 
                                   placeholder="••••••••••••"
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all">
                        </div>
                    </div>

                    {{-- Pickup Location Nickname --}}
                    <div class="sm:col-span-2">
                        <label for="shiprocket_pickup_location" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Warehouse Pickup Location Nickname
                        </label>
                        <input type="text" 
                               name="shiprocket_pickup_location" 
                               id="shiprocket_pickup_location" 
                               value="{{ $settings['shiprocket_pickup_location'] ?? 'Primary' }}" 
                               placeholder="e.g. Primary, Warehouse1, Mumbai-Hub"
                               class="w-full px-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all font-mono">
                        <p class="text-[11px] text-slate-400 mt-1.5">Must match the exact pickup location name configured in your Shiprocket Dashboard (Settings > Pickup Addresses).</p>
                    </div>

                    {{-- Webhook URL Card --}}
                    <div class="sm:col-span-2 p-4 bg-slate-900 text-white rounded-xl space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-purple-300 uppercase tracking-wider">📡 Real-Time Tracking Webhook URL</span>
                            <button type="button" onclick="navigator.clipboard.writeText('{{ url('/webhooks/shiprocket') }}'); alert('Shiprocket Webhook URL copied!');" class="text-[11px] font-bold bg-white/10 hover:bg-white/20 text-white px-2.5 py-1 rounded-lg transition-all cursor-pointer">
                                📋 Copy URL
                            </button>
                        </div>
                        <p class="font-mono text-xs text-amber-300 break-all select-all">{{ url('/webhooks/shiprocket') }}</p>
                        <p class="text-[10px] text-slate-400">
                            Paste this URL in your Shiprocket Dashboard (<strong>Settings &gt; API &gt; Webhooks &gt; Tracking Status Updates</strong>) to receive automated tracking &amp; delivery updates!
                        </p>
                    </div>
                </div>
            </div>

            {{-- 4. Google Gemini AI & Model Photoshoot Configuration --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-7 shadow-xs">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100 flex-wrap gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-base">
                            ✨
                        </div>
                        <div>
                            <h3 class="text-sm font-heading font-extrabold text-slate-900">Google Gemini AI &amp; Model Shoot Integration</h3>
                            <p class="text-xs text-slate-400">Power automated product descriptions, SEO tags &amp; AI model photoshoots</p>
                        </div>
                    </div>
                    @if(!empty($settings['gemini_api_key']))
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>Connected</span>
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            <span>Key Required</span>
                        </span>
                    @endif
                </div>

                <div class="space-y-5">
                    {{-- Gemini API Key --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="gemini_api_key" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Google Gemini API Key <span class="text-rose-500">*</span>
                            </label>
                            <a href="https://aistudio.google.com/app/apikey" target="_blank" rel="noopener noreferrer" class="text-xs font-bold text-purple-600 hover:text-purple-700 hover:underline inline-flex items-center gap-1">
                                <span>Get Free API Key from Google AI Studio</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">🔑</span>
                            <input type="password" 
                                   name="gemini_api_key" 
                                   id="gemini_api_key" 
                                   value="{{ $settings['gemini_api_key'] ?? env('GEMINI_API_KEY', '') }}" 
                                   placeholder="AIzaSy..." 
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-purple-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all font-mono">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1.5">
                            Yeh 100% free hai! Google AI Studio par login karke <strong>Create API Key</strong> par click karein aur yaha paste karein.
                        </p>
                    </div>

                    {{-- Gemini Model Preference --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="gemini_model" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                AI Copywriting Model
                            </label>
                            <select name="gemini_model" id="gemini_model" class="w-full px-3 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-purple-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all cursor-pointer">
                                <option value="gemini-1.5-flash" {{ ($settings['gemini_model'] ?? 'gemini-1.5-flash') === 'gemini-1.5-flash' ? 'selected' : '' }}>Gemini 1.5 Flash (Recommended - Fastest &amp; Free)</option>
                                <option value="gemini-2.0-flash" {{ ($settings['gemini_model'] ?? '') === 'gemini-2.0-flash' ? 'selected' : '' }}>Gemini 2.0 Flash (Latest)</option>
                                <option value="gemini-1.5-pro" {{ ($settings['gemini_model'] ?? '') === 'gemini-1.5-pro' ? 'selected' : '' }}>Gemini 1.5 Pro (Deep Reasoning)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Image Photoshoot Engine
                            </label>
                            <input type="text" readonly value="Google Imagen 3 (imagen-3.0-generate-002)" class="w-full px-3 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-xs font-medium text-slate-600 outline-none cursor-not-allowed">
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN (1/3 width): Logistics, Social Media & Banner Image --}}
        <div class="space-y-6 sm:space-y-8">
            
            {{-- 3. Logistics & Free Shipping Threshold --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs">
                <div class="flex items-center gap-3 mb-5 pb-3 border-b border-slate-100">
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-base">
                        🚚
                    </div>
                    <div>
                        <h3 class="text-sm font-heading font-extrabold text-slate-900">Shipping Rules</h3>
                        <p class="text-xs text-slate-400">Cart & checkout delivery logic</p>
                    </div>
                </div>

                <div class="space-y-4">
                    {{-- Free Shipping Threshold --}}
                    <div>
                        <label for="free_shipping_threshold" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Free Shipping Threshold (₹)
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-bold text-xs">₹</span>
                            <input type="number" 
                                   name="free_shipping_threshold" 
                                   id="free_shipping_threshold" 
                                   value="{{ $settings['free_shipping_threshold'] ?? '999' }}" 
                                   min="0"
                                   step="1"
                                   class="w-full pl-8 pr-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-900 outline-none transition-all">
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">Orders at or above this cart value qualify for ₹0.00 Free Delivery.</p>
                    </div>

                    {{-- Standard Shipping Charge --}}
                    <div>
                        <label for="standard_shipping_charge" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Standard Shipping Charge (₹)
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-bold text-xs">₹</span>
                            <input type="number" 
                                   name="standard_shipping_charge" 
                                   id="standard_shipping_charge" 
                                   value="{{ $settings['standard_shipping_charge'] ?? '50' }}" 
                                   min="0"
                                   step="1"
                                   class="w-full pl-8 pr-4 py-2.5 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-bold text-slate-900 outline-none transition-all">
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">Charged on orders below the free shipping threshold.</p>
                    </div>
                </div>
            </div>

            {{-- 4. Social Media Channels --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs">
                <div class="flex items-center gap-3 mb-5 pb-3 border-b border-slate-100">
                    <div class="w-9 h-9 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center font-bold text-base">
                        🌐
                    </div>
                    <div>
                        <h3 class="text-sm font-heading font-extrabold text-slate-900">Social Media Links</h3>
                        <p class="text-xs text-slate-400">Links shown in footer & social share</p>
                    </div>
                </div>

                <div class="space-y-4">
                    {{-- Instagram Link --}}
                    <div>
                        <label for="instagram_link" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Instagram Profile URL
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">📸</span>
                            <input type="url" 
                                   name="instagram_link" 
                                   id="instagram_link" 
                                   value="{{ $settings['instagram_link'] ?? 'https://instagram.com/threadax.co.in' }}" 
                                   placeholder="https://instagram.com/yourhandle"
                                   class="w-full pl-9 pr-3.5 py-2 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all">
                        </div>
                    </div>

                    {{-- Facebook Link --}}
                    <div>
                        <label for="facebook_link" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Facebook Page URL
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">📘</span>
                            <input type="url" 
                                   name="facebook_link" 
                                   id="facebook_link" 
                                   value="{{ $settings['facebook_link'] ?? '' }}" 
                                   placeholder="https://facebook.com/yourpage"
                                   class="w-full pl-9 pr-3.5 py-2 bg-slate-50 hover:bg-slate-100/50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl text-xs font-medium text-slate-900 outline-none transition-all">
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5. Shop Header Background Image --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs">
                <div class="flex items-center gap-3 mb-5 pb-3 border-b border-slate-100">
                    <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-base">
                        🖼️
                    </div>
                    <div>
                        <h3 class="text-sm font-heading font-extrabold text-slate-900">Shop Header Banner</h3>
                        <p class="text-xs text-slate-400">Background image for shop page hero</p>
                    </div>
                </div>

                @if(isset($settings['shop_header_image']) && !empty($settings['shop_header_image']))
                    <div class="mb-4 rounded-xl overflow-hidden border border-slate-200 bg-slate-100">
                        <img src="{{ asset('storage/' . $settings['shop_header_image']) }}" alt="Shop Header" class="w-full h-28 object-cover">
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Upload New Banner</label>
                    <input type="file" 
                           name="shop_header_image" 
                           id="shop_header_image" 
                           accept="image/*" 
                           class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-900 file:text-white hover:file:bg-slate-800 transition-all cursor-pointer">
                    <p class="text-[10px] text-slate-400 mt-2">Recommended resolution: 1920×600 px (JPG, PNG or WebP).</p>
                </div>
            </div>

        </div>

    </div>

    {{-- Bottom Floating Save Action Bar --}}
    <div class="mt-8 pt-6 border-t border-slate-200 flex items-center justify-end gap-3">
        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold shadow-md hover:shadow-lg active:scale-95 transition-all cursor-pointer">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
            <span>Save All Settings</span>
        </button>
    </div>

</form>

@endsection

@push('styles')
<style>
    /* CKEditor Clean Admin Styles */
    .ck-editor__editable_inline {
        min-height: 180px !important;
        max-height: 360px !important;
        font-family: 'Inter', sans-serif !important;
        font-size: 13px !important;
        border-bottom-left-radius: 12px !important;
        border-bottom-right-radius: 12px !important;
        padding: 12px 16px !important;
        color: #0F172A !important;
        background-color: #FAFAFA !important;
    }
    .ck.ck-toolbar {
        border-top-left-radius: 12px !important;
        border-top-right-radius: 12px !important;
        background-color: #F8FAFC !important;
        border-color: #E2E8F0 !important;
    }
    .ck.ck-editor__main>.ck-editor__editable {
        border-color: #E2E8F0 !important;
    }
    .ck.ck-editor__main>.ck-editor__editable:focus {
        border-color: #94A3B8 !important;
        box-shadow: none !important;
        background-color: #FFFFFF !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const policyEl = document.querySelector('#shipping_policy');
    if (policyEl) {
        ClassicEditor
            .create(policyEl, {
                toolbar: [
                    'heading', '|', 
                    'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|', 
                    'undo', 'redo'
                ],
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading3', view: 'h3', title: 'Heading', class: 'ck-heading_heading3' }
                    ]
                }
            })
            .catch(error => {
                console.error('CKEditor Init Error:', error);
            });
    }
});
</script>
@endpush
