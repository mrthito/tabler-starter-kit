<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'guard_name',
        'permissions',
        'status',
    ];

    protected $casts = [
        'permissions' => 'array',
    ];

    public function admins()
    {
        return $this->belongsToMany(Admin::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
