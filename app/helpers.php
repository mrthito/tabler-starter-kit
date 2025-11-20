<?php

use App\Models\Menu;
use Illuminate\Support\Facades\Auth;

if (! function_exists('menu')) {
    /**
     * Get menu items as array with nested children from database
     * 
     * This function loads menus and menu items directly from the Menu and MenuItem models
     * Returns a nested array structure with all children recursively loaded
     *
     * @param string|null $name Menu name or location
     * @param mixed $user User instance for permission checking (optional)
     * @return array Array of menu items with nested children
     * 
     * @example
     * $menu = menu('main-nav');
     * // Returns: [
     * //     ['id' => 1, 'title' => 'Home', 'url' => '/', 'children' => [...]],
     * //     ['id' => 2, 'title' => 'About', 'url' => '/about', 'children' => []]
     * // ]
     */
    function menu(?string $name = null, $user = null): array
    {
        if (! $name) {
            return [];
        }

        // Load Menu from database (Menu model)
        $menu = Menu::getByName($name);

        // If not found by name, try location
        if (! $menu) {
            $menu = Menu::getByLocation($name);
        }

        if (! $menu) {
            return [];
        }

        // If user is not provided, try to get authenticated user
        if (! $user) {
            try {
                $user = Auth::user() ?? Auth::guard('admin')->user();
            } catch (\Exception $e) {
                // If no user is authenticated, continue with null
                $user = null;
            }
        }

        // Load MenuItems with children from database (MenuItem model)
        // This returns an array with all menu items and their nested children
        return $menu->toArrayWithChildren($user);
    }
}
