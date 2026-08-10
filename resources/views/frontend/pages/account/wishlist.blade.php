@extends('frontend.layouts.account')

@section('account_content')
<div class="bg-white border border-brand-border p-6 sm:p-10 shadow-sm">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-2xl font-heading font-black text-brand-dark uppercase tracking-tight">My Wishlist</h2>
    </div>

    @if($wishlists->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6">
            @foreach($wishlists as $wishlist)
                <div class="group relative">
                    <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-brand-light lg:aspect-none border border-brand-border group-hover:opacity-75 lg:h-80 relative">
                        @if($wishlist->product && $wishlist->product->primaryImage)
                            <img src="{{ $wishlist->product->primaryImage->url }}" alt="{{ $wishlist->product->name }}" class="h-full w-full object-cover object-center lg:h-full lg:w-full">
                        @else
                            <div class="h-full w-full flex items-center justify-center text-brand-muted">No Image</div>
                        @endif

                        {{-- Remove from Wishlist Button --}}
                        <form action="{{ route('account.wishlist.toggle') }}" method="POST" class="absolute top-2 right-2">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $wishlist->product_id }}">
                            <button type="submit" class="p-2 bg-white rounded-full text-red-500 shadow hover:text-red-600 transition-colors">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z"/></svg>
                            </button>
                        </form>
                    </div>
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-sm font-bold text-brand-dark uppercase tracking-wider">
                                <a href="{{ route('frontend.products.show', $wishlist->product->slug) }}">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    {{ $wishlist->product->name }}
                                </a>
                            </h3>
                            <p class="mt-1 text-sm text-brand-muted">₹{{ number_format($wishlist->product->price, 2) }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $wishlists->links() }}
        </div>
    @else
        <div class="text-center py-16 border-2 border-dashed border-brand-border rounded-xl">
            <svg class="mx-auto h-12 w-12 text-brand-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-bold text-brand-dark uppercase tracking-wider">Wishlist Empty</h3>
            <p class="mt-1 text-sm text-brand-muted">You haven't added any products to your wishlist yet.</p>
            <div class="mt-6">
                <a href="{{ route('frontend.products.index') }}" class="btn-primary py-2 px-6">
                    Discover Styles
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
