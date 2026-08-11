@extends('admin.layouts.app')

@section('title', 'Coupons')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Coupon Codes</h1>
            <p class="text-sm text-gray-500 mt-1">Manage discount coupons for your store.</p>
        </div>
        <a href="{{ route('admin.coupons.create') }}" class="inline-flex items-center gap-2 bg-gray-900 text-white px-4 py-2 text-sm font-semibold hover:bg-gray-700 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Coupon
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 text-sm rounded">{{ session('success') }}</div>
    @endif

    {{-- Table --}}
    <div class="bg-white border border-gray-200 shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Code</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Type / Value</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Min Order</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Uses</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Expiry</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Status</th>
                    <th class="px-6 py-3 text-right font-semibold text-gray-600 uppercase tracking-wider text-xs">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($coupons as $coupon)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-mono font-bold text-gray-900 bg-gray-100 px-2 py-1 rounded text-xs">{{ $coupon->code }}</span>
                            @if($coupon->description)
                                <p class="text-xs text-gray-500 mt-1">{{ $coupon->description }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-semibold">
                            @if($coupon->type === 'percent')
                                <span class="text-green-700">{{ $coupon->value }}% OFF</span>
                                @if($coupon->max_discount_amount)
                                    <span class="text-xs text-gray-400 block">Max ₹{{ number_format($coupon->max_discount_amount, 0) }}</span>
                                @endif
                            @else
                                <span class="text-green-700">₹{{ number_format($coupon->value, 0) }} OFF</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-600">₹{{ number_format($coupon->min_order_amount, 0) }}</td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $coupon->used_count }} / {{ $coupon->max_uses ?? '∞' }}
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs">
                            {{ $coupon->expires_at ? $coupon->expires_at->format('d M Y') : 'No expiry' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($coupon->isValid())
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Active</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-600 text-xs font-semibold rounded-full">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-gray-600 hover:text-gray-900 text-xs font-medium">Edit</a>
                            <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" class="inline" onsubmit="return confirm('Delete this coupon?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            No coupons yet. <a href="{{ route('admin.coupons.create') }}" class="text-gray-900 underline">Create your first coupon</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($coupons->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">{{ $coupons->links() }}</div>
        @endif
    </div>
</div>
@endsection
