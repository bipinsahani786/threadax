@extends('admin.layouts.app')

@section('title', 'Manage Variants & Images - ' . $product->name)
@section('page-title')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.products.index') }}" class="text-slate-400 hover:text-slate-900 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <span>Manage: {{ $product->name }}</span>
    </div>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    
    {{-- Header Actions --}}
    <div class="flex justify-between items-center bg-white p-4 rounded-[20px] shadow-[0_4px_24px_rgba(0,0,0,0.03)] border border-slate-100">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center shrink-0">
                @if($product->primary_image)
                    <img src="{{ $product->primary_image->url }}" class="w-full h-full object-cover" alt="Product Image">
                @else
                    <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                @endif
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-900 line-clamp-1">{{ $product->name }}</h2>
                <p class="text-sm text-slate-500">Base SKU: {{ $product->sku ?? 'N/A' }} &bull; Price: ₹{{ number_format($product->price) }}</p>
            </div>
        </div>
        <a href="{{ route('admin.products.edit', $product) }}" class="px-4 py-2 bg-slate-50 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-100 transition-colors border border-slate-200 shrink-0">
            Edit Basic Info
        </a>
    </div>

    {{-- Grid Layout --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        
        {{-- Left: Variants Manager (Span 2) --}}
        <div class="xl:col-span-2">
            <div class="bg-white rounded-[24px] shadow-[0_4px_24px_rgba(0,0,0,0.03)] border border-slate-100 p-8"
                 x-data="variantsManager({{ Js::from($product->variants) }})">
                
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 font-heading">Product Variants</h3>
                        <p class="text-sm text-slate-500 mt-1">Manage sizes, colors, and specific inventory.</p>
                    </div>
                    <button type="button" @click="addVariant()" class="px-4 py-2 bg-slate-900 text-white text-sm font-bold rounded-xl hover:bg-slate-800 transition-colors flex items-center gap-2 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add Variant
                    </button>
                </div>

                <form action="{{ route('admin.products.variants.store', $product) }}" method="POST">
                    @csrf
                    
                    <div class="overflow-x-auto rounded-xl border border-slate-200 mb-6">
                        <table class="w-full text-left text-sm text-slate-600">
                            <thead class="bg-slate-50/80 text-xs uppercase font-bold text-slate-400 border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3 min-w-[120px]">Size</th>
                                    <th class="px-4 py-3 min-w-[120px]">Color</th>
                                    <th class="px-4 py-3 min-w-[120px]">SKU</th>
                                    <th class="px-4 py-3 min-w-[100px]">Price (₹)</th>
                                    <th class="px-4 py-3 min-w-[100px]">Stock</th>
                                    <th class="px-4 py-3 text-center">Active</th>
                                    <th class="px-4 py-3 text-right"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                <template x-for="(variant, index) in variants" :key="variant.temp_id || variant.id">
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        {{-- Hidden ID for existing variants --}}
                                        <input type="hidden" :name="`variants[${index}][id]`" :value="variant.id">
                                        
                                        <td class="px-4 py-3">
                                            <input type="text" :name="`variants[${index}][size]`" x-model="variant.size" placeholder="e.g. XL" 
                                                   class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-sm focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none">
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="text" :name="`variants[${index}][color]`" x-model="variant.color" placeholder="e.g. Black" 
                                                   class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-sm focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none">
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="text" :name="`variants[${index}][sku]`" x-model="variant.sku" placeholder="SKU" 
                                                   class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-sm focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none">
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="number" step="0.01" :name="`variants[${index}][price]`" x-model="variant.price" placeholder="Default" 
                                                   class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-sm focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none">
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="number" :name="`variants[${index}][stock]`" x-model="variant.stock" required min="0" 
                                                   class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-sm focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none">
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <input type="hidden" :name="`variants[${index}][is_active]`" value="0">
                                            <input type="checkbox" :name="`variants[${index}][is_active]`" value="1" x-model="variant.is_active" 
                                                   class="w-4 h-4 text-slate-900 bg-slate-100 border-slate-300 rounded focus:ring-slate-900 focus:ring-2 cursor-pointer">
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <button type="button" @click="removeVariant(index)" class="p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="variants.length === 0" x-cloak>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-500 text-sm">
                                        No variants added yet. Click "Add Variant" to start.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-slate-900 text-white text-sm font-bold rounded-xl hover:bg-slate-800 transition-shadow shadow-[0_4px_12px_rgba(0,0,0,0.1)] hover:shadow-[0_4px_16px_rgba(0,0,0,0.15)] flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Save All Variants
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        {{-- Right: Images Manager (Span 1) --}}
        <div class="space-y-8">
            <div class="bg-white rounded-[24px] shadow-[0_4px_24px_rgba(0,0,0,0.03)] border border-slate-100 p-8">
                <h3 class="text-lg font-bold text-slate-900 font-heading mb-6">Product Images</h3>
                
                {{-- Upload Form --}}
                <form action="{{ route('admin.products.images.store', $product) }}" method="POST" enctype="multipart/form-data" class="mb-8">
                    @csrf
                    <div class="relative border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center hover:bg-slate-50 transition-colors group">
                        <input type="file" name="images[]" id="images" multiple accept="image/*" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="this.form.submit()">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-3 group-hover:bg-slate-200 group-hover:text-slate-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        </div>
                        <p class="text-sm font-bold text-slate-900">Click to upload images</p>
                        <p class="text-xs text-slate-500 mt-1">PNG, JPG or WEBP (Max 2MB)</p>
                    </div>
                    @error('images.*') <p class="mt-2 text-xs text-red-500 text-center">{{ $message }}</p> @enderror
                </form>

                {{-- Images Grid --}}
                @if($product->images->count() > 0)
                    <div class="grid grid-cols-2 gap-4">
                        @foreach($product->images as $image)
                            <div class="relative group rounded-xl overflow-hidden border border-slate-200 aspect-[3/4]">
                                <img src="{{ $image->url }}" alt="Product Image" class="w-full h-full object-cover">
                                
                                {{-- Primary Badge --}}
                                @if($image->is_primary)
                                    <div class="absolute top-2 left-2 px-2 py-1 bg-green-500 text-white text-[10px] font-bold uppercase rounded shadow-sm">Primary</div>
                                @endif
                                
                                {{-- Overlay Actions --}}
                                <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-2">
                                    
                                    @if(!$image->is_primary)
                                        <form action="{{ route('admin.images.primary', $image) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-white text-slate-900 text-xs font-bold rounded-lg hover:bg-slate-100 transition-colors">
                                                Set Primary
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <form action="{{ route('admin.images.destroy', $image) }}" method="POST" onsubmit="return confirm('Delete this image?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-red-500 text-white text-xs font-bold rounded-lg hover:bg-red-600 transition-colors">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-slate-500 text-sm">
                        No images uploaded yet.
                    </div>
                @endif

            </div>
        </div>
        
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('variantsManager', (initialVariants) => ({
            variants: initialVariants,
            
            addVariant() {
                this.variants.push({
                    temp_id: Date.now(),
                    id: null,
                    size: '',
                    color: '',
                    sku: '',
                    price: '',
                    stock: 0,
                    is_active: 1
                });
            },
            
            removeVariant(index) {
                if(confirm('Are you sure you want to remove this row? It will be deleted upon save.')) {
                    this.variants.splice(index, 1);
                }
            }
        }));
    });
</script>
@endpush
@endsection
