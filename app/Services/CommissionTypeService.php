<?php

namespace App\Services;

use App\Models\CommissionType;
use Illuminate\Support\Str;

class CommissionTypeService
{
    /**
     * Create a new CommissionTypeService instance.
     */
    public function __construct(protected CommissionType $commissionType)
    {
        // ...
    }

    /**
     * Get the single commission type record (singleton)
     */
    public function get()
    {
        return $this->commissionType->firstOrCreate(
            [
                'type' => 'both',
                'order_commission_percentage' => 0,
            ]
        );
    }

    /**
     * Update the single commission type record
     */
    public function update(array $data)
    {
        $commissionType = $this->get();
        return $commissionType->update($data);
    }
}
