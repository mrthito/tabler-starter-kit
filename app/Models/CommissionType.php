<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionType extends Model
{
    protected $fillable = [
        'type',
        'order_commission_percentage',
    ];

    protected $casts = [
        'order_commission_percentage' => 'decimal:2',
    ];
}
