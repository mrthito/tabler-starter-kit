<li data-item-id="{{ $item->id }}" data-order="{{ $item->order }}" class="menu-item">
    <div class="menu-item-content">
        <div class="d-flex align-items-center flex-grow-1">
            <i class="ti ti-grip-vertical menu-item-handle"></i>
            @if ($item->icon)
                <i class="{{ $item->icon }} me-2"></i>
            @endif
            <strong>{{ $item->title }}</strong>
            @if ($item->url || $item->route)
                <span class="text-muted ms-2">
                    (<small>{{ $item->url ?: $item->route }}</small>)
                </span>
            @endif
            @if (!$item->status)
                <span class="badge bg-warning-lt ms-2">{{ __('Inactive') }}</span>
            @endif
            @if (!$item->is_visible)
                <span class="badge bg-secondary-lt ms-2">{{ __('Hidden') }}</span>
            @endif
        </div>
        <div class="menu-item-actions">
            <button type="button" class="btn btn-sm btn-secondary" onclick="editMenuItem({{ $item->id }})"
                title="{{ __('Edit') }}">
                <i class="ti ti-edit"></i>
            </button>
            <button type="button" class="btn btn-sm btn-danger" onclick="deleteMenuItem({{ $item->id }})"
                title="{{ __('Delete') }}">
                <i class="ti ti-trash"></i>
            </button>
        </div>
    </div>
    {{-- Always create UL for nesting support - always visible for dropping --}}
    <ul class="sortable-menu menu-item-children" data-parent-id="{{ $item->id }}">
        @if ($item->children && $item->children->count() > 0)
            @foreach ($item->children as $child)
                @include('admin.menus.partials.menu-item', [
                    'item' => $child,
                    'level' => ($level ?? 0) + 1,
                ])
            @endforeach
        @endif
    </ul>
</li>
