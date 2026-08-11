@extends('admin.layouts.app')

@section('title', 'Edit Coupon')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Edit Coupon: <span class="font-mono">{{ $coupon->code }}</span></h1>
        <p class="text-sm text-gray-500 mt-1">Update the coupon details.</p>
    </div>

    <form method="POST" action="{{ route('admin.coupons.update', $coupon) }}" class="bg-white border border-gray-200 shadow-sm p-6 space-y-5">
        @csrf @method('PUT')
        
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm rounded">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="grid grid-cols-2 gap-5">
            <div class="col-span-2">
                <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase tracking-wider">Coupon Code *</label>
                <input type="text" name="code" value="{{ old('code', $coupon->code) }}" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-gray-900 uppercase" style="text-transform:uppercase" required>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase tracking-wider">Discount Type *</label>
                <select name="type" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-gray-900" required>
                    <option value="percent" {{ old('type', $coupon->type) == 'percent' ? 'selected' : '' }}>Percentage (%)</option>
                    <option value="flat" {{ old('type', $coupon->type) == 'flat' ? 'selected' : '' }}>Flat Amount (₹)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase tracking-wider">Discount Value *</label>
                <input type="number" name="value" value="{{ old('value', $coupon->value) }}" step="0.01" min="0.01" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-gray-900" required>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase tracking-wider">Max Discount (₹) <span class="font-normal text-gray-400">(optional)</span></label>
                <input type="number" name="max_discount_amount" value="{{ old('max_discount_amount', $coupon->max_discount_amount) }}" step="0.01" min="0" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-gray-900">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase tracking-wider">Min Order (₹)</label>
                <input type="number" name="min_order_amount" value="{{ old('min_order_amount', $coupon->min_order_amount) }}" step="0.01" min="0" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-gray-900">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase tracking-wider">Max Total Uses <span class="font-normal text-gray-400">(blank = unlimited)</span></label>
                <input type="number" name="max_uses" value="{{ old('max_uses', $coupon->max_uses) }}" min="1" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-gray-900">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase tracking-wider">Max Uses Per User</label>
                <input type="number" name="max_uses_per_user" value="{{ old('max_uses_per_user', $coupon->max_uses_per_user) }}" min="1" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-gray-900">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase tracking-wider">Start Date</label>
                <input type="datetime-local" name="starts_at" value="{{ old('starts_at', $coupon->starts_at?->format('Y-m-d\TH:i')) }}" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-gray-900">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase tracking-wider">Expiry Date</label>
                <input type="datetime-local" name="expires_at" value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d\TH:i')) }}" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-gray-900">
            </div>

            <div class="col-span-2">
                <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase tracking-wider">Description</label>
                <input type="text" name="description" value="{{ old('description', $coupon->description) }}" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-gray-900">
            </div>

            <div class="col-span-2 flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }} class="w-4 h-4">
                <label for="is_active" class="text-sm font-medium text-gray-700">Coupon is Active</label>
            </div>
        </div>

        <div class="flex items-center gap-3 pt-3 border-t border-gray-100">
            <button type="submit" class="bg-gray-900 text-white px-5 py-2 text-sm font-semibold hover:bg-gray-700 transition-colors">Update Coupon</button>
            <a href="{{ route('admin.coupons.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
        </div>
    </form>
</div>
@endsection
