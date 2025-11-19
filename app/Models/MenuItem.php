<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class MenuItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'menu_id',
        'parent_id',
        'title',
        'url',
        'route',
        'icon',
        'css_class',
        'target',
        'order',
        'status',
        'is_visible',
        'linkable_type',
        'linkable_id',
        'permissions',
        'roles',
        'attributes',
        'meta',
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_visible' => 'boolean',
        'order' => 'integer',
        'permissions' => 'array',
        'roles' => 'array',
        'attributes' => 'array',
        'meta' => 'array',
    ];

    /**
     * Get the menu this item belongs to
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Get the parent item
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    /**
     * Get child items
     */
    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }

    /**
     * Get only active and visible children
     */
    public function activeChildren(): HasMany
    {
        return $this->children()->where('status', true)->where('is_visible', true);
    }

    /**
     * Get all descendants (children, grandchildren, etc.)
     */
    public function descendants(): HasMany
    {
        return $this->children()->with('descendants');
    }

    /**
     * Get the polymorphic linkable model
     */
    public function linkable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope to only active items
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope to only visible items
     */
    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    /**
     * Scope to root items (no parent)
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope items by menu
     */
    public function scopeForMenu($query, $menuId)
    {
        return $query->where('menu_id', $menuId);
    }

    /**
     * Get the final URL for this menu item
     */
    public function getFinalUrlAttribute(): string
    {
        // Priority: linkable model > route > url
        if ($this->linkable) {
            return $this->getLinkableUrl();
        }

        if ($this->route && Route::has($this->route)) {
            return route($this->route);
        }

        return $this->url ?? '#';
    }

    /**
     * Get URL from linkable model
     */
    protected function getLinkableUrl(): string
    {
        $linkable = $this->linkable;

        if (! $linkable) {
            return '#';
        }

        // Use LinkableMenu trait method if available
        if (method_exists($linkable, 'getMenuUrl')) {
            return $linkable->getMenuUrl();
        }

        // Try common methods on linkable models
        if (method_exists($linkable, 'getUrl')) {
            return $linkable->getUrl();
        }

        if (method_exists($linkable, 'url')) {
            return $linkable->url();
        }

        if (method_exists($linkable, 'getRouteKeyName')) {
            $key = $linkable->getRouteKeyName();
            $id = $linkable->{$key};

            // Try to guess route name based on model name
            $modelName = class_basename($linkable);
            $routeName = strtolower(Str::plural($modelName));

            if (Route::has("{$routeName}.show")) {
                return route("{$routeName}.show", $id);
            }
        }

        // Fallback: return a default URL
        return '#';
    }

    /**
     * Check if item requires authentication
     */
    public function requiresAuth(): bool
    {
        return ! empty($this->permissions) || ! empty($this->roles);
    }

    /**
     * Check if user can access this item
     */
    public function isAccessible($user = null): bool
    {
        if (! $this->requiresAuth()) {
            return true;
        }

        if (! $user) {
            return false;
        }

        // Check permissions
        if (! empty($this->permissions)) {
            $hasPermission = false;
            foreach ($this->permissions as $permission) {
                if (method_exists($user, 'hasPermission') && $user->hasPermission($permission)) {
                    $hasPermission = true;
                    break;
                } elseif (method_exists($user, 'can') && $user->can($permission)) {
                    $hasPermission = true;
                    break;
                }
            }
            if (! $hasPermission) {
                return false;
            }
        }

        // Check roles
        if (! empty($this->roles)) {
            $hasRole = false;
            foreach ($this->roles as $role) {
                if (method_exists($user, 'hasRole') && $user->hasRole($role)) {
                    $hasRole = true;
                    break;
                } elseif (method_exists($user, 'hasRole')) {
                    $hasRole = true;
                    break;
                }
            }
            if (! $hasRole) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if this item is currently active
     */
    public function isActive(): bool
    {
        $currentUrl = \Illuminate\Support\Facades\URL::current();
        $finalUrl = $this->final_url;

        // Exact match
        if ($currentUrl === $finalUrl) {
            return true;
        }

        // Check if current URL starts with menu URL (for parent items)
        if ($finalUrl !== '#' && strpos($currentUrl, $finalUrl) === 0) {
            return true;
        }

        // Check route
        if ($this->route && request()->routeIs($this->route . '*')) {
            return true;
        }

        // Check if any child is active
        foreach ($this->activeChildren as $child) {
            if ($child->isActive()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if this item has active children
     */
    public function hasActiveChildren(): bool
    {
        foreach ($this->activeChildren as $child) {
            if ($child->isActive() || $child->hasActiveChildren()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get all ancestors (parent, grandparent, etc.)
     */
    public function ancestors()
    {
        $ancestors = collect();
        $parent = $this->parent;

        while ($parent) {
            $ancestors->prepend($parent);
            $parent = $parent->parent;
        }

        return $ancestors;
    }

    /**
     * Get the depth level of this item
     */
    public function getDepth(): int
    {
        return $this->ancestors()->count();
    }

    /**
     * Get HTML attributes as string
     */
    public function getHtmlAttributes(): string
    {
        $attrs = $this->attributes ?? [];
        $html = '';

        foreach ($attrs as $key => $value) {
            $html .= ' ' . htmlspecialchars($key) . '="' . htmlspecialchars($value) . '"';
        }

        if ($this->css_class) {
            $html .= ' class="' . htmlspecialchars($this->css_class) . '"';
        }

        if ($this->target) {
            $html .= ' target="' . htmlspecialchars($this->target) . '"';
        }

        return trim($html);
    }

    /**
     * Get meta value
     */
    public function getMeta(string $key, $default = null)
    {
        return data_get($this->meta, $key, $default);
    }

    /**
     * Set meta value
     */
    public function setMeta(string $key, $value): self
    {
        $meta = $this->meta ?? [];
        data_set($meta, $key, $value);
        $this->meta = $meta;
        return $this;
    }

    /**
     * Link this item to a model
     */
    public function linkTo(Model $model, ?string $title = null): self
    {
        $this->linkable_type = get_class($model);
        $this->linkable_id = $model->id;

        if ($title) {
            $this->title = $title;
        } elseif (empty($this->title)) {
            // Use LinkableMenu trait method if available
            if (method_exists($model, 'getMenuTitle')) {
                $this->title = $model->getMenuTitle();
            } else {
                // Try to get title from common attributes
                $this->title = $model->title ?? $model->name ?? $model->label ?? class_basename($model);
            }
        }

        // Auto-populate URL if not set
        if (empty($this->url) && empty($this->route)) {
            if (method_exists($model, 'getMenuUrl')) {
                $url = $model->getMenuUrl();
                if ($url !== '#') {
                    // Try to determine if it's a route or URL
                    if (filter_var($url, FILTER_VALIDATE_URL) || str_starts_with($url, '/')) {
                        $this->url = $url;
                    }
                }
            }
        }

        $this->save();

        return $this;
    }

    /**
     * Add a child item
     */
    public function addChild(array $attributes): MenuItem
    {
        $attributes['parent_id'] = $this->id;
        $attributes['menu_id'] = $this->menu_id;

        return static::create($attributes);
    }

    /**
     * Move item to new parent
     */
    public function moveTo(?MenuItem $parent = null): self
    {
        $this->parent_id = $parent ? $parent->id : null;
        $this->save();

        return $this;
    }

    /**
     * Reorder siblings
     */
    public function reorder(int $newOrder): self
    {
        $siblings = static::where('parent_id', $this->parent_id)
            ->where('menu_id', $this->menu_id)
            ->where('id', '!=', $this->id)
            ->orderBy('order')
            ->get();

        $oldOrder = $this->order;

        if ($newOrder < $oldOrder) {
            // Moving up
            $siblings->where('order', '>=', $newOrder)
                ->where('order', '<', $oldOrder)
                ->each(function ($item) {
                    $item->increment('order');
                });
        } else {
            // Moving down
            $siblings->where('order', '>', $oldOrder)
                ->where('order', '<=', $newOrder)
                ->each(function ($item) {
                    $item->decrement('order');
                });
        }

        $this->order = $newOrder;
        $this->save();

        return $this;
    }
}
