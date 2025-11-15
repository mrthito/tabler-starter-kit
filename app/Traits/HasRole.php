<?php

namespace App\Traits;

use App\Models\Role;

trait HasRole
{
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function scopeHasRole($query, $role)
    {
        return $query->where('role_id', $role->id);
    }

    public function scopeHasPermission($query, string $permission)
    {
        return $query->whereHas('role', function ($query) use ($permission) {
            $query->whereJsonContains('permissions', $permission);
        });
    }
}
