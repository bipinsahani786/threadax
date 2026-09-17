{{-- ═════════════════════════════════════════════════════════════════ --}}
{{-- ✨ GOOGLE GEMINI AI ASSISTANT MODAL (Copywriting & Photoshoot)   --}}
{{-- ═════════════════════════════════════════════════════════════════ --}}
<div x-show="showAiModal" 
     x-cloak 
     class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
     role="dialog" 
     aria-modal="true">
    
    {{-- Backdrop --}}
    <div x-show="showAiModal" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="showAiModal = false" 
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

    {{-- Modal Panel --}}
    <div x-show="showAiModal" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl border border-slate-200/80 overflow-hidden z-10 my-8">

        {{-- Top Gradient Header --}}
        <div class="bg-gradient-to-r from-slate-900 via-purple-950 to-indigo-950 text-white p-5 sm:p-6 relative overflow-hidden">
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-purple-500/20 rounded-full blur-2xl pointer-events-none"></div>
            
            <div class="flex items-center justify-between relative z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-xl shadow-inner">
                        ✨
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-base sm:text-lg font-heading font-black tracking-tight text-white">
                                Gemini AI Studio
                            </h3>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-purple-500/30 text-purple-200 border border-purple-400/30">
                                1.5 Flash + Imagen 3
                            </span>
                        </div>
                        <p class="text-xs text-purple-200/80 mt-0.5">
                            Automate product descriptions, rich fit details &amp; high-fashion photoshoot
                        </p>
                    </div>
                </div>

                {{-- Close Button --}}
                <button type="button" 
                        @click="showAiModal = false" 
                        class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition-all cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Tabs --}}
            <div class="flex gap-2 mt-5 border-b border-white/10 pb-1">
                <button type="button" 
                        @click="aiTab = 'copy'"
                        :class="aiTab === 'copy' ? 'bg-white text-slate-900 font-bold shadow-sm' : 'text-purple-200 hover:text-white hover:bg-white/10 font-medium'"
                        class="px-3.5 py-1.5 rounded-xl text-xs transition-all flex items-center gap-1.5 cursor-pointer">
                    <span>✍️ Copywriting &amp; Details</span>
                </button>
                <button type="button" 
                        @click="aiTab = 'photoshoot'"
                        :class="aiTab === 'photoshoot' ? 'bg-white text-slate-900 font-bold shadow-sm' : 'text-purple-200 hover:text-white hover:bg-white/10 font-medium'"
                        class="px-3.5 py-1.5 rounded-xl text-xs transition-all flex items-center gap-1.5 cursor-pointer">
                    <span>📸 AI Model Photoshoot</span>
                </button>
            </div>
        </div>

        {{-- Modal Body --}}
        <div class="p-5 sm:p-7 max-h-[75vh] overflow-y-auto space-y-5">

            {{-- API Key Alert / Quick Setup Form --}}
            <div x-show="aiApiKeyNeeded" 
                 x-cloak 
                 class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 space-y-3">
                <div class="flex items-start gap-2.5">
                    <span class="text-base mt-0.5">🔑</span>
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-amber-950">Gemini API Key Required</h4>
                        <p class="text-xs text-amber-800 mt-0.5">
                            Apni Google AI Studio ki free API key daalein. Yeh bilkul free hai aur 1 minute me milti hai:
                            <a href="https://aistudio.google.com/app/apikey" target="_blank" rel="noopener noreferrer" class="font-bold underline text-purple-700 hover:text-purple-900 ml-1">
                                Get Free API Key →
                            </a>
                        </p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <input type="password" 
                           x-model="aiInputKey" 
                           placeholder="AIzaSy..." 
                           class="flex-1 px-3 py-2 bg-white border border-amber-300 rounded-xl text-xs font-mono outline-none focus:border-purple-500">
                    <button type="button" 
                            @click="saveGeminiApiKey()" 
                            :disabled="aiSavingKey"
                            class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition-all cursor-pointer disabled:opacity-50 shrink-0">
                        <span x-show="!aiSavingKey">Save Key</span>
                        <span x-show="aiSavingKey" class="animate-pulse">Saving...</span>
                    </button>
                </div>
            </div>

            {{-- Success Toast --}}
            <div x-show="aiSuccess" 
                 x-cloak 
                 x-transition 
                 class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2">
                <span>✅</span>
                <span x-text="aiSuccess"></span>
            </div>

            {{-- Error Toast --}}
            <div x-show="aiError" 
                 x-cloak 
                 x-transition 
                 class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold flex items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <span>⚠️</span>
                    <span x-text="aiError"></span>
                </div>
                <button type="button" @click="aiError = ''" class="text-rose-400 hover:text-rose-700">✕</button>
            </div>

            {{-- ─────────────────────────────────────────────────────── --}}
            {{-- TAB 1: COPYWRITING & RICH DETAILS                       --}}
            {{-- ─────────────────────────────────────────────────────── --}}
            <div x-show="aiTab === 'copy'" class="space-y-4">
                
                {{-- Product Name Preview / Input --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Product Name <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           x-model="name" 
                           placeholder="e.g., Furious Motion Graphic Oversized Tee"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 outline-none focus:bg-white focus:border-purple-400 transition-all">
                </div>

                {{-- User's Rough Notes / Key Highlights --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Aapke Rough Notes / Details (Optional)
                        </label>
                        <span class="text-[11px] text-slate-400">Jaise points, fabric, graphics, color</span>
                    </div>
                    <textarea x-model="aiRoughNotes" 
                              rows="3" 
                              placeholder="Yaha rough points likh sakte hain... (E.g.: Red oversized tee, front small minimal logo, back bold furious motion graphic, 240 GSM heavy french terry cotton, drop shoulder cut, machine wash cold)"
                              class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 outline-none focus:bg-white focus:border-purple-400 transition-all leading-relaxed"></textarea>
                    <p class="text-[11px] text-slate-400 mt-1">
                        💡 Gemini in rough notes ko expand karke professional product story, fit features, fabric info aur wash care create karega.
                    </p>
                </div>

                {{-- Tone of Voice Selection --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Brand Tone &amp; Vibe
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="flex flex-col items-center justify-center p-2.5 rounded-xl border cursor-pointer text-center transition-all"
                               :class="aiTone === 'streetwear_bold' ? 'bg-purple-50 border-purple-500 text-purple-900 font-bold' : 'bg-slate-50 border-slate-200 text-slate-600 hover:bg-slate-100/60'">
                            <input type="radio" x-model="aiTone" value="streetwear_bold" class="sr-only">
                            <span class="text-sm mb-0.5">🔥</span>
                            <span class="text-xs">Streetwear Bold</span>
                        </label>
                        <label class="flex flex-col items-center justify-center p-2.5 rounded-xl border cursor-pointer text-center transition-all"
                               :class="aiTone === 'minimal_luxury' ? 'bg-purple-50 border-purple-500 text-purple-900 font-bold' : 'bg-slate-50 border-slate-200 text-slate-600 hover:bg-slate-100/60'">
                            <input type="radio" x-model="aiTone" value="minimal_luxury" class="sr-only">
                            <span class="text-sm mb-0.5">✨</span>
                            <span class="text-xs">Quiet Luxury</span>
                        </label>
                        <label class="flex flex-col items-center justify-center p-2.5 rounded-xl border cursor-pointer text-center transition-all"
                               :class="aiTone === 'casual_everyday' ? 'bg-purple-50 border-purple-500 text-purple-900 font-bold' : 'bg-slate-50 border-slate-200 text-slate-600 hover:bg-slate-100/60'">
                            <input type="radio" x-model="aiTone" value="casual_everyday" class="sr-only">
                            <span class="text-sm mb-0.5">👕</span>
                            <span class="text-xs">Casual Urban</span>
                        </label>
                    </div>
                </div>

                {{-- Action Button --}}
                <div class="pt-2">
                    <button type="button" 
                            @click="generateWithGemini()" 
                            :disabled="aiLoading"
                            class="w-full py-3 rounded-2xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold text-xs shadow-md hover:shadow-lg active:scale-98 transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50">
                        <span x-show="!aiLoading">🚀 Generate Rich Details &amp; Prefill CKEditor</span>
                        <span x-show="aiLoading" class="inline-flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span>Gemini is generating rich product story &amp; SEO...</span>
                        </span>
                    </button>
                    <p class="text-[11px] text-center text-slate-400 mt-2">
                        Subtitle, Full CKEditor Description (Headings + Bullet points) aur SEO tags automatic set ho jayenge.
                    </p>
                </div>
            </div>

            {{-- ─────────────────────────────────────────────────────── --}}
            {{-- TAB 2: AI MODEL PHOTOSHOOT (IMAGEN 3)                   --}}
            {{-- ─────────────────────────────────────────────────────── --}}
            <div x-show="aiTab === 'photoshoot'" class="space-y-4">
                
                {{-- Reference Image Upload --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Upload Product Picture / Mockup (Recommended)
                    </label>
                    <div class="border-2 border-dashed border-slate-200 hover:border-purple-300 rounded-2xl p-4 text-center transition-all bg-slate-50/50">
                        <template x-if="!aiImagePreview">
                            <div class="py-3">
                                <div class="w-10 h-10 mx-auto rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg mb-2">
                                    📸
                                </div>
                                <label class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 cursor-pointer shadow-xs">
                                    <span>Choose Product Photo</span>
                                    <input type="file" accept="image/*" @change="handleAiImageUpload($event)" class="sr-only">
                                </label>
                                <p class="text-[11px] text-slate-400 mt-1.5">
                                    Front photo, flat-lay ya mockup upload karein. Gemini iska print &amp; cut detect karke model par generate karega.
                                </p>
                            </div>
                        </template>

                        <template x-if="aiImagePreview">
                            <div class="flex items-center justify-between gap-4 p-2 bg-white rounded-xl border border-slate-200">
                                <div class="flex items-center gap-3">
                                    <img :src="aiImagePreview" class="w-14 h-14 object-cover rounded-lg border border-slate-100">
                                    <div class="text-left">
                                        <p class="text-xs font-bold text-slate-800">Reference Image Loaded</p>
                                        <p class="text-[11px] text-emerald-600 font-medium">Ready for Multimodal Analysis</p>
                                    </div>
                                </div>
                                <button type="button" @click="aiImagePreview = ''; aiImageBase64 = '';" class="text-xs font-bold text-rose-600 hover:underline">
                                    Change
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Model & Vibe Selection --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Model &amp; Photoshoot Aesthetic
                    </label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="flex items-center gap-2 p-2.5 rounded-xl border cursor-pointer transition-all"
                               :class="aiImageStyle === 'male_streetwear' ? 'bg-purple-50 border-purple-500 text-purple-900 font-bold' : 'bg-slate-50 border-slate-200 text-slate-700 hover:bg-slate-100/60'">
                            <input type="radio" x-model="aiImageStyle" value="male_streetwear" class="sr-only">
                            <span class="text-base">🕺</span>
                            <div class="text-left">
                                <p class="text-xs">Indian Male Model</p>
                                <p class="text-[10px] text-slate-400 font-normal">Streetwear pose &amp; look</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-2 p-2.5 rounded-xl border cursor-pointer transition-all"
                               :class="aiImageStyle === 'female_streetwear' ? 'bg-purple-50 border-purple-500 text-purple-900 font-bold' : 'bg-slate-50 border-slate-200 text-slate-700 hover:bg-slate-100/60'">
                            <input type="radio" x-model="aiImageStyle" value="female_streetwear" class="sr-only">
                            <span class="text-base">💃</span>
                            <div class="text-left">
                                <p class="text-xs">Indian Female Model</p>
                                <p class="text-[10px] text-slate-400 font-normal">Chic urban fashion</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-2 p-2.5 rounded-xl border cursor-pointer transition-all"
                               :class="aiImageStyle === 'urban_night' ? 'bg-purple-50 border-purple-500 text-purple-900 font-bold' : 'bg-slate-50 border-slate-200 text-slate-700 hover:bg-slate-100/60'">
                            <input type="radio" x-model="aiImageStyle" value="urban_night" class="sr-only">
                            <span class="text-base">🌆</span>
                            <div class="text-left">
                                <p class="text-xs">Tokyo / Seoul Night</p>
                                <p class="text-[10px] text-slate-400 font-normal">Neon reflection street</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-2 p-2.5 rounded-xl border cursor-pointer transition-all"
                               :class="aiImageStyle === 'studio_minimal' ? 'bg-purple-50 border-purple-500 text-purple-900 font-bold' : 'bg-slate-50 border-slate-200 text-slate-700 hover:bg-slate-100/60'">
                            <input type="radio" x-model="aiImageStyle" value="studio_minimal" class="sr-only">
                            <span class="text-base">🏛️</span>
                            <div class="text-left">
                                <p class="text-xs">Concrete Studio</p>
                                <p class="text-[10px] text-slate-400 font-normal">Minimalist clean look</p>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Action Button --}}
                <div class="pt-2">
                    <button type="button" 
                            @click="generateModelPhoto({{ isset($product) ? $product->id : 'null' }})" 
                            :disabled="aiImageLoading"
                            class="w-full py-3 rounded-2xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold text-xs shadow-md hover:shadow-lg active:scale-98 transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50">
                        <span x-show="!aiImageLoading">📸 Generate On-Model Photoshoot</span>
                        <span x-show="aiImageLoading" class="inline-flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span>Generating photorealistic model photoshoot with Imagen 3...</span>
                        </span>
                    </button>
                </div>

                {{-- Generated Result Card --}}
                <div x-show="aiImageResultUrl" x-cloak class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-800">Generated On-Model Shoot</span>
                        <a :href="aiImageResultUrl" target="_blank" download class="text-xs font-bold text-purple-600 hover:underline inline-flex items-center gap-1">
                            <span>Open Full Size ↗</span>
                        </a>
                    </div>
                    <div class="aspect-[3/4] max-w-xs mx-auto rounded-xl overflow-hidden border border-slate-200 shadow-md">
                        <img :src="aiImageResultUrl" class="w-full h-full object-cover" alt="AI Generated Model Photoshoot">
                    </div>
                    @if(isset($product))
                        <p class="text-xs text-center text-emerald-600 font-bold">
                            ✅ Yeh image is product ke Media Gallery me automatic attach ho gayi hai!
                        </p>
                    @else
                        <p class="text-xs text-center text-slate-500 font-medium">
                            Product create hone ke baad Variants &amp; Media page par aap aur bhi photoshoots add kar sakte hain.
                        </p>
                    @endif
                </div>

            </div>

        </div>

    </div>
</div>

<script>
function productAiHelper(config = {}) {
    return {
        showAiModal: false,
        aiTab: 'copy',
        aiRoughNotes: '',
        aiTone: 'streetwear_bold',
        aiLoading: false,
        aiError: '',
        aiSuccess: '',
        aiApiKeyNeeded: false,
        aiInputKey: '',
        aiSavingKey: false,

        // Photoshoot states
        aiImagePreview: '',
        aiImageBase64: '',
        aiImageStyle: 'male_streetwear',
        aiImageLoading: false,
        aiImageResultUrl: '',
        aiImageResultPath: '',

        openAiModal(tab = 'copy') {
            this.aiTab = tab;
            this.showAiModal = true;
            this.aiError = '';
            this.aiSuccess = '';
        },

        handleAiImageUpload(event) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                this.aiImageBase64 = e.target.result;
                this.aiImagePreview = e.target.result;
            };
            reader.readAsDataURL(file);
        },

        async generateWithGemini() {
            if (!this.name || !this.name.trim()) {
                this.aiError = 'Please enter a product name first!';
                return;
            }
            this.aiLoading = true;
            this.aiError = '';
            this.aiSuccess = '';

            try {
                const res = await fetch('{{ route('admin.products.ai.generate-content') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        name: this.name,
                        rough_notes: this.aiRoughNotes,
                        category_id: document.querySelector('input[name="category_ids[]"]:checked')?.value || null,
                        tone: this.aiTone
                    })
                });

                const data = await res.json();
                if (!res.ok) {
                    if (data.requires_key) {
                        this.aiApiKeyNeeded = true;
                    }
                    throw new Error(data.message || 'Generation failed');
                }

                if (data.success && data.data) {
                    // Fill Short Description
                    if (data.data.short_description) {
                        const shortDescEl = document.querySelector('#short_description');
                        if (shortDescEl) shortDescEl.value = data.data.short_description;
                    }

                    // Fill Description & CKEditor
                    if (data.data.description) {
                        const descEl = document.querySelector('#description');
                        if (descEl) descEl.value = data.data.description;
                        if (window.productCkEditor) {
                            window.productCkEditor.setData(data.data.description);
                        }
                    }

                    // Fill SEO
                    if (data.data.meta_title) {
                        this.metaTitle = data.data.meta_title;
                        const metaTitleEl = document.querySelector('#meta_title');
                        if (metaTitleEl) metaTitleEl.value = data.data.meta_title;
                    }
                    if (data.data.meta_description) {
                        this.metaDescription = data.data.meta_description;
                        const metaDescEl = document.querySelector('#meta_description');
                        if (metaDescEl) metaDescEl.value = data.data.meta_description;
                    }

                    this.aiSuccess = '✨ Product content & fit details generated successfully!';
                    setTimeout(() => { this.showAiModal = false; }, 1800);
                }
            } catch (err) {
                this.aiError = err.message || 'Something went wrong while connecting to Gemini.';
            } finally {
                this.aiLoading = false;
            }
        },

        async generateModelPhoto(productId = null) {
            const prodName = this.name || 'Streetwear Drop';
            this.aiImageLoading = true;
            this.aiError = '';
            this.aiSuccess = '';
            this.aiImageResultUrl = '';

            try {
                const res = await fetch('{{ route('admin.products.ai.generate-image') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        product_name: prodName,
                        rough_notes: this.aiRoughNotes,
                        reference_image: this.aiImageBase64 || null,
                        model_style: this.aiImageStyle,
                        product_id: productId
                    })
                });

                const data = await res.json();
                if (!res.ok) {
                    if (data.requires_key) {
                        this.aiApiKeyNeeded = true;
                    }
                    throw new Error(data.message || 'Image generation failed');
                }

                if (data.success && data.data) {
                    this.aiImageResultUrl = data.data.url;
                    this.aiImageResultPath = data.data.relative_path;
                    this.aiSuccess = '📸 High-fashion model photoshoot generated successfully!';
                    if (productId && window.location.pathname.includes('/variants')) {
                        setTimeout(() => { window.location.reload(); }, 1800);
                    }
                }
            } catch (err) {
                this.aiError = err.message || 'Failed to generate model photoshoot.';
            } finally {
                this.aiImageLoading = false;
            }
        },

        async saveGeminiApiKey() {
            if (!this.aiInputKey.trim()) return;
            this.aiSavingKey = true;
            this.aiError = '';
            try {
                const res = await fetch('{{ route('admin.products.ai.save-key') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ api_key: this.aiInputKey })
                });
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Failed to save key');
                this.aiApiKeyNeeded = false;
                this.aiSuccess = 'Key saved successfully! Now generating...';
                if (this.aiTab === 'copy') {
                    await this.generateWithGemini();
                }
            } catch (err) {
                this.aiError = err.message;
            } finally {
                this.aiSavingKey = false;
            }
        }
    };
}
</script>
