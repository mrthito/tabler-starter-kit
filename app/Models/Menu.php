<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'title',
        'location',
        'description',
        'status',
        'order',
        'settings',
    ];

    protected $casts = [
        'status' => 'boolean',
        'order' => 'integer',
        'settings' => 'array',
    ];

    /**
     * Get all items for this menu
     */
    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class)->whereNull('parent_id')->orderBy('order');
    }

    /**
     * Get all items including nested (for admin purposes)
     */
    public function allItems(): HasMany
    {
        return $this->hasMany(MenuItem::class)->orderBy('order');
    }

    /**
     * Get only active and visible items
     */
    public function activeItems(): HasMany
    {
        return $this->items()->where('status', true)->where('is_visible', true);
    }

    /**
     * Scope to only active menus
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope menus by location
     */
    public function scopeLocation($query, string $location)
    {
        return $query->where('location', $location);
    }

    /**
     * Scope menus by name
     */
    public function scopeByName($query, string $name)
    {
        return $query->where('name', $name);
    }

    /**
     * Get menu by name (static helper)
     */
    public static function getByName(string $name): ?self
    {
        return static::active()->where('name', $name)->first();
    }

    /**
     * Get menu by location (static helper)
     */
    public static function getByLocation(string $location): ?self
    {
        return static::active()->where('location', $location)->first();
    }

    /**
     * Create a new menu with a unique name
     */
    public static function createWithName(string $title, ?string $name = null, array $attributes = []): self
    {
        if (! $name) {
            $name = Str::slug($title);
        }

        // Ensure unique name
        $originalName = $name;
        $counter = 1;
        while (static::where('name', $name)->exists()) {
            $name = $originalName . '-' . $counter;
            $counter++;
        }

        return static::create(array_merge([
            'name' => $name,
            'title' => $title,
        ], $attributes));
    }

    /**
     * Get the rendered menu structure
     */
    public function getRenderedStructure($user = null)
    {
        return $this->buildMenuTree($this->getAccessibleItems($user));
    }

    /**
     * Get accessible items for a user
     */
    public function getAccessibleItems($user = null)
    {
        $items = $this->activeItems()->with('children')->get();

        if (! $user) {
            return $items->filter(function ($item) {
                return ! $item->requiresAuth();
            });
        }

        return $items->filter(function ($item) use ($user) {
            return $item->isAccessible($user);
        });
    }

    /**
     * Build menu tree structure
     */
    protected function buildMenuTree($items, $parentId = null)
    {
        return $items->filter(function ($item) use ($parentId) {
            return $item->parent_id === $parentId;
        })->map(function ($item) use ($items) {
            $children = $this->buildMenuTree($items, $item->id);
            $item->children = $children;
            return $item;
        })->values();
    }

    /**
     * Add an item to this menu
     */
    public function addItem(array $attributes): MenuItem
    {
        return $this->allItems()->create($attributes);
    }

    /**
     * Get setting value
     */
    public function getSetting(string $key, $default = null)
    {
        return data_get($this->settings, $key, $default);
    }

    /**
     * Set setting value
     */
    public function setSetting(string $key, $value): self
    {
        $settings = $this->settings ?? [];
        data_set($settings, $key, $value);
        $this->settings = $settings;
        return $this;
    }
}
