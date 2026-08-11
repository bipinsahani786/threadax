<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $coupons = Coupon::latest()->paginate(15);
        return view('admin.pages.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.pages.coupons.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'                => 'required|string|unique:coupons,code|max:50',
            'type'                => 'required|in:percent,flat',
            'value'               => 'required|numeric|min:0.01',
            'min_order_amount'    => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'max_uses'            => 'nullable|integer|min:1',
            'max_uses_per_user'   => 'nullable|integer|min:1',
            'starts_at'           => 'nullable|date',
            'expires_at'          => 'nullable|date|after_or_equal:starts_at',
            'description'         => 'nullable|string|max:255',
            'is_active'           => 'boolean',
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['max_uses_per_user'] = $validated['max_uses_per_user'] ?? 1;

        Coupon::create($validated);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created successfully.');
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.pages.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code'                => 'required|string|unique:coupons,code,' . $coupon->id . '|max:50',
            'type'                => 'required|in:percent,flat',
            'value'               => 'required|numeric|min:0.01',
            'min_order_amount'    => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'max_uses'            => 'nullable|integer|min:1',
            'max_uses_per_user'   => 'nullable|integer|min:1',
            'starts_at'           => 'nullable|date',
            'expires_at'          => 'nullable|date|after_or_equal:starts_at',
            'description'         => 'nullable|string|max:255',
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->boolean('is_active', false);

        $coupon->update($validated);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon deleted.');
    }
}
