@extends('frontend.layouts.account')

@section('account_content')

<div class="flex justify-between items-center mb-5 sm:mb-6">
    <div>
        <h2 class="text-base sm:text-lg font-heading font-bold text-brand-dark">Saved Addresses</h2>
        <p class="text-xs sm:text-sm text-brand-muted mt-0.5">Manage your delivery and billing addresses.</p>
    </div>
    <a href="#add-address" class="inline-flex items-center gap-1.5 bg-brand-dark text-white text-xs font-bold px-3.5 py-2 rounded-lg hover:bg-brand-text transition-colors">
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
        <span>Add Address</span>
    </a>
</div>

@if($addresses->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5 mb-8 sm:mb-10">
        @foreach($addresses as $address)
            <div class="bg-white border {{ $address->is_default ? 'border-brand-text ring-1 ring-brand-text/30 shadow-sm' : 'border-brand-border hover:border-brand-text/40' }} rounded-xl p-4 sm:p-5 relative transition-all group">
                @if($address->is_default)
                    <div class="absolute top-0 right-0 bg-brand-dark text-white text-[9px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-bl-lg rounded-tr-xl flex items-center gap-1">
                        <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        Default
                    </div>
                @endif
                
                <div class="flex items-start gap-3 mb-3">
                    <span class="w-8 h-8 rounded-lg {{ $address->is_default ? 'bg-brand-dark text-white' : 'bg-brand-light text-brand-dark' }} flex items-center justify-center shrink-0">
                        @if($address->type === 'work')
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z"/></svg>
                        @else
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                        @endif
                    </span>
                    <div>
                        <h3 class="font-bold text-brand-dark text-sm capitalize">{{ $address->name }}</h3>
                        <span class="inline-block px-2 py-0.5 mt-0.5 rounded text-[9px] font-bold uppercase tracking-wider {{ $address->type === 'work' ? 'bg-blue-50 text-blue-600' : 'bg-gray-100 text-gray-600' }}">{{ $address->type }}</span>
                    </div>
                </div>

                <address class="not-italic text-xs sm:text-sm text-brand-muted space-y-1 mb-4 pl-11 leading-relaxed">
                    <span class="block text-brand-dark font-medium">{{ $address->line1 }}</span>
                    @if($address->line2) <span class="block">{{ $address->line2 }}</span> @endif
                    @if($address->landmark) <span class="block text-xs text-brand-muted">Landmark: {{ $address->landmark }}</span> @endif
                    <span class="block text-brand-dark">{{ $address->city }}, {{ $address->state }} {{ $address->pincode }}</span>
                    <span class="block pt-1 flex items-center gap-1.5 text-xs text-brand-dark">
                        <svg class="w-3.5 h-3.5 text-brand-muted shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                        {{ $address->phone }}
                        @if($address->alternate_phone)
                            <span class="text-xs text-brand-muted">/ {{ $address->alternate_phone }}</span>
                        @endif
                    </span>
                </address>
                
                <div class="flex gap-4 pt-3 border-t border-brand-border pl-11">
                    <form action="{{ route('account.addresses.destroy', $address->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this address?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs font-semibold text-red-500 hover:text-red-700 flex items-center gap-1 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            Remove
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-10 sm:py-12 bg-white border border-dashed border-brand-border rounded-xl mb-8">
        <div class="w-12 h-12 rounded-full bg-brand-light flex items-center justify-center mx-auto mb-3">
            <svg class="w-6 h-6 text-brand-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
        </div>
        <h3 class="text-sm font-bold text-brand-dark">No Addresses Found</h3>
        <p class="mt-1 text-xs text-brand-muted max-w-xs mx-auto">You haven't saved any addresses yet. Add one below for faster checkout.</p>
    </div>
@endif

{{-- Add New Address Form --}}
<div class="bg-white rounded-xl border border-brand-border shadow-sm overflow-hidden" id="add-address">
    <div class="px-5 sm:px-6 py-4 border-b border-brand-border bg-brand-off-white">
        <h3 class="text-sm sm:text-base font-heading font-bold text-brand-dark">Add New Address</h3>
        <p class="text-xs text-brand-muted mt-0.5">Please provide accurate details to ensure smooth delivery.</p>
    </div>
    
    <div class="p-5 sm:p-6">
        <form action="{{ route('account.addresses.store') }}" method="POST" class="space-y-4 sm:space-y-5">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                {{-- Name --}}
                <div>
                    <label for="name" class="block text-xs font-semibold text-brand-dark mb-1.5">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                        class="w-full bg-brand-off-white border border-brand-border rounded-lg px-3.5 py-2.5 text-brand-dark focus:bg-white focus:ring-1 focus:ring-brand-dark focus:border-brand-dark transition-colors text-xs sm:text-sm placeholder:text-brand-muted" placeholder="e.g. John Doe">
                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                {{-- Phone --}}
                <div>
                    <label for="phone" class="block text-xs font-semibold text-brand-dark mb-1.5">Phone Number <span class="text-red-500">*</span></label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required 
                        class="w-full bg-brand-off-white border border-brand-border rounded-lg px-3.5 py-2.5 text-brand-dark focus:bg-white focus:ring-1 focus:ring-brand-dark focus:border-brand-dark transition-colors text-xs sm:text-sm placeholder:text-brand-muted" placeholder="10-digit mobile number">
                    @error('phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                {{-- Alternate Phone --}}
                <div>
                    <label for="alternate_phone" class="block text-xs font-semibold text-brand-dark mb-1.5">Alternate Phone (Optional)</label>
                    <input type="tel" name="alternate_phone" id="alternate_phone" value="{{ old('alternate_phone') }}" 
                        class="w-full bg-brand-off-white border border-brand-border rounded-lg px-3.5 py-2.5 text-brand-dark focus:bg-white focus:ring-1 focus:ring-brand-dark focus:border-brand-dark transition-colors text-xs sm:text-sm placeholder:text-brand-muted" placeholder="Alternate contact number">
                    @error('alternate_phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Type --}}
                <div>
                    <label for="type" class="block text-xs font-semibold text-brand-dark mb-1.5">Address Type <span class="text-red-500">*</span></label>
                    <select name="type" id="type" required 
                        class="w-full bg-brand-off-white border border-brand-border rounded-lg px-3.5 py-2.5 text-brand-dark focus:bg-white focus:ring-1 focus:ring-brand-dark focus:border-brand-dark transition-colors text-xs sm:text-sm">
                        <option value="home" {{ old('type') === 'home' ? 'selected' : '' }}>Home (All day delivery)</option>
                        <option value="work" {{ old('type') === 'work' ? 'selected' : '' }}>Work / Office (Delivery 9 AM - 6 PM)</option>
                        <option value="other" {{ old('type') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('type') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Line 1 --}}
                <div class="md:col-span-2">
                    <label for="line1" class="block text-xs font-semibold text-brand-dark mb-1.5">Flat, House no., Building, Company, Apartment <span class="text-red-500">*</span></label>
                    <input type="text" name="line1" id="line1" value="{{ old('line1') }}" required 
                        class="w-full bg-brand-off-white border border-brand-border rounded-lg px-3.5 py-2.5 text-brand-dark focus:bg-white focus:ring-1 focus:ring-brand-dark focus:border-brand-dark transition-colors text-xs sm:text-sm placeholder:text-brand-muted" placeholder="e.g. Flat 402, Royale Palms">
                    @error('line1') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Line 2 --}}
                <div class="md:col-span-2">
                    <label for="line2" class="block text-xs font-semibold text-brand-dark mb-1.5">Area, Street, Sector, Village (Optional)</label>
                    <input type="text" name="line2" id="line2" value="{{ old('line2') }}" 
                        class="w-full bg-brand-off-white border border-brand-border rounded-lg px-3.5 py-2.5 text-brand-dark focus:bg-white focus:ring-1 focus:ring-brand-dark focus:border-brand-dark transition-colors text-xs sm:text-sm placeholder:text-brand-muted" placeholder="e.g. Main Street, Sector 18">
                    @error('line2') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Landmark --}}
                <div>
                    <label for="landmark" class="block text-xs font-semibold text-brand-dark mb-1.5">Landmark (Optional)</label>
                    <input type="text" name="landmark" id="landmark" value="{{ old('landmark') }}" 
                        class="w-full bg-brand-off-white border border-brand-border rounded-lg px-3.5 py-2.5 text-brand-dark focus:bg-white focus:ring-1 focus:ring-brand-dark focus:border-brand-dark transition-colors text-xs sm:text-sm placeholder:text-brand-muted" placeholder="e.g. Near City Center Mall">
                    @error('landmark') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Pincode --}}
                <div>
                    <label for="pincode" class="block text-xs font-semibold text-brand-dark mb-1.5">6-Digit Pincode <span class="text-red-500">*</span></label>
                    <input type="text" name="pincode" id="pincode" value="{{ old('pincode') }}" required maxlength="6" 
                        class="w-full bg-brand-off-white border border-brand-border rounded-lg px-3.5 py-2.5 text-brand-dark focus:bg-white focus:ring-1 focus:ring-brand-dark focus:border-brand-dark transition-colors text-xs sm:text-sm placeholder:text-brand-muted" placeholder="e.g. 110001">
                    @error('pincode') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- City --}}
                <div>
                    <label for="city" class="block text-xs font-semibold text-brand-dark mb-1.5">City / Town <span class="text-red-500">*</span></label>
                    <input type="text" name="city" id="city" value="{{ old('city') }}" required 
                        class="w-full bg-brand-off-white border border-brand-border rounded-lg px-3.5 py-2.5 text-brand-dark focus:bg-white focus:ring-1 focus:ring-brand-dark focus:border-brand-dark transition-colors text-xs sm:text-sm placeholder:text-brand-muted" placeholder="e.g. Mumbai">
                    @error('city') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- State --}}
                <div>
                    <label for="state" class="block text-xs font-semibold text-brand-dark mb-1.5">State <span class="text-red-500">*</span></label>
                    <input type="text" name="state" id="state" value="{{ old('state') }}" required 
                        class="w-full bg-brand-off-white border border-brand-border rounded-lg px-3.5 py-2.5 text-brand-dark focus:bg-white focus:ring-1 focus:ring-brand-dark focus:border-brand-dark transition-colors text-xs sm:text-sm placeholder:text-brand-muted" placeholder="e.g. Maharashtra">
                    @error('state') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Default Address Checkbox --}}
            <div class="pt-2">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}
                        class="rounded border-brand-border text-brand-dark focus:ring-brand-dark w-4 h-4">
                    <span class="text-xs font-medium text-brand-dark">Set as default delivery address</span>
                </label>
            </div>

            {{-- Submit Button --}}
            <div class="pt-4 border-t border-brand-border flex items-center justify-end gap-3">
                <button type="submit" class="bg-brand-dark text-white font-bold text-xs uppercase tracking-wider px-6 py-2.5 rounded-lg hover:bg-brand-text transition-all">
                    Save Address
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
