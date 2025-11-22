<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Media extends Model
{
    protected $fillable = [
        'parent_id',
        'type', //file or folder
        'name', //name of the file or folder
        'path', //path of the file or folder
        'size', //size of the file or folder
        'url', //url of the file or folder
        'thumbnail', //thumbnail of the file or folder
        'alt', //alt text of the file or folder
    ];

    public function getSizeAttribute($value)
    {
        return $value / 1024 / 1024;
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Media::class, 'parent_id');
    }

    public function isFolder(): bool
    {
        return $this->type === 'folder';
    }
}
