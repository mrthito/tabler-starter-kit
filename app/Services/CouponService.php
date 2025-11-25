<?php

namespace App\Services;

use App\Models\Coupon;
use Illuminate\Support\Str;

class CouponService
{
    /**
     * Create a new CouponService instance.
     */
    public function __construct(protected Coupon $coupon)
    {
        // ...
    }

    public function paginate($perPage = 20)
    {
        return $this->coupon->orderBy('sort_order')->latest()->paginate($perPage);
    }

    public function create(array $data)
    {
        // Generate code if not provided
        if (empty($data['code'])) {
            $data['code'] = $this->generateUniqueCode();
        } else {
            $data['code'] = strtoupper(Str::slug($data['code'], ''));
        }

        // Handle plan_ids - ensure it's an array
        if (isset($data['plan_ids']) && is_array($data['plan_ids'])) {
            $data['plan_ids'] = array_values(array_filter($data['plan_ids']));
        }

        return $this->coupon->create($data);
    }

    public function update(Coupon $coupon, array $data)
    {
        // Ensure code is uppercase
        if (isset($data['code'])) {
            $data['code'] = strtoupper(Str::slug($data['code'], ''));
        }

        // Handle plan_ids - ensure it's an array
        if (isset($data['plan_ids']) && is_array($data['plan_ids'])) {
            $data['plan_ids'] = array_values(array_filter($data['plan_ids']));
        }

        return $coupon->update($data);
    }

    public function delete($coupon, $rows = [])
    {
        if ($coupon == 'bulk') {
            if (!is_array($rows)) {
                $rows = explode(',', $rows);
            }
            $this->coupon->whereIn('id', $rows)->delete();
            return;
        }
        if ($coupon instanceof Coupon) {
            return $coupon->delete();
        }
        return $this->coupon->where('id', $coupon)->delete();
    }

    /**
     * Generate a unique coupon code
     */
    protected function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while ($this->coupon->where('code', $code)->exists());

        return $code;
    }

    /**
     * Validate and apply coupon
     */
    public function validateCoupon(string $code, $amount = null, $planId = null, $userId = null): ?Coupon
    {
        $coupon = $this->coupon->where('code', strtoupper($code))->first();

        if (!$coupon) {
            return null;
        }

        if (!$coupon->isValid()) {
            return null;
        }

        // Check minimum amount
        if ($amount && $coupon->minimum_amount && $amount < $coupon->minimum_amount) {
            return null;
        }

        // Check plan applicability
        if ($planId && !$coupon->isApplicableToPlan($planId)) {
            return null;
        }

        // TODO: Check per-user usage limit if $userId is provided
        // This would require a coupon_usages table to track per-user usage

        return $coupon;
    }
}
