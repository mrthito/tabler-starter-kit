<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Transaction extends Model
{
    protected $fillable = [
        'transaction_category_id',
        'reference_number',
        'title',
        'description',
        'amount',
        'currency',
        'type',
        'payment_method',
        'transaction_date',
        'due_date',
        'transactionable_type',
        'transactionable_id',
        'status',
        'tax_amount',
        'tax_rate',
        'tax_type',
        'metadata',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'transaction_date' => 'date',
        'due_date' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Get the category that owns the transaction
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(TransactionCategory::class, 'transaction_category_id');
    }

    /**
     * Get the parent transactionable model (polymorphic relation)
     */
    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the admin who created the transaction
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    /**
     * Get the admin who updated the transaction
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    /**
     * Generate a unique reference number
     */
    public static function generateReferenceNumber(): string
    {
        do {
            $reference = 'TXN-' . strtoupper(uniqid());
        } while (self::where('reference_number', $reference)->exists());

        return $reference;
    }
}
