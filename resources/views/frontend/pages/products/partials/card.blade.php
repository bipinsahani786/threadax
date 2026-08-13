<a href="{{ route('frontend.products.show', $product->slug) }}" class="group block">
    <div class="relative w-full aspect-[3/4] bg-brand-light overflow-hidden mb-4">
        @if($product->primary_image)
            <img src="{{ $product->primary_image->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
        @else
            <div class="w-full h-full flex items-center justify-center bg-gray-200">
                <span class="text-gray-400 text-sm">No Image</span>
            </div>
        @endif

        {{-- Premium Discount Badge --}}
        @if($product->discount_percent > 0)
            <div class="absolute top-3 left-3">
                <span class="inline-flex items-center gap-1 bg-gradient-to-r from-orange-500 to-red-500 text-white text-[10px] font-black px-2.5 py-1 uppercase tracking-wider rounded-sm shadow-lg">
                    {{ $product->discount_percent }}% OFF
                </span>
            </div>
        @endif

        <div class="absolute bottom-4 right-4 bg-white p-2 rounded-full shadow-lg opacity-0 translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 hover:bg-brand-text hover:text-white flex items-center justify-center z-10">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
        </div>
    </div>
    <h3 class="text-sm font-bold mb-1 line-clamp-1">{{ $product->name }}</h3>
    <p class="text-brand-muted text-xs mb-1">{{ $product->category->name ?? 'Streetwear' }}</p>

    {{-- Star Rating --}}
    @if($product->review_count > 0)
        <div class="flex items-center gap-1 mb-2">
            @for($i = 1; $i <= 5; $i++)
                <svg class="w-3 h-3 {{ $i <= round($product->average_rating) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            @endfor
            <span class="text-[10px] text-brand-muted">({{ $product->review_count }})</span>
        </div>
    @endif

    {{-- Price --}}
    <div class="flex items-center gap-2 flex-wrap">
        <p class="font-bold text-sm">₹{{ number_format($product->price) }}</p>
        @if($product->compare_price > $product->price)
            <p class="text-xs text-brand-muted line-through">₹{{ number_format($product->compare_price) }}</p>
        @endif
    </div>
</a>
