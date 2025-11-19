<?php

namespace App\Helpers;

use App\Facades\Menu;

class MenuHelper
{
    /**
     * Quick menu creation helper
     */
    public static function menu($key, $title, $url = '#', $icon = '')
    {
        return Menu::create($key, $title, $url, $icon);
    }

    /**
     * Quick submenu addition
     */
    public static function submenu($title, $url, $icon = '', $position = null)
    {
        return Menu::add($title, $url, $icon, $position);
    }

    /**
     * Render all menus
     */
    public static function render()
    {
        return Menu::render();
    }

    /**
     * Get menu by key
     */
    public static function get($key)
    {
        return Menu::get($key);
    }

    /**
     * Check if menu exists
     */
    public static function has($key)
    {
        return Menu::has($key);
    }

    /**
     * Get all menus as array
     */
    public static function all()
    {
        return Menu::allAsArray();
    }

    /**
     * Get active menu
     */
    public static function active()
    {
        return Menu::getActiveMenu();
    }

    /**
     * Check if current page matches menu
     */
    public static function isActive($key)
    {
        $menu = Menu::get($key);

        return $menu ? Menu::canAccess($menu) : false;
    }
}
