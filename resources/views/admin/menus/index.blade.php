<x-app-layout :page="__('Menus')" layout="admin">

    <x-slot name="pretitle">{{ __('Appearance') }}</x-slot>
    <x-slot name="subtitle">{{ __('Menus') }}</x-slot>

    <x-slot name="actions">
        <a href="{{ route('admin.appearance.menus.create') }}" class="btn btn-primary">
            <i class="ti ti-plus icon icon-1"></i>
            {{ __('Create Menu') }}
        </a>
    </x-slot>

    <x-common.alert />

    <div class="row row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Menus') }}</h3>
                    <div class="card-actions">
                        <button class="btn btn-sm btn-outline-primary" onclick="location.reload()">
                            <i class="ti ti-refresh icon icon-1"></i>
                            {{ __('Refresh') }}
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Location') }}</th>
                                    <th>{{ __('Items') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Order') }}</th>
                                    <th>{{ __('Created') }}</th>
                                    <th class="w-1"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($menus as $menu)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="{{ route('admin.appearance.menus.show', $menu) }}"
                                                    class="text-decoration-none">
                                                    <strong>{{ $menu->title }}</strong>
                                                </a>
                                            </div>
                                            @if ($menu->description)
                                                <div class="text-muted text-sm">
                                                    {{ Str::limit($menu->description, 50) }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <code>{{ $menu->name }}</code>
                                        </td>
                                        <td>
                                            @if ($menu->location)
                                                <span class="badge bg-info-lt">
                                                    {{ $menu->location }}
                                                </span>
                                            @else
                                                <span class="text-muted">{{ __('None') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary-lt">
                                                <i class="ti ti-list icon icon-1"></i>
                                                {{ $menu->all_items_count ?? 0 }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($menu->status)
                                                <span class="badge bg-success-lt">
                                                    <i class="ti ti-check icon icon-1"></i>
                                                    {{ __('Active') }}
                                                </span>
                                            @else
                                                <span class="badge bg-danger-lt">
                                                    <i class="ti ti-x icon icon-1"></i>
                                                    {{ __('Inactive') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $menu->order }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">
                                                {{ $menu->created_at->format('M d, Y') }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <a href="{{ route('admin.appearance.menus.show', $menu) }}"
                                                    class="btn btn-sm btn-secondary" title="{{ __('Manage Items') }}">
                                                    <i class="ti ti-list"></i>
                                                </a>
                                                <a href="{{ route('admin.appearance.menus.edit', $menu) }}"
                                                    class="btn btn-sm btn-primary" title="{{ __('Edit') }}">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.appearance.menus.destroy', $menu) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('{{ __('Are you sure you want to delete this menu?') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        title="{{ __('Delete') }}">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="empty">
                                                <div class="empty-img">
                                                    <i class="ti ti-menu-2" style="font-size: 3rem;"></i>
                                                </div>
                                                <p class="empty-title">{{ __('No menus found') }}</p>
                                                <p class="empty-subtitle text-muted">
                                                    {{ __('Get started by creating a new menu.') }}
                                                </p>
                                                <div class="empty-action">
                                                    <a href="{{ route('admin.appearance.menus.create') }}"
                                                        class="btn btn-primary">
                                                        <i class="ti ti-plus icon icon-1"></i>
                                                        {{ __('Create Menu') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($menus->hasPages())
                    <div class="card-footer d-flex align-items-center">
                        <div class="dropdown">
                            <a class="btn dropdown-toggle" data-bs-toggle="dropdown">
                                <span id="page-count" class="me-1">{{ $menus->perPage() }}</span>
                                <span>{{ __('records') }}</span>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" data-per-page="10">10 {{ __('records') }}</a>
                                <a class="dropdown-item" data-per-page="20">20 {{ __('records') }}</a>
                                <a class="dropdown-item" data-per-page="50">50 {{ __('records') }}</a>
                                <a class="dropdown-item" data-per-page="100">100 {{ __('records') }}</a>
                            </div>
                        </div>
                        {{ $menus->withQueryString()->links('pagination::bs5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
