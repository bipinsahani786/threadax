<x-mail::message>
# Hi {{ $cart->user->name }},

We noticed you left some great items in your cart. They are waiting for you!

<x-mail::table>
| Item       | Qty         | Price  |
| :--------- | :--------- | :----- |
@foreach($cart->items as $item)
@if($item->variant && $item->variant->product)
| {{ $item->variant->product->name }} ({{ $item->variant->size }} / {{ $item->variant->color }}) | {{ $item->quantity }} | ₹{{ $item->variant->price ?? $item->variant->product->price }} |
@endif
@endforeach
</x-mail::table>

<x-mail::button :url="route('frontend.checkout.index')">
Complete Your Checkout
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
