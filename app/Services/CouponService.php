<?php

namespace App\Services;

use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;

class CouponService
{
    /**
     * Validate a coupon code against a given subtotal.
     * Returns ['valid' => true, 'coupon' => ..., 'discount' => ...] or ['valid' => false, 'message' => ...]
     */
    public function validate(string $code, float $subtotal): array
    {
        $coupon = Coupon::where('code', strtoupper(trim($code)))->first();

        if (! $coupon) {
            return ['valid' => false, 'message' => 'Invalid coupon code.'];
        }

        if (! $coupon->isValid()) {
            return ['valid' => false, 'message' => 'This coupon has expired or is no longer active.'];
        }

        if ($subtotal < $coupon->min_order_amount) {
            return [
                'valid'   => false,
                'message' => 'Minimum order amount of ₹' . number_format($coupon->min_order_amount, 2) . ' required for this coupon.',
            ];
        }

        // Check per-user usage
        if (Auth::check() && $coupon->max_uses_per_user) {
            $userUsageCount = $coupon->usages()
                ->where('user_id', Auth::id())
                ->count();

            if ($userUsageCount >= $coupon->max_uses_per_user) {
                return ['valid' => false, 'message' => 'You have already used this coupon.'];
            }
        }

        $discount = $coupon->calculateDiscount($subtotal);

        return [
            'valid'    => true,
            'coupon'   => $coupon,
            'discount' => $discount,
        ];
    }

    /**
     * Record coupon usage after order is placed.
     */
    public function recordUsage(Coupon $coupon, int $orderId, float $discountApplied): void
    {
        $coupon->usages()->create([
            'user_id'          => Auth::id(),
            'order_id'         => $orderId,
            'discount_applied' => $discountApplied,
        ]);

        $coupon->increment('used_count');
    }
}
