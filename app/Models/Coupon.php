<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'usage_limit',
        'used_count',
        'usage_limit_per_user',
        'minimum_amount',
        'maximum_discount',
        'valid_from',
        'valid_until',
        'applicable_to',
        'plan_ids',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'usage_limit_per_user' => 'integer',
        'minimum_amount' => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'plan_ids' => 'array',
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Check if coupon is valid
     */
    public function isValid(): bool
    {
        if (!$this->status) {
            return false;
        }

        // Check date validity
        $now = now();
        if ($this->valid_from && $now->lt($this->valid_from)) {
            return false;
        }
        if ($this->valid_until && $now->gt($this->valid_until)) {
            return false;
        }

        // Check usage limit
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    /**
     * Check if coupon is applicable to a plan
     */
    public function isApplicableToPlan($planId): bool
    {
        if ($this->applicable_to === 'all') {
            return true;
        }

        if ($this->applicable_to === 'plans') {
            return true; // All plans
        }

        if ($this->applicable_to === 'specific_plans') {
            return in_array($planId, $this->plan_ids ?? []);
        }

        return false;
    }

    /**
     * Calculate discount amount
     */
    public function calculateDiscount($amount): float
    {
        if ($this->type === 'percentage') {
            $discount = ($amount * $this->value) / 100;
            if ($this->maximum_discount) {
                $discount = min($discount, $this->maximum_discount);
            }
            return round($discount, 2);
        }

        // Fixed amount
        return min($this->value, $amount);
    }

    /**
     * Increment usage count
     */
    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }
}
