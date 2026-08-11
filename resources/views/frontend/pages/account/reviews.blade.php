@extends('frontend.layouts.account')

@section('account_content')
<div class="bg-white border border-brand-border p-6 sm:p-10 shadow-sm">
    <h2 class="text-2xl font-heading font-black text-brand-dark uppercase tracking-tight mb-8">My Reviews</h2>

    @if($reviews->count() > 0)
        <div class="divide-y divide-brand-border">
            @foreach($reviews as $review)
                <div class="py-6 first:pt-0">
                    <div class="flex flex-col md:flex-row gap-6">
                        
                        {{-- Product Info --}}
                        <div class="w-full md:w-1/3 lg:w-1/4 shrink-0">
                            <a href="{{ route('frontend.products.show', $review->product->slug) }}" class="group flex gap-4">
                                <div class="w-16 h-20 bg-brand-light border border-brand-border shrink-0 overflow-hidden rounded">
                                    @if($review->product->primaryImage)
                                        <img src="{{ $review->product->primaryImage->url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-brand-dark group-hover:text-brand-text transition-colors line-clamp-2">{{ $review->product->name }}</h4>
                                </div>
                            </a>
                        </div>
                        
                        {{-- Review Content --}}
                        <div class="w-full flex-1">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex text-brand-dark">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'fill-current' : 'text-gray-200 fill-current' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                                <span class="text-xs text-brand-muted">{{ $review->created_at->format('M d, Y') }}</span>
                            </div>
                            
                            @if($review->title)
                                <h4 class="font-bold text-brand-dark text-sm uppercase tracking-wider mb-2">{{ $review->title }}</h4>
                            @endif
                            <p class="text-brand-muted text-sm leading-relaxed mb-3">{{ $review->body }}</p>

                            <div class="inline-flex items-center px-2 py-1 rounded text-xs font-medium capitalize
                                @if($review->status === 'approved') bg-green-100 text-green-800
                                @elseif($review->status === 'rejected') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif
                            ">
                                Status: {{ $review->status }}
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-8 border-t border-brand-border pt-4">
            {{ $reviews->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-brand-muted mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            <h3 class="text-lg font-bold text-brand-dark uppercase mb-2">No Reviews Yet</h3>
            <p class="text-brand-muted text-sm mb-6">You haven't reviewed any products yet.</p>
            <a href="{{ route('frontend.products.index') }}" class="btn-primary">Shop Now</a>
        </div>
    @endif
</div>
@endsection
