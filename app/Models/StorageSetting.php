<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StorageSetting extends Model
{
    protected $fillable = [
        'provider',
        'config',
    ];

    protected $casts = [
        'config' => 'array',
    ];

    /**
     * Get config value by key
     */
    public function getConfig($key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }

    /**
     * Set config value
     */
    public function setConfig($key, $value)
    {
        $config = $this->config ?? [];
        $config[$key] = $value;
        $this->config = $config;
        return $this;
    }
}
