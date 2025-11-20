<?php

namespace App\Models;

use App\Models\MenuItem;
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
     * Loads all MenuItem records from database with their children
     */
    public function getAccessibleItems($user = null)
    {
        // Load ALL menu items from database (MenuItem model)
        // This gets all items regardless of parent_id, so we can build the tree
        $allItems = MenuItem::where('menu_id', $this->id)
            ->where('status', true)
            ->where('is_visible', true)
            ->orderBy('order')
            ->get();

        // Filter by permissions if user is provided
        if (! $user) {
            $filteredItems = $allItems->filter(function ($item) {
                return ! $item->requiresAuth();
            });
        } else {
            $filteredItems = $allItems->filter(function ($item) use ($user) {
                return $item->isAccessible($user);
            });
        }

        return $filteredItems;
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
     * Get menu items as array with nested children
     */
    public function toArrayWithChildren($user = null): array
    {
        $items = $this->getAccessibleItems($user);
        return $this->buildMenuArray($items, null);
    }

    /**
     * Build menu array structure recursively from MenuItem collection
     * This builds the tree structure from all items loaded from database
     */
    protected function buildMenuArray($items, $parentId = null): array
    {
        $result = [];

        // Filter items by parent_id and sort by order
        $filteredItems = $items->filter(function ($item) use ($parentId) {
            // Handle both null comparison for root items
            if ($parentId === null) {
                return $item->parent_id === null;
            }
            return $item->parent_id == $parentId;
        })->sortBy('order')->values();

        foreach ($filteredItems as $item) {
            // Build item array from MenuItem model
            $itemArray = [
                'id' => $item->id,
                'title' => $item->title,
                'url' => $item->final_url,
                'route' => $item->route,
                'icon' => $item->icon,
                'css_class' => $item->css_class,
                'target' => $item->target,
                'order' => $item->order,
                'is_active' => $item->isActive(),
                'is_visible' => $item->is_visible,
                'status' => $item->status,
                'linkable_type' => $item->linkable_type,
                'linkable_id' => $item->linkable_id,
                'permissions' => $item->permissions,
                'roles' => $item->roles,
                'attributes' => $item->attributes,
                'meta' => $item->meta,
            ];

            // Recursively get children from the same collection
            // This works because we loaded ALL items from database
            $children = $this->buildMenuArray($items, $item->id);
            if (!empty($children)) {
                $itemArray['children'] = $children;
            }

            $result[] = $itemArray;
        }

        return $result;
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
