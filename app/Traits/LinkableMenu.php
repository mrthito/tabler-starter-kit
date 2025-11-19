<?php

namespace App\Traits;

trait LinkableMenu
{
    /**
     * Get the URL for this model when used in a menu
     */
    public function getMenuUrl(): string
    {
        // Try common methods
        if (method_exists($this, 'getUrl')) {
            return $this->getUrl();
        }

        if (method_exists($this, 'url')) {
            return $this->url();
        }

        // Try to guess route name based on model name
        $modelName = class_basename($this);
        $routeName = strtolower(\Illuminate\Support\Str::plural($modelName));

        if (\Illuminate\Support\Facades\Route::has("{$routeName}.show")) {
            return route("{$routeName}.show", $this->getKey());
        }

        // Fallback: return a default URL
        return '#';
    }

    /**
     * Get the title for this model when used in a menu
     */
    public function getMenuTitle(): string
    {
        // Try common attributes
        return $this->title
            ?? $this->name
            ?? $this->label
            ?? $this->heading
            ?? class_basename($this) . ' #' . $this->getKey();
    }

    /**
     * Get the description for this model when used in a menu
     */
    public function getMenuDescription(): ?string
    {
        return $this->description
            ?? $this->excerpt
            ?? $this->summary
            ?? null;
    }
}
