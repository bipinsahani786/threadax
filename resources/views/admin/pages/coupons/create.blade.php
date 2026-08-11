@extends('admin.layouts.app')

@section('title', 'Create Coupon')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Create Coupon</h1>
        <p class="text-sm text-gray-500 mt-1">Add a new discount coupon code.</p>
    </div>

    <form method="POST" action="{{ route('admin.coupons.store') }}" class="bg-white border border-gray-200 shadow-sm p-6 space-y-5">
        @csrf
        
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm rounded">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="grid grid-cols-2 gap-5">
            {{-- Code --}}
            <div class="col-span-2">
                <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase tracking-wider">Coupon Code *</label>
                <input type="text" name="code" value="{{ old('code') }}" placeholder="e.g. SUMMER20" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-gray-900 uppercase" style="text-transform:uppercase" required>
                <p class="text-xs text-gray-400 mt-1">Will be saved in UPPERCASE automatically.</p>
            </div>

            {{-- Type --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase tracking-wider">Discount Type *</label>
                <select name="type" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-gray-900" required>
                    <option value="percent" {{ old('type') == 'percent' ? 'selected' : '' }}>Percentage (%)</option>
                    <option value="flat" {{ old('type') == 'flat' ? 'selected' : '' }}>Flat Amount (₹)</option>
                </select>
            </div>

            {{-- Value --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase tracking-wider">Discount Value *</label>
                <input type="number" name="value" value="{{ old('value') }}" step="0.01" min="0.01" placeholder="e.g. 20 (for 20% or ₹20)" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-gray-900" required>
            </div>

            {{-- Max Discount (for percent type) --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase tracking-wider">Max Discount Amount (₹) <span class="font-normal text-gray-400">(optional — for % type)</span></label>
                <input type="number" name="max_discount_amount" value="{{ old('max_discount_amount') }}" step="0.01" min="0" placeholder="e.g. 500" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-gray-900">
            </div>

            {{-- Min Order --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase tracking-wider">Min Order Amount (₹)</label>
                <input type="number" name="min_order_amount" value="{{ old('min_order_amount', 0) }}" step="0.01" min="0" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-gray-900">
            </div>

            {{-- Max Uses --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase tracking-wider">Max Total Uses <span class="font-normal text-gray-400">(blank = unlimited)</span></label>
                <input type="number" name="max_uses" value="{{ old('max_uses') }}" min="1" placeholder="e.g. 100" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-gray-900">
            </div>

            {{-- Max Uses Per User --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase tracking-wider">Max Uses Per User</label>
                <input type="number" name="max_uses_per_user" value="{{ old('max_uses_per_user', 1) }}" min="1" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-gray-900">
            </div>

            {{-- Starts At --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase tracking-wider">Start Date <span class="font-normal text-gray-400">(optional)</span></label>
                <input type="datetime-local" name="starts_at" value="{{ old('starts_at') }}" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-gray-900">
            </div>

            {{-- Expires At --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase tracking-wider">Expiry Date <span class="font-normal text-gray-400">(optional)</span></label>
                <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-gray-900">
            </div>

            {{-- Description --}}
            <div class="col-span-2">
                <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase tracking-wider">Description <span class="font-normal text-gray-400">(internal note)</span></label>
                <input type="text" name="description" value="{{ old('description') }}" placeholder="e.g. Summer sale 20% off" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-gray-900">
            </div>

            {{-- Active --}}
            <div class="col-span-2 flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4">
                <label for="is_active" class="text-sm font-medium text-gray-700">Coupon is Active</label>
            </div>
        </div>

        <div class="flex items-center gap-3 pt-3 border-t border-gray-100">
            <button type="submit" class="bg-gray-900 text-white px-5 py-2 text-sm font-semibold hover:bg-gray-700 transition-colors">Create Coupon</button>
            <a href="{{ route('admin.coupons.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
        </div>
    </form>
</div>
@endsection
