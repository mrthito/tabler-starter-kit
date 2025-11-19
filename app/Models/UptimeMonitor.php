<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UptimeMonitor extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'service_name',
        'service_url',
        'status',
        'response_time',
        'error_message',
        'checked_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'checked_at' => 'timestamp',
        ];
    }
}
