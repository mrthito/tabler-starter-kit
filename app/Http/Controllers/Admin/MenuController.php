<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MenuController extends Controller
{
    /**
     * Display a listing of the menus.
     */
    public function index(): View
    {
        $menus = Menu::withCount('allItems')
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.menus.index', compact('menus'));
    }

    /**
     * Show the form for creating a new menu.
     */
    public function create(): View
    {
        return view('admin.menus.create');
    }

    /**
     * Store a newly created menu in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'name' => ['nullable', 'string', 'max:255', 'unique:menus,name'],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        // Generate name from title if not provided
        if (empty($validated['name'])) {
            $validated['name'] = Str::slug($validated['title']);

            // Ensure unique name
            $originalName = $validated['name'];
            $counter = 1;
            while (Menu::where('name', $validated['name'])->exists()) {
                $validated['name'] = $originalName . '-' . $counter;
                $counter++;
            }
        }

        $validated['status'] = $validated['status'] ?? true;
        $validated['order'] = $validated['order'] ?? 0;

        Menu::create($validated);

        return redirect()
            ->route('admin.appearance.menus.index')
            ->with('success', __('Menu created successfully'));
    }

    /**
     * Display the specified menu with items for management.
     */
    public function show(Request $request, Menu $menu)
    {
        // Return JSON for a specific item (for edit modal)
        if ($request->has('item')) {
            $item = MenuItem::where('id', $request->item)
                ->where('menu_id', $menu->id)
                ->firstOrFail();

            return response()->json([
                'id' => $item->id,
                'title' => $item->title,
                'url' => $item->url,
                'route' => $item->route,
                'icon' => $item->icon,
                'css_class' => $item->css_class,
                'target' => $item->target,
                'status' => $item->status,
                'is_visible' => $item->is_visible,
            ]);
        }

        $menu->load([
            'allItems' => function ($query) {
                $query->orderBy('order');
            },
        ]);

        // Build tree structure - get root items and load children recursively
        $allItems = $menu->allItems;
        $rootItems = $allItems->whereNull('parent_id')->sortBy('order');

        // Attach children to each item
        foreach ($allItems as $item) {
            $item->children = $allItems->where('parent_id', $item->id)->sortBy('order')->values();
        }

        return view('admin.menus.show', compact('menu', 'rootItems'));
    }

    /**
     * Show the form for editing the specified menu.
     */
    public function edit(Menu $menu): View
    {
        return view('admin.menus.edit', compact('menu'));
    }

    /**
     * Update the specified menu in storage.
     */
    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'name' => ['nullable', 'string', 'max:255', 'unique:menus,name,' . $menu->id],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        $menu->update($validated);

        return redirect()
            ->route('admin.appearance.menus.index')
            ->with('success', __('Menu updated successfully'));
    }

    /**
     * Remove the specified menu from storage.
     */
    public function destroy(Menu $menu): RedirectResponse
    {
        $menu->delete();

        return redirect()
            ->route('admin.appearance.menus.index')
            ->with('success', __('Menu deleted successfully'));
    }

    /**
     * Store a menu item.
     */
    public function storeItem(Request $request, Menu $menu): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:255'],
            'route' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'css_class' => ['nullable', 'string', 'max:255'],
            'target' => ['nullable', 'string', 'in:_self,_blank,_parent,_top'],
            'parent_id' => ['nullable', 'exists:menu_items,id'],
            'order' => ['nullable', 'integer', 'min:0'],
            'status' => ['boolean'],
            'is_visible' => ['boolean'],
            'linkable_type' => ['nullable', 'string'],
            'linkable_id' => ['nullable', 'integer'],
            'permissions' => ['nullable', 'array'],
            'roles' => ['nullable', 'array'],
        ]);

        $validated['menu_id'] = $menu->id;
        $validated['status'] = $validated['status'] ?? true;
        $validated['is_visible'] = $validated['is_visible'] ?? true;
        $validated['target'] = $validated['target'] ?? '_self';
        $validated['order'] = $validated['order'] ?? 0;

        MenuItem::create($validated);

        return redirect()
            ->route('admin.appearance.menus.show', $menu)
            ->with('success', __('Menu item added successfully'));
    }

    /**
     * Update a menu item.
     */
    public function updateItem(Request $request, Menu $menu, MenuItem $menuItem): RedirectResponse
    {
        // Ensure menu item belongs to this menu
        if ($menuItem->menu_id !== $menu->id) {
            abort(404);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:255'],
            'route' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'css_class' => ['nullable', 'string', 'max:255'],
            'target' => ['nullable', 'string', 'in:_self,_blank,_parent,_top'],
            'parent_id' => ['nullable', 'exists:menu_items,id'],
            'order' => ['nullable', 'integer', 'min:0'],
            'status' => ['boolean'],
            'is_visible' => ['boolean'],
            'linkable_type' => ['nullable', 'string'],
            'linkable_id' => ['nullable', 'integer'],
            'permissions' => ['nullable', 'array'],
            'roles' => ['nullable', 'array'],
        ]);

        // Prevent item from being its own parent
        if (isset($validated['parent_id']) && $validated['parent_id'] == $menuItem->id) {
            return redirect()
                ->route('admin.appearance.menus.show', $menu)
                ->with('error', __('Menu item cannot be its own parent'));
        }

        $menuItem->update($validated);

        return redirect()
            ->route('admin.appearance.menus.show', $menu)
            ->with('success', __('Menu item updated successfully'));
    }

    /**
     * Delete a menu item.
     */
    public function destroyItem(Menu $menu, MenuItem $menuItem): RedirectResponse
    {
        // Ensure menu item belongs to this menu
        if ($menuItem->menu_id !== $menu->id) {
            abort(404);
        }

        $menuItem->delete();

        return redirect()
            ->route('admin.appearance.menus.show', $menu)
            ->with('success', __('Menu item deleted successfully'));
    }

    /**
     * Reorder menu items (AJAX endpoint).
     */
    public function reorderItems(Request $request, Menu $menu): JsonResponse
    {
        $validated = $request->validate([
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'exists:menu_items,id'],
            'items.*.parent_id' => ['nullable', 'exists:menu_items,id'],
            'items.*.order' => ['required', 'integer', 'min:0'],
        ]);

        foreach ($validated['items'] as $itemData) {
            $menuItem = MenuItem::find($itemData['id']);

            // Ensure menu item belongs to this menu
            if ($menuItem && $menuItem->menu_id === $menu->id) {
                $menuItem->update([
                    'parent_id' => $itemData['parent_id'] ?? null,
                    'order' => $itemData['order'],
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => __('Menu items reordered successfully'),
        ]);
    }

    /**
     * Link a menu item to a model.
     */
    public function linkToModel(Request $request, Menu $menu, MenuItem $menuItem): RedirectResponse
    {
        $validated = $request->validate([
            'linkable_type' => ['required', 'string'],
            'linkable_id' => ['required', 'integer'],
            'title' => ['nullable', 'string', 'max:255'],
        ]);

        // Ensure menu item belongs to this menu
        if ($menuItem->menu_id !== $menu->id) {
            abort(404);
        }

        $modelClass = $validated['linkable_type'];
        $modelId = $validated['linkable_id'];

        // Verify model exists
        if (! class_exists($modelClass) || ! ($model = $modelClass::find($modelId))) {
            return redirect()
                ->route('admin.appearance.menus.show', $menu)
                ->with('error', __('Model not found'));
        }

        $menuItem->linkTo($model, $validated['title'] ?? null);

        return redirect()
            ->route('admin.appearance.menus.show', $menu)
            ->with('success', __('Menu item linked successfully'));
    }
}
