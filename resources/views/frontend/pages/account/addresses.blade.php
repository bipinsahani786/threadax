@extends('frontend.layouts.account')

@section('account_content')
<div class="bg-white border border-brand-border p-6 sm:p-10 shadow-sm">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-2xl font-heading font-black text-brand-dark uppercase tracking-tight">Saved Addresses</h2>
    </div>

    @if($addresses->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($addresses as $address)
                <div class="border border-brand-border rounded-lg p-6 relative">
                    @if($address->is_default)
                        <span class="absolute top-0 right-0 bg-brand-text text-white text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-bl-lg rounded-tr-lg">Default</span>
                    @endif
                    
                    <h3 class="font-bold text-brand-dark text-lg mb-2">{{ $address->name }}</h3>
                    <address class="not-italic text-sm text-brand-muted space-y-1 mb-6">
                        <span class="block">{{ $address->line1 }}</span>
                        @if($address->line2) <span class="block">{{ $address->line2 }}</span> @endif
                        <span class="block">{{ $address->city }}, {{ $address->state }} {{ $address->pincode }}</span>
                        <span class="block mt-2">Phone: {{ $address->phone }}</span>
                    </address>
                    
                    <div class="flex gap-4">
                        <form action="{{ route('account.addresses.destroy', $address->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this address?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-bold text-red-500 hover:text-red-700 uppercase tracking-wider">Remove</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-16 border-2 border-dashed border-brand-border rounded-xl">
            <svg class="mx-auto h-12 w-12 text-brand-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <h3 class="mt-2 text-sm font-bold text-brand-dark uppercase tracking-wider">No addresses</h3>
            <p class="mt-1 text-sm text-brand-muted">You haven't saved any addresses yet.</p>
        </div>
    @endif

    {{-- Add New Address Form --}}
    <div class="mt-12 pt-10 border-t border-brand-border">
        <h3 class="text-xl font-heading font-black text-brand-dark uppercase tracking-tight mb-6">Add New Address</h3>
        
        <form action="{{ route('account.addresses.store') }}" method="POST" class="max-w-2xl">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="name" class="block text-xs font-bold text-brand-dark uppercase tracking-wider mb-2">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full border-brand-border focus:ring-brand-text focus:border-brand-text text-sm">
                    @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="phone" class="block text-xs font-bold text-brand-dark uppercase tracking-wider mb-2">Phone Number</label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required class="w-full border-brand-border focus:ring-brand-text focus:border-brand-text text-sm">
                    @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="street" class="block text-xs font-bold text-brand-dark uppercase tracking-wider mb-2">Street Address</label>
                <input type="text" name="street" id="street" value="{{ old('street') }}" required class="w-full border-brand-border focus:ring-brand-text focus:border-brand-text text-sm" placeholder="House number and street name">
                @error('street') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label for="city" class="block text-xs font-bold text-brand-dark uppercase tracking-wider mb-2">City</label>
                    <input type="text" name="city" id="city" value="{{ old('city') }}" required class="w-full border-brand-border focus:ring-brand-text focus:border-brand-text text-sm">
                    @error('city') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="state" class="block text-xs font-bold text-brand-dark uppercase tracking-wider mb-2">State</label>
                    <input type="text" name="state" id="state" value="{{ old('state') }}" required class="w-full border-brand-border focus:ring-brand-text focus:border-brand-text text-sm">
                    @error('state') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="pincode" class="block text-xs font-bold text-brand-dark uppercase tracking-wider mb-2">PIN Code</label>
                    <input type="text" name="pincode" id="pincode" value="{{ old('pincode') }}" required class="w-full border-brand-border focus:ring-brand-text focus:border-brand-text text-sm">
                    @error('pincode') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mb-8">
                <label for="type" class="block text-xs font-bold text-brand-dark uppercase tracking-wider mb-2">Address Type</label>
                <select name="type" id="type" class="w-full md:w-1/3 border-brand-border focus:ring-brand-text focus:border-brand-text text-sm">
                    <option value="home" {{ old('type') == 'home' ? 'selected' : '' }}>Home (All Day Delivery)</option>
                    <option value="work" {{ old('type') == 'work' ? 'selected' : '' }}>Work (Delivery between 10 AM - 5 PM)</option>
                    <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn-primary">
                Save Address
            </button>
        </form>
    </div>
</div>
@endsection
