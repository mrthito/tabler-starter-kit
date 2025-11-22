<x-app-layout :page="$menu->title" layout="admin">

    <x-slot name="pretitle">{{ __('Appearance') }}</x-slot>
    <x-slot name="subtitle">{{ __('Manage Menu: :title', ['title' => $menu->title]) }}</x-slot>

    <x-slot name="actions">
        <a href="{{ route('admin.appearance.menus.edit', $menu) }}" class="btn btn-secondary">
            <i class="ti ti-settings icon icon-1"></i>
            {{ __('Menu Settings') }}
        </a>
        <a href="{{ route('admin.appearance.menus.index') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-left icon icon-1"></i>
            {{ __('Back') }}
        </a>
    </x-slot>

    <x-common.alert />

    <div class="row row-cards">
        <!-- Menu Items Panel -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Add Menu Items') }}</h3>
                </div>
                <div class="card-body">
                    <form id="addMenuItemForm" action="{{ route('admin.appearance.menus.items.store', $menu) }}"
                        method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label required">{{ __('Title') }}</label>
                            <input type="text" class="form-control" name="title" required
                                placeholder="{{ __('Menu Item Title') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Link Type') }}</label>
                            <select class="form-select" name="link_type" id="linkType">
                                <option value="url">{{ __('Custom URL') }}</option>
                                <option value="route">{{ __('Route') }}</option>
                                <option value="linkable">{{ __('Link to Resource') }}</option>
                            </select>
                        </div>

                        <!-- Custom URL -->
                        <div class="mb-3" id="urlField">
                            <label class="form-label">{{ __('URL') }}</label>
                            <input type="text" class="form-control" name="url"
                                placeholder="{{ __('https://example.com or /page') }}">
                        </div>

                        <!-- Route -->
                        <div class="mb-3 d-none" id="routeField">
                            <label class="form-label">{{ __('Route Name') }}</label>
                            <input type="text" class="form-control" name="route"
                                placeholder="{{ __('e.g., admin.dashboard') }}">
                        </div>

                        <!-- Linkable -->
                        <div class="mb-3 d-none" id="linkableField">
                            <label class="form-label">{{ __('Resource Type') }}</label>
                            <select class="form-select" name="linkable_type">
                                <option value="">{{ __('Select Resource Type') }}</option>
                                <option value="App\Models\Page">{{ __('Page') }}</option>
                                <option value="App\Models\Post">{{ __('Post') }}</option>
                                <option value="App\Models\Category">{{ __('Category') }}</option>
                            </select>
                        </div>

                        <div class="mb-3 d-none" id="linkableIdField">
                            <label class="form-label">{{ __('Resource') }}</label>
                            <select class="form-select" name="linkable_id" id="linkableResource">
                                <option value="">{{ __('Select Resource') }}</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Icon') }}</label>
                            <input type="text" class="form-control" name="icon"
                                placeholder="{{ __('e.g., ti ti-home') }}">
                            <small class="form-hint">{{ __('Tabler icon class name') }}</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('CSS Class') }}</label>
                            <input type="text" class="form-control" name="css_class"
                                placeholder="{{ __('Additional CSS classes') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Target') }}</label>
                            <select class="form-select" name="target">
                                <option value="_self">{{ __('Same Window') }}</option>
                                <option value="_blank">{{ __('New Window') }}</option>
                                <option value="_parent">{{ __('Parent Window') }}</option>
                                <option value="_top">{{ __('Top Window') }}</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <input type="hidden" name="status" value="0">
                            <label class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status" value="1" checked>
                                <span class="form-check-label">{{ __('Active') }}</span>
                            </label>
                        </div>

                        <div class="mb-3">
                            <input type="hidden" name="is_visible" value="0">
                            <label class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_visible" value="1"
                                    checked>
                                <span class="form-check-label">{{ __('Visible') }}</span>
                            </label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-plus icon icon-1"></i>
                                {{ __('Add to Menu') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Menu Structure Info -->
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Menu Information') }}</h3>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-6">{{ __('Name') }}:</dt>
                        <dd class="col-6"><code>{{ $menu->name }}</code></dd>

                        <dt class="col-6">{{ __('Location') }}:</dt>
                        <dd class="col-6">
                            {{ $menu->location ?: __('None') }}
                        </dd>

                        <dt class="col-6">{{ __('Items') }}:</dt>
                        <dd class="col-6">
                            <span class="badge bg-secondary-lt">
                                {{ $menu->allItems->count() }}
                            </span>
                        </dd>

                        <dt class="col-6">{{ __('Status') }}:</dt>
                        <dd class="col-6">
                            @if ($menu->status)
                                <span class="badge bg-success-lt">{{ __('Active') }}</span>
                            @else
                                <span class="badge bg-danger-lt">{{ __('Inactive') }}</span>
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Menu Structure -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Menu Structure') }}</h3>
                    <div class="card-actions">
                        <button class="btn btn-sm btn-outline-primary" id="saveMenuStructure">
                            <i class="ti ti-device-floppy icon icon-1"></i>
                            {{ __('Save Structure') }}
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="menuItemsList" class="menu-items-list">
                        @if ($rootItems && $rootItems->count() > 0)
                            <ul class="sortable-menu" data-parent-id="">
                                @foreach ($rootItems as $item)
                                    @include('admin.menus.partials.menu-item', [
                                        'item' => $item,
                                        'level' => 0,
                                    ])
                                @endforeach
                            </ul>
                        @else
                            <div class="empty">
                                <div class="empty-img">
                                    <i class="ti ti-list" style="font-size: 3rem;"></i>
                                </div>
                                <p class="empty-title">{{ __('No menu items') }}</p>
                                <p class="empty-subtitle text-muted">
                                    {{ __('Add items to your menu using the panel on the left.') }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Menu Item Modal -->
    <div class="modal modal-blur fade" id="editMenuItemModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="editMenuItemForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Edit Menu Item') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Same fields as add form, populated via JavaScript -->
                        <div class="mb-3">
                            <label class="form-label required">{{ __('Title') }}</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('URL') }}</label>
                            <input type="text" class="form-control" name="url">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Route') }}</label>
                            <input type="text" class="form-control" name="route">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Icon') }}</label>
                            <input type="text" class="form-control" name="icon">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('CSS Class') }}</label>
                            <input type="text" class="form-control" name="css_class">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Target') }}</label>
                            <select class="form-select" name="target">
                                <option value="_self">{{ __('Same Window') }}</option>
                                <option value="_blank">{{ __('New Window') }}</option>
                                <option value="_parent">{{ __('Parent Window') }}</option>
                                <option value="_top">{{ __('Top Window') }}</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <input type="hidden" name="status" value="0">
                            <label class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status" value="1">
                                <span class="form-check-label">{{ __('Active') }}</span>
                            </label>
                        </div>

                        <div class="mb-3">
                            <input type="hidden" name="is_visible" value="0">
                            <label class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_visible" value="1">
                                <span class="form-check-label">{{ __('Visible') }}</span>
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-check icon icon-1"></i>
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Link Type Toggle
                const linkType = document.getElementById('linkType');
                const urlField = document.getElementById('urlField');
                const routeField = document.getElementById('routeField');
                const linkableField = document.getElementById('linkableField');
                const linkableIdField = document.getElementById('linkableIdField');

                linkType?.addEventListener('change', function() {
                    urlField.classList.add('d-none');
                    routeField.classList.add('d-none');
                    linkableField.classList.add('d-none');
                    linkableIdField.classList.add('d-none');

                    if (this.value === 'url') {
                        urlField.classList.remove('d-none');
                    } else if (this.value === 'route') {
                        routeField.classList.remove('d-none');
                    } else if (this.value === 'linkable') {
                        linkableField.classList.remove('d-none');
                        linkableIdField.classList.remove('d-none');
                    }
                });

                // Sortable Menu Items with nested support
                const menuItemsList = document.getElementById('menuItemsList');
                const sortableInstances = [];

                function initializeSortable(element) {
                    // Destroy existing instance if any
                    if (element.dataset.sortableInitialized === 'true') {
                        const existingInstance = sortableInstances.find(s => s && s.el === element);
                        if (existingInstance) {
                            try {
                                existingInstance.destroy();
                            } catch (e) {
                                console.warn('Error destroying sortable:', e);
                            }
                            const index = sortableInstances.indexOf(existingInstance);
                            if (index > -1) {
                                sortableInstances.splice(index, 1);
                            }
                        }
                    }

                    const sortable = new Sortable(element, {
                        animation: 200,
                        handle: '.menu-item-handle',
                        group: {
                            name: 'menu-items',
                            pull: true,
                            put: true
                        },
                        fallbackOnBody: true,
                        swapThreshold: 0.65,
                        forceFallback: false,
                        ghostClass: 'sortable-ghost',
                        chosenClass: 'sortable-chosen',
                        dragClass: 'sortable-drag',
                        emptyInsertThreshold: 10,
                        onStart: function(evt) {
                            // Add drop zone indicators to all menu items
                            document.querySelectorAll('.menu-item').forEach(li => {
                                if (li !== evt.item) {
                                    li.classList.add('drop-zone-active');
                                }
                            });
                        },
                        onMove: function(evt) {
                            const related = evt.related;
                            const dragged = evt.dragged;
                            const target = evt.to;
                            const targetLi = target.closest('li.menu-item');

                            // Remove hover from all items first
                            document.querySelectorAll('.menu-item').forEach(li => {
                                if (li !== related && li !== dragged) {
                                    li.classList.remove('drop-zone-hover');
                                }
                            });

                            // Highlight the target LI if hovering over it
                            if (targetLi && targetLi !== dragged) {
                                targetLi.classList.add('drop-zone-hover');
                            }

                            return true;
                        },
                        onEnd: function(evt) {
                            const item = evt.item;
                            const toList = evt.to;
                            const fromList = evt.from;

                            // Remove all drop zone indicators
                            document.querySelectorAll('.menu-item').forEach(li => {
                                li.classList.remove('drop-zone-active', 'drop-zone-hover');
                            });

                            // Check if dropped onto another menu item (LI) instead of a list (UL)
                            const targetLi = evt.to.closest('li.menu-item[data-item-id]');

                            // If dropped directly on an LI (not on a UL), make it a child
                            if (targetLi && targetLi !== item && !toList.classList.contains(
                                    'sortable-menu')) {
                                // Item was dropped on another LI, create nested structure
                                let nestedUl = targetLi.querySelector(':scope > ul.sortable-menu');

                                // Create nested UL if it doesn't exist
                                if (!nestedUl) {
                                    nestedUl = document.createElement('ul');
                                    nestedUl.className = 'sortable-menu menu-item-children';
                                    nestedUl.setAttribute('data-parent-id', targetLi.getAttribute(
                                        'data-item-id'));
                                    targetLi.appendChild(nestedUl);
                                }

                                // Remove d-none class if present
                                nestedUl.classList.remove('d-none');

                                // Ensure item is in the nested UL
                                if (item.parentElement !== nestedUl) {
                                    nestedUl.appendChild(item);
                                }

                                // Initialize Sortable for nested UL
                                initializeSortable(nestedUl);
                            } else if (toList.classList.contains('sortable-menu')) {
                                // Dropped in a UL, ensure it's properly placed
                                const listParent = toList.closest('li.menu-item[data-item-id]');
                                if (!listParent) {
                                    // Root level - ensure item is in the list
                                    if (item.parentElement !== toList) {
                                        toList.appendChild(item);
                                    }
                                }
                            }

                            // Ensure all menu items have a nested UL for dropping
                            document.querySelectorAll('.menu-item').forEach(li => {
                                let nestedUl = li.querySelector(':scope > ul.sortable-menu');
                                if (!nestedUl) {
                                    nestedUl = document.createElement('ul');
                                    nestedUl.className = 'sortable-menu menu-item-children';
                                    nestedUl.setAttribute('data-parent-id', li.getAttribute(
                                        'data-item-id'));
                                    li.appendChild(nestedUl);
                                }

                                // Always ensure nested ULs are visible (no d-none)
                                nestedUl.classList.remove('d-none');

                                // Always ensure sortable is initialized for nested ULs
                                if (nestedUl.dataset.sortableInitialized !== 'true') {
                                    initializeSortable(nestedUl);
                                }
                            });

                            // Auto-save structure after drop
                            setTimeout(() => {
                                const items = [];
                                const rootUl = menuItemsList.querySelector(
                                    'ul.sortable-menu[data-parent-id=""]');

                                if (rootUl) {
                                    processItems(rootUl, items, null);
                                }

                                // Save structure automatically
                                fetch('{{ route('admin.appearance.menus.items.reorder', $menu) }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector(
                                                'meta[name="csrf-token"]').content
                                        },
                                        body: JSON.stringify({
                                            items: items
                                        })
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            if (typeof ToastMagic !== 'undefined') {
                                                ToastMagic.success('Menu structure saved');
                                            }
                                            // Update structure locally
                                            updateMenuStructure();
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error auto-saving menu structure:',
                                            error);
                                        if (typeof ToastMagic !== 'undefined') {
                                            ToastMagic.error('Error saving menu structure');
                                        }
                                    });
                            }, 300);
                        }
                    });

                    element.dataset.sortableInitialized = 'true';
                    sortableInstances.push(sortable);
                    return sortable;
                }

                function reinitializeAllSortables() {
                    // Clear existing sortables
                    sortableInstances.forEach(instance => {
                        if (instance && instance.el) {
                            try {
                                instance.destroy();
                            } catch (e) {
                                // Ignore errors
                            }
                            instance.el.dataset.sortableInitialized = 'false';
                        }
                    });
                    sortableInstances.length = 0;

                    // Reinitialize all sortable menus (including newly created ones)
                    if (menuItemsList) {
                        menuItemsList.querySelectorAll('.sortable-menu').forEach(ul => {
                            if (!ul.classList.contains('d-none')) {
                                ul.dataset.sortableInitialized = 'false';
                                initializeSortable(ul);
                            }
                        });
                    }
                }

                // Initialize sortables on page load
                if (menuItemsList) {
                    // Initialize root level sortable
                    const rootUl = menuItemsList.querySelector('ul.sortable-menu[data-parent-id=""]');
                    if (rootUl) {
                        initializeSortable(rootUl);
                    }

                    // Initialize all existing nested sortables (not hidden)
                    menuItemsList.querySelectorAll('.sortable-menu:not(.d-none)').forEach(ul => {
                        if (ul !== rootUl && ul.dataset.sortableInitialized !== 'true') {
                            initializeSortable(ul);
                        }
                    });
                }

                // Update menu structure
                function updateMenuStructure() {
                    const items = [];
                    const rootUl = menuItemsList.querySelector('ul.sortable-menu[data-parent-id=""]');

                    if (rootUl) {
                        processItems(rootUl, items, null);
                    }
                }

                function processItems(ul, items, parentId) {
                    if (!ul) return;

                    ul.querySelectorAll(':scope > li[data-item-id]').forEach((li, index) => {
                        const itemId = li.getAttribute('data-item-id');

                        // Find nested UL - check both direct child and nested
                        let nestedUl = li.querySelector(':scope > ul.sortable-menu');
                        if (!nestedUl) {
                            // Check if there's a nested UL at any level
                            nestedUl = li.querySelector('ul.sortable-menu');
                        }

                        items.push({
                            id: itemId,
                            parent_id: parentId,
                            order: index
                        });

                        if (nestedUl) {
                            processItems(nestedUl, items, itemId);
                        }
                    });
                }

                // Save menu structure
                document.getElementById('saveMenuStructure')?.addEventListener('click', function() {
                    const items = [];
                    const rootUl = menuItemsList.querySelector('ul.sortable-menu[data-parent-id=""]');

                    if (rootUl) {
                        processItems(rootUl, items, null);
                    }

                    fetch('{{ route('admin.appearance.menus.items.reorder', $menu) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                items: items
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                if (typeof ToastMagic !== 'undefined') {
                                    ToastMagic.success(data.message || 'Menu structure saved successfully');
                                } else {
                                    alert(data.message || 'Menu structure saved successfully');
                                }
                                setTimeout(() => location.reload(), 1000);
                            }
                        })
                        .catch(error => {
                            if (typeof ToastMagic !== 'undefined') {
                                ToastMagic.error('Error saving menu structure');
                            } else {
                                alert('Error saving menu structure');
                            }
                            console.error(error);
                        });
                });

                // Edit menu item
                window.editMenuItem = function(itemId) {
                    fetch(`{{ route('admin.appearance.menus.show', $menu) }}?item=${itemId}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            const form = document.getElementById('editMenuItemForm');
                            form.action =
                                `{{ url('admin/appearance/menus/' . $menu->id . '/items') }}/${itemId}`;
                            form.querySelector('[name="title"]').value = data.title || '';
                            form.querySelector('[name="url"]').value = data.url || '';
                            form.querySelector('[name="route"]').value = data.route || '';
                            form.querySelector('[name="icon"]').value = data.icon || '';
                            form.querySelector('[name="css_class"]').value = data.css_class || '';
                            form.querySelector('[name="target"]').value = data.target || '_self';
                            form.querySelector('[name="status"]').checked = data.status;
                            form.querySelector('[name="is_visible"]').checked = data.is_visible;

                            new bootstrap.Modal(document.getElementById('editMenuItemModal')).show();
                        })
                        .catch(error => {
                            console.error(error);
                            if (typeof ToastMagic !== 'undefined') {
                                ToastMagic.error('Error loading menu item');
                            } else {
                                alert('Error loading menu item');
                            }
                        });
                };

                // Delete menu item
                window.deleteMenuItem = function(itemId) {
                    if (!confirm('{{ __('Are you sure you want to delete this menu item?') }}')) {
                        return;
                    }

                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ url('admin/appearance/menus/' . $menu->id . '/items') }}/${itemId}`;

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = document.querySelector('meta[name="csrf-token"]').content;
                    form.appendChild(csrf);

                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';
                    form.appendChild(method);

                    document.body.appendChild(form);
                    form.submit();
                };
            });
        </script>
    @endpush

    @push('styles')
        <style>
            .menu-items-list ul {
                list-style: none;
                padding: 0;
                margin: 0;
            }

            .menu-items-list li {
                border-radius: 0.375rem;
                background: var(--tblr-bg-surface);
            }

            .menu-items-list li:hover {
                background: var(--tblr-bg-surface-secondary);
            }

            .menu-item-handle {
                cursor: move;
                color: var(--tblr-secondary);
                margin-right: 0.5rem;
            }

            .menu-item-handle:hover {
                color: var(--tblr-primary);
            }

            .sortable-ghost {
                opacity: 0.4;
                background: var(--tblr-bg-surface-secondary);
            }

            .sortable-chosen {
                cursor: grabbing;
            }

            .sortable-drag {
                opacity: 0.8;
            }

            .menu-item {
                position: relative;
                min-height: 2.5rem;
                margin-bottom: 0.5rem;
            }

            .menu-item-actions {
                float: right;
            }

            .menu-item-children {
                margin-left: 1rem;
                margin-top: 0.5rem;
                padding-left: 1rem;
                border-left: 2px solid var(--tblr-border-color);
            }

            /* Child menu items - indent nested items */
            .menu-item-children>li.menu-item {
                margin-left: 10px;
            }

            /* Nested child items (grandchildren and deeper) */
            .menu-item-children .menu-item-children>li.menu-item {
                margin-left: 10px;
            }

            /* Empty nested ULs - show minimal height for dropping */
            .menu-item-children:empty {
                min-height: 1rem;
                border-left-style: dashed;
                opacity: 0.5;
            }

            .menu-item-children:empty:hover {
                opacity: 1;
                border-left-color: var(--tblr-primary);
                background: var(--tblr-primary-lt);
            }

            .menu-item-content {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0.75rem;
                border: 1px solid var(--tblr-border-color);
                border-radius: 0.375rem;
                background: var(--tblr-bg-surface);
                margin-bottom: 0.25rem;
                transition: all 0.2s ease;
            }

            .menu-item-content:hover {
                background: var(--tblr-bg-surface-secondary);
                border-color: var(--tblr-primary);
            }

            /* Drop zone indicators */
            .menu-item.drop-zone-active {
                position: relative;
            }

            .menu-item.drop-zone-active::after {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                border: 2px dashed var(--tblr-primary);
                border-radius: 0.375rem;
                pointer-events: none;
                opacity: 0.5;
                z-index: 10;
            }

            .menu-item.drop-zone-hover {
                background: var(--tblr-primary-lt) !important;
                border-color: var(--tblr-primary) !important;
            }

            .menu-item.drop-zone-hover .menu-item-content {
                border-color: var(--tblr-primary);
                background: var(--tblr-primary-lt);
                transform: scale(1.02);
            }

            /* Drop placeholder */
            .menu-item-drop-placeholder {
                height: 2px;
                margin: 0.25rem 0;
                background: var(--tblr-primary);
                border-radius: 2px;
                opacity: 0.8;
            }

            .menu-item-drop-placeholder .drop-placeholder-line {
                height: 100%;
                width: 100%;
                background: var(--tblr-primary);
                border-radius: 2px;
                box-shadow: 0 0 4px var(--tblr-primary);
            }

            .menu-item-drop-placeholder.d-none {
                display: none !important;
            }
        </style>
    @endpush
</x-app-layout>
