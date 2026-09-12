@extends('admin.layouts.app')

@section('title', 'Manage Variants & Media - ' . $product->name)
@section('page-title')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.products.index') }}" class="text-slate-400 hover:text-slate-900 transition-colors p-1.5 rounded-xl hover:bg-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <div class="flex items-center gap-2">
                <span class="text-lg font-extrabold text-slate-900">Manage Drop: {{ $product->name }}</span>
                @if($product->is_active)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Live
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-600 border border-slate-200">
                        Draft
                    </span>
                @endif
            </div>
            <p class="text-xs text-slate-400">Master SKU: {{ $product->effective_sku }} • Base Price: ₹{{ number_format($product->price) }}</p>
        </div>
    </div>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-6 sm:space-y-8" 
     x-data="variantsManager({{ Js::from($product->variants) }}, '{{ $product->effective_sku }}', {{ $product->price }})">
    
    {{-- Executive Header & Studio Bar --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs">
        
        <div class="flex items-center gap-4">
            <div class="w-14 h-16 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center shadow-2xs relative">
                @if($product->primaryImage)
                    <img src="{{ $product->primaryImage->url }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
                @else
                    <span class="font-extrabold text-slate-400 text-xs">TX</span>
                @endif
                <span class="absolute bottom-0 inset-x-0 bg-black/60 text-white text-[8px] font-black text-center py-0.5">
                    {{ $product->images->count() }} imgs
                </span>
            </div>
            
            <div>
                <h2 class="text-base font-extrabold text-slate-900 line-clamp-1">{{ $product->name }}</h2>
                <div class="flex items-center gap-2 mt-1 text-xs text-slate-500 flex-wrap">
                    <span class="font-bold text-slate-900 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200">
                        ₹{{ number_format($product->price) }}
                    </span>
                    <span>•</span>
                    <span class="font-mono text-slate-400">{{ $product->effective_sku }}</span>
                    <span>•</span>
                    <span class="text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                        📁 {{ $product->category->name ?? 'Streetwear' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Quick Navigation Actions --}}
        <div class="flex items-center gap-2.5 flex-wrap">
            <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors shadow-2xs">
                <span>✏️ Edit Basic Info</span>
            </a>

            <a href="{{ route('frontend.products.show', $product->slug) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors shadow-2xs">
                <span>👁️ View Live Store</span>
            </a>
        </div>
    </div>

    {{-- Main Workspace Grid --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 sm:gap-8">
        
        {{-- Left Column: Variants Matrix Manager (Span 2) --}}
        <div class="xl:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-7 shadow-xs">
                
                {{-- Matrix Title & Quick Tools Toolbar --}}
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-base">
                            ⚡
                        </div>
                        <div>
                            <h3 class="text-sm font-heading font-extrabold text-slate-900">Inventory & Variant Matrix</h3>
                            <p class="text-xs text-slate-400">Configure size variations, colorways, SKUs, and stock quantities</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2 flex-wrap">
                        {{-- 1-Click Standard Sizes Generator --}}
                        <button type="button" 
                                @click="generateStandardSizes()" 
                                class="px-3 py-1.5 bg-purple-50 hover:bg-purple-100 text-purple-700 border border-purple-200 text-xs font-bold rounded-xl transition-all cursor-pointer shadow-2xs flex items-center gap-1">
                            <span>✨ + Quick S, M, L, XL, XXL</span>
                        </button>
                        
                        {{-- Add Row Button --}}
                        <button type="button" 
                                @click="addVariant()" 
                                class="px-3.5 py-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-extrabold rounded-xl transition-all flex items-center gap-1.5 shadow-2xs cursor-pointer">
                            <span>➕ Add Row</span>
                        </button>
                    </div>
                </div>

                {{-- Bulk Operations & Quick Action Strip --}}
                <div class="mb-5 p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 flex flex-wrap items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-2">
                        <span class="font-extrabold text-slate-700">⚡ Bulk Tools:</span>
                        <button type="button" @click="generateCleanSkus()" class="px-2.5 py-1 bg-white hover:bg-slate-100 text-slate-700 font-bold rounded-lg border border-slate-200 transition-colors shadow-2xs cursor-pointer">
                            Auto-Generate SKUs
                        </button>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <span class="text-slate-500 font-medium">Bulk Stock:</span>
                        <input type="number" x-model.number="bulkStock" placeholder="50" class="w-20 px-2.5 py-1 rounded-lg border border-slate-200 bg-white text-xs font-bold text-slate-900 outline-none">
                        <button type="button" @click="applyBulkStock()" class="px-3 py-1 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-lg transition-colors cursor-pointer shadow-2xs">
                            Apply to All
                        </button>
                    </div>
                </div>

                {{-- Variants Form Table --}}
                <form action="{{ route('admin.products.variants.store', $product) }}" method="POST">
                    @csrf
                    
                    <div class="overflow-x-auto rounded-xl border border-slate-200 mb-6">
                        <table class="w-full text-left text-xs text-slate-600">
                            <thead class="bg-slate-50 text-[11px] uppercase font-extrabold text-slate-500 border-b border-slate-200 tracking-wider">
                                <tr>
                                    <th class="px-4 py-3.5 min-w-[110px]">Size</th>
                                    <th class="px-4 py-3.5 min-w-[140px]">Color / Shade</th>
                                    <th class="px-4 py-3.5 min-w-[200px]">Variant SKU</th>
                                    <th class="px-4 py-3.5 min-w-[120px]">Price (₹)</th>
                                    <th class="px-4 py-3.5 min-w-[110px]">Stock Qty</th>
                                    <th class="px-4 py-3.5 text-center min-w-[80px]">Active</th>
                                    <th class="px-3 py-3.5 text-right w-10"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white font-medium">
                                <template x-for="(variant, index) in variants" :key="variant.temp_id || variant.id">
                                    <tr class="hover:bg-slate-50/70 transition-colors">
                                        {{-- Hidden ID for existing variants --}}
                                        <input type="hidden" :name="`variants[${index}][id]`" :value="variant.id">
                                        
                                        {{-- Size --}}
                                        <td class="px-4 py-2.5">
                                            <input type="text" 
                                                   :name="`variants[${index}][size]`" 
                                                   x-model="variant.size" 
                                                   placeholder="e.g. XL" 
                                                   class="w-full px-3 py-2 bg-slate-50/80 hover:bg-slate-100/60 focus:bg-white rounded-lg border border-slate-200 text-xs font-black text-slate-900 focus:border-slate-400 outline-none uppercase">
                                        </td>

                                        {{-- Color --}}
                                        <td class="px-4 py-2.5">
                                            <input type="text" 
                                                   :name="`variants[${index}][color]`" 
                                                   x-model="variant.color" 
                                                   placeholder="e.g. Vintage Grey" 
                                                   class="w-full px-3 py-2 bg-slate-50/80 hover:bg-slate-100/60 focus:bg-white rounded-lg border border-slate-200 text-xs font-semibold text-slate-800 focus:border-slate-400 outline-none">
                                        </td>

                                        {{-- SKU (Full Width, No Truncation) --}}
                                        <td class="px-4 py-2.5">
                                            <input type="text" 
                                                   :name="`variants[${index}][sku]`" 
                                                   x-model="variant.sku" 
                                                   placeholder="SKU Code" 
                                                   class="w-full px-3 py-2 bg-slate-50/80 hover:bg-slate-100/60 focus:bg-white rounded-lg border border-slate-200 text-xs font-mono font-bold text-slate-900 focus:border-slate-400 outline-none">
                                        </td>

                                        {{-- Price Override --}}
                                        <td class="px-4 py-2.5">
                                            <div class="relative">
                                                <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 text-xs">₹</span>
                                                <input type="number" 
                                                       step="0.01" 
                                                       :name="`variants[${index}][price]`" 
                                                       x-model="variant.price" 
                                                       :placeholder="basePrice" 
                                                       class="w-full pl-6 pr-2.5 py-2 bg-slate-50/80 hover:bg-slate-100/60 focus:bg-white rounded-lg border border-slate-200 text-xs font-bold text-slate-900 focus:border-slate-400 outline-none">
                                            </div>
                                        </td>

                                        {{-- Stock Quantity --}}
                                        <td class="px-4 py-2.5">
                                            <input type="number" 
                                                   :name="`variants[${index}][stock]`" 
                                                   x-model.number="variant.stock" 
                                                   required 
                                                   min="0" 
                                                   class="w-full px-3 py-2 rounded-lg border text-xs font-extrabold focus:border-slate-400 outline-none transition-colors"
                                                   :class="variant.stock == 0 ? 'border-rose-200 bg-rose-50/80 text-rose-700' : (variant.stock <= 5 ? 'border-amber-200 bg-amber-50/80 text-amber-700' : 'border-slate-200 bg-slate-50/80 text-slate-900')">
                                        </td>

                                        {{-- Active Checkbox --}}
                                        <td class="px-4 py-2.5 text-center">
                                            <input type="hidden" :name="`variants[${index}][is_active]`" value="0">
                                            <input type="checkbox" 
                                                   :name="`variants[${index}][is_active]`" 
                                                   value="1" 
                                                   x-model="variant.is_active" 
                                                   class="w-4 h-4 text-slate-900 bg-slate-100 border-slate-300 rounded focus:ring-0 cursor-pointer">
                                        </td>

                                        {{-- Remove Row --}}
                                        <td class="px-3 py-2.5 text-right">
                                            <button type="button" 
                                                    @click="removeVariant(index)" 
                                                    class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer" 
                                                    title="Delete Variant Row">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>

                                {{-- Empty State --}}
                                <tr x-show="variants.length === 0" x-cloak>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-400 text-xs">
                                        <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2 text-xl">
                                            👕
                                        </div>
                                        <h4 class="font-extrabold text-slate-700 text-sm mb-1">No variants configured</h4>
                                        <p class="text-slate-400 mb-3">Click "+ Quick S, M, L, XL, XXL" to auto-populate standard sizes.</p>
                                        <button type="button" @click="generateStandardSizes()" class="px-4 py-2 bg-purple-600 text-white rounded-xl text-xs font-bold hover:bg-purple-700 cursor-pointer">
                                            Generate Standard Sizes
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Form Footer with Live Metric Summaries & Save CTA --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-2">
                        <div class="flex items-center gap-3 text-xs font-bold text-slate-500">
                            <span class="bg-slate-100 px-3 py-1.5 rounded-xl border border-slate-200">
                                Total Variants: <strong class="text-slate-900" x-text="variants.length"></strong>
                            </span>
                            <span class="bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-xl border border-emerald-200">
                                Total Units: <strong class="text-emerald-950" x-text="variants.reduce((acc, v) => acc + (parseInt(v.stock) || 0), 0)"></strong> in stock
                            </span>
                        </div>
                        
                        <button type="submit" class="px-7 py-3 bg-slate-900 hover:bg-slate-800 text-white text-xs font-extrabold rounded-xl transition-all shadow-sm hover:shadow-md flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span>Save All Variants</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        {{-- Right Column: Media & Photos Studio (Span 1) --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs">
                
                <div class="flex items-center justify-between gap-3 mb-5 pb-3 border-b border-slate-100 flex-wrap">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
                            📷
                        </div>
                        <div>
                            <h3 class="text-xs font-heading font-extrabold text-slate-900 uppercase tracking-wider">Product Photos</h3>
                            <p class="text-[11px] text-slate-400">{{ $product->images->count() }} photos uploaded</p>
                        </div>
                    </div>

                    {{-- ✨ AI Model Shoot Action --}}
                    <button type="button" 
                            @click="openAiModal('photoshoot')" 
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white text-[11px] font-bold shadow-xs hover:shadow-md transition-all active:scale-95 cursor-pointer">
                        <span class="animate-pulse text-amber-300">✨</span>
                        <span>AI Model Shoot</span>
                    </button>
                </div>
                
                {{-- Upload Dropzone --}}
                <form action="{{ route('admin.products.images.store', $product) }}" method="POST" enctype="multipart/form-data" class="mb-5">
                    @csrf
                    <label class="block relative border-2 border-dashed border-slate-200 hover:border-slate-400 rounded-2xl p-5 text-center bg-slate-50/50 hover:bg-slate-50 transition-colors group cursor-pointer">
                        <input type="file" name="images[]" multiple accept="image/*" required class="sr-only" onchange="this.form.submit()">
                        <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white text-slate-600 shadow-2xs border border-slate-200 mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        </div>
                        <h4 class="text-xs font-bold text-slate-900 mb-0.5">Upload Lookbook Photos</h4>
                        <p class="text-[10px] text-slate-400">Click or drag JPG, PNG, WEBP</p>
                    </label>
                </form>

                {{-- Images Grid --}}
                <div class="grid grid-cols-2 gap-3">
                    @forelse($product->images as $image)
                        <div class="group relative rounded-xl border border-slate-200 overflow-hidden bg-slate-100 aspect-[3/4] shadow-2xs">
                            <img src="{{ $image->url }}" alt="Product Image" class="w-full h-full object-cover">
                            
                            {{-- Primary Badge --}}
                            @if($image->is_primary)
                                <div class="absolute top-2 left-2 bg-slate-900/90 backdrop-blur-xs text-white text-[9px] font-black px-2 py-0.5 rounded-md shadow-md">
                                    ★ PRIMARY
                                </div>
                            @endif

                            {{-- Overlay Actions on Hover --}}
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-between p-2.5 text-white">
                                <div class="flex justify-end">
                                    {{-- Delete Image --}}
                                    <form action="{{ route('admin.images.destroy', $image) }}" method="POST" onsubmit="return confirm('Delete this photo?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg bg-rose-600 text-white hover:bg-rose-700 transition-colors cursor-pointer shadow-md" title="Delete Photo">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </form>
                                </div>

                                <div>
                                    @if(!$image->is_primary)
                                        <form action="{{ route('admin.images.primary', $image) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full py-1.5 bg-white text-slate-900 hover:bg-slate-100 rounded-lg text-[10px] font-black shadow-md cursor-pointer transition-colors">
                                                ★ Set Primary
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-8 text-slate-400 text-xs">
                            No photos uploaded yet. Click above to add lookbook images.
                        </div>
                    @endforelse
                </div>

            </div>
        </div>

    </div>
    {{-- ✨ AI Assistant Modal (Gemini Copy & Photoshoot) --}}
    @include('admin.pages.products.partials.ai_modal')

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('variantsManager', (initialVariants, baseSku, basePrice) => ({
            name: '{{ addslashes($product->name) }}',
            variants: initialVariants.map(v => ({...v, temp_id: null})),
            baseSku: (baseSku && baseSku !== 'TX' && baseSku !== 'TX-GEN') ? baseSku : 'TX-VAR',
            basePrice: basePrice || 0,
            bulkStock: 50,
            ...productAiHelper(),

            addVariant() {
                const nextNum = this.variants.length + 1;
                this.variants.push({
                    temp_id: Date.now() + Math.random(),
                    id: null,
                    size: '',
                    color: this.variants[0]?.color || '',
                    sku: this.baseSku + '-' + nextNum,
                    price: '',
                    stock: 25,
                    is_active: 1
                });
            },

            generateStandardSizes() {
                const sizes = ['S', 'M', 'L', 'XL', 'XXL'];
                const defaultColor = this.variants[0]?.color || '';
                sizes.forEach(size => {
                    if (!this.variants.some(v => v.size === size)) {
                        this.variants.push({
                            temp_id: Date.now() + Math.random(),
                            id: null,
                            size: size,
                            color: defaultColor,
                            sku: this.baseSku + '-' + size,
                            price: '',
                            stock: 25,
                            is_active: 1
                        });
                    }
                });
            },

            generateCleanSkus() {
                this.variants.forEach((v, idx) => {
                    const sizePart = (v.size || 'V' + (idx + 1)).toUpperCase().replace(/\s+/g, '');
                    v.sku = this.baseSku + '-' + sizePart;
                });
            },

            applyBulkStock() {
                if (this.bulkStock !== null && this.bulkStock >= 0) {
                    this.variants.forEach(v => v.stock = this.bulkStock);
                }
            },

            removeVariant(index) {
                this.variants.splice(index, 1);
            }
        }));
    });
</script>
@endpush
