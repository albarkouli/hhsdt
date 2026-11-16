<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\CouponUsage;
use Illuminate\Support\Facades\DB;

class CouponService
{
    /**
     * Validate a code against a subtotal and user.
     * Returns [Coupon|null, errorMessage|null, discountAmount].
     *
     * @return array{0: ?Coupon, 1: ?string, 2: float}
     */
    public function validate(string $code, float $subtotal, int $userId): array
    {
        $coupon = Coupon::whereRaw('UPPER(code) = ?', [strtoupper(trim($code))])->first();

        if (! $coupon) {
            return [null, 'Coupon code not found.', 0.0];
        }

        if (! $coupon->is_active) {
            return [null, 'This coupon is no longer active.', 0.0];
        }

        if ($coupon->isExpired()) {
            return [null, 'This coupon has expired.', 0.0];
        }

        if (! $coupon->hasStarted()) {
            return [null, 'This coupon is not active yet.', 0.0];
        }

        if ($coupon->isExhausted()) {
            return [null, 'This coupon has reached its usage limit.', 0.0];
        }

        if ($subtotal < (float) $coupon->min_order) {
            return [null, 'Minimum order of ' . number_format((float) $coupon->min_order, 2) . ' LYD required.', 0.0];
        }

        $userUsages = CouponUsage::where('coupon_id', $coupon->id)
            ->where('user_id', $userId)
            ->count();

        if ($userUsages >= $coupon->max_uses_per_user) {
            return [null, 'You have already used this coupon.', 0.0];
        }

        $discount = $coupon->discountFor($subtotal);

        return [$coupon, null, $discount];
    }

    public function recordUsage(Coupon $coupon, int $userId, ?int $orderId, float $discount): void
    {
        DB::transaction(function () use ($coupon, $userId, $orderId, $discount) {
            CouponUsage::create([
                'coupon_id'       => $coupon->id,
                'user_id'         => $userId,
                'order_id'        => $orderId,
                'discount_amount' => $discount,
            ]);

            Coupon::where('id', $coupon->id)->increment('used_count');
        });
    }
}