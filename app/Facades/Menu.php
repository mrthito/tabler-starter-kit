<?php

namespace App\Facades;

use App\Helpers\Menu as HelpersMenu;
use Illuminate\Support\Facades\Facade;

/**
 * Menu Facade - Universal Menu Builder
 *
 * @method static \App\Helpers\Menu name(string $groupName)
 * @method static \App\Helpers\Menu create(string $key, string $title, string $url = '#', string $icon = '')
 * @method static \App\Helpers\Menu add(string $title, string $url = '#', string $icon = '', int $position = null)
 * @method static \App\Helpers\Menu position(int $position)
 * @method static \App\Helpers\Menu permissions(string|array $permissions)
 * @method static \App\Helpers\Menu badge(string $text, string $class = 'badge-primary')
 * @method static \App\Helpers\Menu icon(string $icon)
 * @method static \App\Helpers\Menu url(string $url)
 * @method static \App\Helpers\MenuItem|null get(string $key)
 * @method static array all()
 * @method static array allAsArray()
 * @method static array getSortedMenus(string $groupName = null)
 * @method static array getMenusByName(string $name, string $groupName = null)
 * @method static array getMenusByNames(array $names, string $groupName = null)
 * @method static array getMenusByPosition(int $position)
 * @method static array getMenusByPositionRange(int $minPosition, int $maxPosition)
 * @method static bool canAccess(\App\Helpers\MenuItem $menu, $user = null)
 * @method static string render(string $key = null, $user = null, string $view = null, array $viewData = [])
 * @method static string renderGroup(string $groupName, string $view)
 * @method static string renderWithView(\App\Helpers\MenuItem|array $menu, $user = null, string $view, array $viewData = [])
 * @method static \Illuminate\Support\Collection collection($user = null, string $groupName = null)
 * @method static array toArray($user = null, string $groupName = null)
 * @method static array getMenuData(string $key = null, $user = null)
 * @method static string toJson($user = null, int $options = 0)
 * @method static \App\Helpers\Menu enableCache(bool $enable = true)
 * @method static \App\Helpers\Menu setCacheTtl(int $ttl)
 * @method static \App\Helpers\Menu clearCache(string $groupName = null)
 * @method static int count($user = null, string $groupName = null)
 * @method static bool has(string $key)
 * @method static bool hasPermission(\App\Helpers\MenuItem $menu, string $permission, $user = null)
 * @method static \App\Helpers\Menu bypassPermissions(bool $bypass = true)
 * @method static bool isBypassingPermissions()
 * @method static \App\Helpers\Menu togglePermissions()
 * @method static \App\Helpers\MenuItem|null getActiveMenu()
 * @method static array getActiveMenus()
 * @method static array debug()
 * @method static void saveToConfig(string $key, array $menuData)
 * @method static \App\Helpers\Menu clear()
 * @method static \App\Helpers\Menu remove(string $key)
 * @method static \App\Helpers\Menu update(string $key, string $title = null, string $url = null, string $icon = null)
 */
class Menu extends Facade
{
    protected static function getFacadeAccessor()
    {
        return HelpersMenu::class;
    }
}
