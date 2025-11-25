<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $table = 'plans';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'monthly_price',
        'yearly_price',
        'yearly_discount_percentage',
        'currency',
        'has_trial',
        'trial_days',
        'detail',
        'features',
        'plan_type',
        'sort_order',
        'is_popular',
        'status',
    ];

    protected $casts = [
        'monthly_price' => 'decimal:2',
        'yearly_price' => 'decimal:2',
        'yearly_discount_percentage' => 'integer',
        'has_trial' => 'boolean',
        'is_popular' => 'boolean',
        'status' => 'boolean',
        'features' => 'array',
        'trial_days' => 'integer',
        'sort_order' => 'integer',
    ];
}
