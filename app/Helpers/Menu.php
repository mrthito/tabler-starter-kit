<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

class Menu
{
    protected $menus = [];

    protected $currentMenu = null;

    protected $currentMenuGroup = null;

    protected $menuConfig = [];

    protected $bypassPermissions = false;

    protected $cacheEnabled = true;

    protected $cachePrefix = 'menu_';

    protected $cacheTtl = 3600; // 1 hour

    public function __construct()
    {
        $this->menuConfig = Config::get('admin.menus', []);
        $this->cacheEnabled = Config::get('menu.cache_enabled', true);
        $this->cacheTtl = Config::get('menu.cache_ttl', 1);
        $this->loadMenus();
    }

    /**
     * Set the current menu group name
     */
    public function name($groupName)
    {
        $this->currentMenuGroup = $groupName;

        // Initialize menu group if it doesn't exist
        if (! isset($this->menus[$groupName])) {
            $this->menus[$groupName] = [];
        }

        return $this;
    }

    /**
     * Load menus from configuration
     */
    protected function loadMenus()
    {
        foreach ($this->menuConfig as $menuKey => $menuData) {
            $this->menus[$menuKey] = $this->buildMenuFromConfig($menuData);
        }
    }

    /**
     * Build menu structure from configuration
     */
    protected function buildMenuFromConfig($menuData)
    {
        $menu = new MenuItem($menuData['title'] ?? '', $menuData['url'] ?? '#', $menuData['icon'] ?? '');

        if (isset($menuData['position'])) {
            $menu->setPosition($menuData['position']);
        }

        if (isset($menuData['permissions'])) {
            $menu->setPermissions($menuData['permissions']);
        }

        if (isset($menuData['children']) && is_array($menuData['children'])) {
            foreach ($menuData['children'] as $childData) {
                $child = $this->buildMenuFromConfig($childData);
                $menu->addChild($child);
            }
        }

        return $menu;
    }

    /**
     * Create a new menu
     */
    public function create($key, $title, $url = '#', $icon = '', $name = null)
    {
        $menu = new MenuItem($title, $url, $icon);

        // Set the menu name if provided
        if ($name !== null) {
            $menu->setName($name);
        }

        // If we have a current menu group, add to that group
        if ($this->currentMenuGroup !== null) {
            $this->menus[$this->currentMenuGroup][$key] = $menu;
            // Reset the current menu group after adding to it
            $this->currentMenuGroup = null;
        } else {
            // Default behavior - add to root menus
            $this->menus[$key] = $menu;
        }

        $this->currentMenu = $menu;

        return $this;
    }

    /**
     * Add a menu directly to the menus array (for dynamic creation)
     */
    public function addMenu($key, $menu)
    {
        $this->menus[$key] = $menu;

        return $this;
    }

    /**
     * Add a menu item to the current menu
     */
    public function add($title, $url = '#', $icon = '', $position = null)
    {
        if ($this->currentMenu === null) {
            throw new \Exception('No current menu selected. Use create() first.');
        }

        $menuItem = new MenuItem($title, $url, $icon);
        if ($position !== null) {
            $menuItem->setPosition($position);
        }

        $this->currentMenu->addChild($menuItem);

        return $this;
    }

    /**
     * Set position for the current menu
     */
    public function position($position)
    {
        if ($this->currentMenu === null) {
            throw new \Exception('No current menu selected. Use create() first.');
        }

        $this->currentMenu->setPosition($position);

        return $this;
    }

    /**
     * Set permissions for the current menu
     */
    public function permissions($permissions)
    {
        if ($this->currentMenu === null) {
            throw new \Exception('No current menu selected. Use create() first.');
        }

        $this->currentMenu->setPermissions($permissions);

        return $this;
    }

    /**
     * Set icon for the current menu
     */
    public function icon($icon)
    {
        if ($this->currentMenu === null) {
            throw new \Exception('No current menu selected. Use create() first.');
        }

        $this->currentMenu->setIcon($icon);

        return $this;
    }

    /**
     * Set URL for the current menu
     */
    public function url($url)
    {
        if ($this->currentMenu === null) {
            throw new \Exception('No current menu selected. Use create() first.');
        }

        $this->currentMenu->setUrl($url);

        return $this;
    }

    /**
     * Get a specific menu or menu group
     */
    public function get($key, $groupName = null)
    {
        if ($groupName !== null) {
            return $this->menus[$groupName][$key] ?? null;
        }

        // If key exists as a group, return the group
        if (isset($this->menus[$key]) && is_array($this->menus[$key])) {
            return $this->menus[$key];
        }

        // Otherwise, search in all groups
        foreach ($this->menus as $group => $menus) {
            if (is_array($menus) && isset($menus[$key])) {
                return $menus[$key];
            }
        }

        return null;
    }

    /**
     * Get all menus
     */
    public function all()
    {
        return $this->menus;
    }

    /**
     * Get all menus as array
     */
    public function allAsArray()
    {
        $result = [];
        foreach ($this->menus as $key => $menu) {
            $result[$key] = $this->menuToArray($menu);
        }

        return $result;
    }

    /**
     * Get menus sorted by position
     */
    public function getSortedMenus($groupName = null)
    {
        // Try to get cached menus first
        $cachedMenus = $this->getCachedMenus($groupName);
        if ($cachedMenus !== null) {
            return $cachedMenus;
        }

        if ($groupName !== null) {
            $menus = $this->menus[$groupName] ?? [];
        } else {
            // Flatten all menus from all groups
            $menus = [];
            foreach ($this->menus as $group => $groupMenus) {
                if (is_array($groupMenus)) {
                    foreach ($groupMenus as $key => $menu) {
                        $menus[$key] = $menu;
                    }
                } else {
                    // Handle legacy menus that aren't in groups
                    $menus[$group] = $groupMenus;
                }
            }
        }

        uasort($menus, function ($a, $b) {
            return $a->getPosition() <=> $b->getPosition();
        });

        // Cache the sorted menus
        $this->cacheMenus($groupName, $menus);

        return $menus;
    }

    /**
     * Filter menus by name
     */
    public function getMenusByName($name, $groupName = null)
    {
        $menus = $this->getSortedMenus($groupName);
        $filteredMenus = [];

        foreach ($menus as $key => $menu) {
            if ($menu->getName() === $name) {
                $filteredMenus[$key] = $menu;
            }
        }

        return $filteredMenus;
    }

    /**
     * Filter menus by multiple names
     */
    public function getMenusByNames($names, $groupName = null)
    {
        $menus = $this->getSortedMenus($groupName);
        $filteredMenus = [];

        foreach ($menus as $key => $menu) {
            if (in_array($menu->getName(), $names)) {
                $filteredMenus[$key] = $menu;
            }
        }

        return $filteredMenus;
    }

    /**
     * Get menus for a specific position
     */
    public function getMenusByPosition($position)
    {
        return array_filter($this->menus, function ($menu) use ($position) {
            return $menu->getPosition() === $position;
        });
    }

    /**
     * Check if user has permission to access menu
     */
    public function canAccess($menu, $user = null)
    {
        // If permissions are bypassed, allow access to all menus
        if ($this->bypassPermissions) {
            return true;
        }

        if ($user === null) {
            $user = Auth::user();
        }

        // If no user is provided and no user is authenticated, allow access if no permissions are required
        if (! $user) {
            return ! $menu->hasPermissions();
        }

        if (! $menu->hasPermissions()) {
            return true;
        }

        $permissions = $menu->getPermissions();
        if (is_string($permissions)) {
            return $user->hasPermission($permissions);
        }

        if (is_array($permissions)) {
            foreach ($permissions as $permission) {
                if ($user->hasPermission($permission)) {
                    return true;
                }
            }

            return false;
        }

        return true;
    }

    /**
     * Render menu group HTML
     */
    public function renderGroup($groupName, $view)
    {
        // Try to get cached HTML first
        $cacheKey = $this->getCacheKey($groupName).'_html_'.md5($view);
        if ($this->cacheEnabled && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $menus = $this->getSortedMenus($groupName);
        if (! $menus || ! is_array($menus)) {
            return '';
        }

        $user = Auth::user();

        // Always use view-based rendering
        $html = $this->renderWithView($menus, $view, []);

        // Cache the HTML output
        if ($this->cacheEnabled) {
            Cache::put($cacheKey, $html, $this->cacheTtl);
        }

        return $html;
    }

    /**
     * Render menu HTML
     */
    public function render($key = null, $view = null, $viewData = [])
    {
        $user = Auth::user();

        if ($key !== null) {
            $menu = $this->get($key);
            if (! $menu || ! $this->canAccess($menu, $user)) {
                return '';
            }

            // If custom view is provided, use view-based rendering
            if ($view !== null) {
                return $this->renderWithView($menu, $view, $viewData);
            }

            return $this->renderBasicHtml($menu, $user);
        }

        // If custom view is provided for all menus, use view-based rendering
        if ($view !== null) {
            $menus = $this->getSortedMenus();
            $accessibleMenus = [];
            foreach ($menus as $menu) {
                if ($this->canAccess($menu, $user)) {
                    $accessibleMenus[] = $menu;
                }
            }

            return $this->renderWithView($accessibleMenus, $view, $viewData);
        }

        $html = '';
        foreach ($this->getSortedMenus() as $menu) {
            if ($this->canAccess($menu, $user)) {
                $html .= $this->renderBasicHtml($menu, $user);
            }
        }

        return $html;
    }

    /**
     * Render menu with custom view
     */
    public function renderWithView($menu, $view, $viewData = [])
    {
        $user = Auth::user();

        // Prepare data for the view
        $data = array_merge([
            'menu' => $menu,
            'user' => $user,
            'isActive' => $this->isActive($menu),
            'hasActiveChildren' => $this->hasActiveChildren($menu),
            'canAccess' => $this->canAccess($menu, $user),
        ], $viewData);

        // Handle both single menu and collection of menus
        if (is_array($menu)) {
            $data['menus'] = $menu;
            $data['menu'] = null; // Clear single menu since we have multiple
        }

        try {
            return view($view, $data)->render();
        } catch (\Exception $e) {
            // Fallback to basic HTML if view fails
            return $this->renderBasicHtml($menu, $user);
        }
    }

    /**
     * Get menus as a collection
     */
    public function collection($user = null, $groupName = null)
    {
        $menus = collect($this->getSortedMenus($groupName))->filter(function ($menu) use ($user) {
            return $this->canAccess($menu, $user);
        });

        return $menus;
    }

    /**
     * Get menus as an array
     */
    public function toArray($user = null, $groupName = null)
    {
        return $this->collection($user, $groupName)->map(function ($menu) {
            return $this->menuToArray($menu);
        })->toArray();
    }

    /**
     * Get menu count
     */
    public function count($user = null, $groupName = null)
    {
        return $this->collection($user, $groupName)->count();
    }

    /**
     * Check if menu exists
     */
    public function has($key)
    {
        return isset($this->menus[$key]);
    }

    /**
     * Bypass all permissions (useful for testing/development)
     */
    public function bypassPermissions($bypass = true)
    {
        $this->bypassPermissions = $bypass;

        return $this;
    }

    /**
     * Check if permissions are being bypassed
     */
    public function isBypassingPermissions()
    {
        return $this->bypassPermissions;
    }

    /**
     * Toggle permission bypass
     */
    public function togglePermissions()
    {
        $this->bypassPermissions = ! $this->bypassPermissions;

        return $this;
    }

    /**
     * Debug menu loading
     */
    public function debug()
    {
        return [
            'config_menus' => $this->menuConfig,
            'loaded_menus' => array_keys($this->menus),
            'menu_objects' => $this->menus,
            'users_menu' => $this->get('users'),
            'users_menu_type' => get_class($this->get('users')),
        ];
    }

    /**
     * Convert menu item to array
     */
    protected function menuToArray($menu)
    {
        $data = [
            'name' => $menu->getName(),
            'title' => $menu->getTitle(),
            'url' => $menu->getUrl(),
            'icon' => $menu->getIcon(),
            'position' => $menu->getPosition(),
            'permissions' => $menu->getPermissions(),
            'is_active' => $this->isActive($menu),
            'has_children' => $menu->hasChildren(),
            'has_active_children' => $this->hasActiveChildren($menu),
        ];

        if ($menu->hasChildren()) {
            $data['children'] = collect($menu->getChildren())->map(function ($child) {
                return $this->menuToArray($child);
            })->toArray();
        }

        return $data;
    }

    /**
     * Basic HTML fallback rendering (framework-agnostic)
     */
    protected function renderBasicHtml($menu, $user = null)
    {
        if (is_array($menu)) {
            $html = '';
            foreach ($menu as $singleMenu) {
                $html .= $this->renderBasicHtml($singleMenu, $user);
            }

            return $html;
        }

        if (! $this->canAccess($menu, $user)) {
            return '';
        }

        $html = '<li>';
        $html .= '<a href="'.$menu->getUrl().'">';
        $html .= htmlspecialchars($menu->getTitle());
        $html .= '</a>';

        if ($menu->hasChildren()) {
            $html .= '<ul>';
            foreach ($menu->getChildren() as $child) {
                $html .= $this->renderBasicHtml($child, $user);
            }
            $html .= '</ul>';
        }

        $html .= '</li>';

        return $html;
    }

    /**
     * Get menu ID for dropdown
     */
    protected function getMenuId($menu)
    {
        return strtolower(str_replace([' ', '-'], '_', $menu->getTitle()));
    }

    /**
     * Check if menu is active
     */
    public function isActive($menu)
    {
        // Handle array of menus - check if any are active
        if (is_array($menu)) {
            foreach ($menu as $singleMenu) {
                if ($this->isActive($singleMenu)) {
                    return true;
                }
            }

            return false;
        }

        $currentUrl = request()->url();
        $currentPath = request()->path();
        $menuUrl = $menu->getUrl();

        // Skip if menu URL is just a hash
        if ($menuUrl === '#') {
            // For parent menus with hash URLs, check if any children are active
            foreach ($menu->getChildren() as $child) {
                if ($this->isActive($child)) {
                    return true;
                }
            }

            return false;
        }

        // Parse menu URL to get path
        $menuPath = parse_url($menuUrl, PHP_URL_PATH);
        if (! $menuPath) {
            $menuPath = $menuUrl;
        }

        // Remove leading slash for comparison
        $menuPath = ltrim($menuPath, '/');
        $currentPath = ltrim($currentPath, '/');

        // Check if current path exactly matches menu path
        if ($currentPath === $menuPath) {
            return true;
        }

        // Check if current path starts with menu path (for parent menus)
        if ($menuPath && strpos($currentPath, $menuPath) === 0) {
            return true;
        }

        // Check if current URL exactly matches menu URL
        if ($currentUrl === $menuUrl) {
            return true;
        }

        // Check if current URL starts with menu URL (for parent menus)
        if ($menuUrl !== '#' && strpos($currentUrl, $menuUrl) === 0) {
            return true;
        }

        // Check children recursively - if any child is active, parent should be active
        foreach ($menu->getChildren() as $child) {
            if ($this->isActive($child)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if menu has active children
     */
    public function hasActiveChildren($menu)
    {
        // Handle array of menus
        if (is_array($menu)) {
            foreach ($menu as $singleMenu) {
                if ($this->hasActiveChildren($singleMenu)) {
                    return true;
                }
            }

            return false;
        }

        foreach ($menu->getChildren() as $child) {
            if ($this->isActive($child)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get current active menu
     */
    public function getActiveMenu()
    {
        foreach ($this->menus as $menu) {
            if ($this->isActive($menu)) {
                return $menu;
            }
        }

        return null;
    }

    /**
     * Get all active menus (including parents of active children)
     */
    public function getActiveMenus()
    {
        $activeMenus = [];
        foreach ($this->menus as $key => $menu) {
            if ($this->isActive($menu)) {
                $activeMenus[$key] = $menu;
            }
        }

        return $activeMenus;
    }

    /**
     * Save menu configuration to config file
     */
    public function saveToConfig($key, $menuData)
    {
        $configPath = config_path('admin.php');

        if (! file_exists($configPath)) {
            $this->createAdminConfigFile();
        }

        $config = include $configPath;
        $config['menus'][$key] = $menuData;

        $content = "<?php\n\nreturn ".var_export($config, true).';';
        file_put_contents($configPath, $content);

        // Reload config
        Config::set('admin.menus.'.$key, $menuData);
        $this->loadMenus();
    }

    /**
     * Create admin config file if it doesn't exist
     */
    protected function createAdminConfigFile()
    {
        $config = [
            'menus' => [],
        ];

        $content = "<?php\n\nreturn ".var_export($config, true).';';
        file_put_contents(config_path('admin.php'), $content);
    }

    /**
     * Get menu data for JSON/API responses
     */
    public function getMenuData($key = null, $user = null)
    {
        if ($key !== null) {
            $menu = $this->get($key);
            if (! $menu || ! $this->canAccess($menu, $user)) {
                return null;
            }

            return $this->menuToArray($menu);
        }

        $menus = [];
        foreach ($this->getSortedMenus() as $menuKey => $menu) {
            if ($this->canAccess($menu, $user)) {
                $menus[$menuKey] = $this->menuToArray($menu);
            }
        }

        return $menus;
    }

    /**
     * Get menu structure as JSON
     */
    public function toJson($user = null, $options = 0)
    {
        return json_encode($this->getMenuData(null, $user), $options);
    }

    /**
     * Check if menu has specific permission
     */
    public function hasPermission($menu, $permission, $user = null)
    {
        if ($user === null) {
            $user = Auth::user();
        }

        if (! $menu->hasPermissions()) {
            return true;
        }

        $permissions = $menu->getPermissions();
        if (is_string($permissions)) {
            return $permissions === $permission;
        }

        if (is_array($permissions)) {
            return in_array($permission, $permissions);
        }

        return false;
    }

    /**
     * Get menu by position range
     */
    public function getMenusByPositionRange($minPosition, $maxPosition)
    {
        return array_filter($this->menus, function ($menu) use ($minPosition, $maxPosition) {
            $position = $menu->getPosition();

            return $position >= $minPosition && $position <= $maxPosition;
        });
    }

    /**
     * Clear all menus
     */
    public function clear()
    {
        $this->menus = [];
        $this->currentMenu = null;
        $this->clearCache();

        return $this;
    }

    /**
     * Enable or disable caching
     */
    public function enableCache($enable = true)
    {
        $this->cacheEnabled = $enable;

        return $this;
    }

    /**
     * Set cache TTL
     */
    public function setCacheTtl($ttl)
    {
        $this->cacheTtl = $ttl;

        return $this;
    }

    /**
     * Get cache key for menu group
     */
    protected function getCacheKey($groupName = null)
    {
        $user = Auth::user();
        $userId = $user ? $user->id : 'guest';
        $bypassPermissions = $this->bypassPermissions ? 'bypass' : 'normal';

        return $this->cachePrefix.($groupName ?? 'all').'_'.$userId.'_'.$bypassPermissions;
    }

    /**
     * Get cached menu data
     */
    protected function getCachedMenus($groupName = null)
    {
        if (! $this->cacheEnabled) {
            return null;
        }

        $cacheKey = $this->getCacheKey($groupName);

        return Cache::get($cacheKey);
    }

    /**
     * Cache menu data
     */
    protected function cacheMenus($groupName, $data)
    {
        if (! $this->cacheEnabled) {
            return;
        }

        $cacheKey = $this->getCacheKey($groupName);
        Cache::put($cacheKey, $data, $this->cacheTtl);
    }

    /**
     * Clear menu cache
     */
    public function clearCache($groupName = null)
    {
        if ($groupName) {
            $cacheKey = $this->getCacheKey($groupName);
            Cache::forget($cacheKey);
        } else {
            // Clear all menu cache keys
            $tags = ['menu'];
            if (Cache::getStore() instanceof \Illuminate\Cache\TaggableStore) {
                Cache::tags($tags)->flush();
            } else {
                // Fallback: clear by pattern
                $user = Auth::user();
                $userId = $user ? $user->id : 'guest';
                $patterns = [
                    $this->cachePrefix.'*_'.$userId.'_*',
                ];

                foreach ($patterns as $pattern) {
                    // This is a simplified approach - in production you might want to use Redis SCAN
                    Cache::forget($pattern);
                }
            }
        }
    }

    /**
     * Remove menu by key
     */
    public function remove($key)
    {
        if (isset($this->menus[$key])) {
            unset($this->menus[$key]);
            $this->clearCache(); // Clear cache when menu is removed
        }

        return $this;
    }

    /**
     * Update existing menu
     */
    public function update($key, $title = null, $url = null, $icon = null)
    {
        $menu = $this->get($key);
        if ($menu) {
            if ($title !== null) {
                $menu->setTitle($title);
            }
            if ($url !== null) {
                $menu->setUrl($url);
            }
            if ($icon !== null) {
                $menu->setIcon($icon);
            }
            $this->clearCache(); // Clear cache when menu is updated
        }

        return $this;
    }
}

/**
 * Menu Item Class
 */
class MenuItem
{
    protected $name;

    protected $title;

    protected $url;

    protected $icon;

    protected $position = 0;

    protected $permissions = null;

    protected $children = [];

    public function __construct($title, $url = '#', $icon = '')
    {
        $this->title = $title;
        $this->url = $url;
        $this->icon = $icon;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getUrl()
    {
        if (Route::has($this->url)) {
            return route($this->url);
        }

        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    public function getPermissions()
    {
        return $this->permissions;
    }

    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;

        return $this;
    }

    public function hasPermissions()
    {
        return $this->permissions !== null;
    }

    public function addChild($child)
    {
        $this->children[] = $child;

        return $this;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function hasChildren()
    {
        return ! empty($this->children);
    }
}
