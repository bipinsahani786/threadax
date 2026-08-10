@extends('frontend.layouts.account')

@section('account_content')
<div class="bg-white border border-brand-border p-6 sm:p-10 shadow-sm max-w-3xl">
    <h2 class="text-2xl font-heading font-black text-brand-dark uppercase tracking-tight mb-8">Profile Details</h2>

    <form action="{{ route('account.profile.update') }}" method="POST" class="space-y-6">
        @csrf
        
        <div>
            <label for="name" class="block text-xs font-bold uppercase tracking-widest text-brand-dark mb-2">Full Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                class="w-full bg-brand-light border border-brand-border px-4 py-3 text-brand-dark focus:outline-none focus:border-brand-text focus:ring-1 focus:ring-brand-text">
            @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-widest text-brand-dark mb-2">Email Address</label>
            <input type="email" name="email" id="email" value="{{ $user->email }}" disabled
                class="w-full bg-gray-100 border border-brand-border px-4 py-3 text-brand-muted cursor-not-allowed">
            <p class="text-xs text-brand-muted mt-2">Email address cannot be changed. Contact support if you need to update it.</p>
        </div>

        <div>
            <label for="phone" class="block text-xs font-bold uppercase tracking-widest text-brand-dark mb-2">Phone Number</label>
            <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                class="w-full bg-brand-light border border-brand-border px-4 py-3 text-brand-dark focus:outline-none focus:border-brand-text focus:ring-1 focus:ring-brand-text">
            @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div class="pt-4 border-t border-brand-border">
            <button type="submit" class="btn-primary w-full sm:w-auto px-8 py-3">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
