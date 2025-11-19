<x-app-layout :page="__('Roles')" layout="admin">

    <x-slot name="pretitle">{{ __('Roles') }}</x-slot>
    <x-slot name="subtitle">{{ __('Manage Roles') }}</x-slot>

    <x-slot name="page-actions">
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">{{ __('Create Role') }}</a>
    </x-slot>

    <div class="row row-cards">
        <div class="col-12">
            <x-table.card :title="__('Roles')" :description="__('Manage all roles here')" :export="[
                'pdf' => route('admin.roles.export', 'pdf'),
                'xls' => route('admin.roles.export', 'xls'),
                'csv' => route('admin.roles.export', 'csv'),
            ]" :action="route('admin.roles.destroy', 'bulk')">
                <x-table.table>
                    <x-slot name="thead">
                        <tr>
                            <th class="w-1"></th>
                            <th>
                                <button class="table-sort d-flex justify-content-between"
                                    data-sort="sort-name">{{ __('Name') }}</button>
                            </th>
                            <th>
                                <button class="table-sort d-flex justify-content-between"
                                    data-sort="sort-status">{{ __('Guard') }}</button>
                            </th>
                            <th>
                                <button class="table-sort d-flex justify-content-between"
                                    data-sort="sort-date">{{ __('Status') }}</button>
                            </th>
                            <th width="10px"></th>
                        </tr>
                    </x-slot>
                    <x-slot name="tbody">
                        @forelse ($roles as $role)
                            <tr>
                                <td>
                                    <input class="form-check-input m-0 align-middle table-selectable-check"
                                        name="row[]" type="checkbox" value="{{ $role->id }}">
                                </td>
                                <td class="sort-name">
                                    {{ $role->name }}
                                </td>
                                <td class="sort-status">
                                    <span
                                        class="badge bg-{{ $role->guard === 'admin' ? 'success-lt' : ($role->guard === 'admin' ? 'info-lt' : 'warning-lt') }}">
                                        {{ $role->guard }}
                                    </span>
                                </td>
                                <td class="sort-date">
                                    @if ($role->status)
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
                                <td class="text-end">
                                    <a href="{{ route('admin.roles.edit', $role->id) }}"
                                        class="btn btn-primary btn-sm">
                                        <i class="ti ti-edit icon icon-1"></i>
                                        {{ __('Edit') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">{{ __('No data found') }}</td>
                            </tr>
                        @endforelse
                    </x-slot>
                </x-table.table>
                <x-slot name="footer">
                    <div class="card-footer d-flex align-items-center">
                        <div class="dropdown">
                            <a class="btn dropdown-toggle" data-bs-toggle="dropdown">
                                <span id="page-count" class="me-1">{{ $roles->perPage() }}</span>
                                <span>{{ __('records') }}</span>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" data-per-page="10">10 {{ __('records') }}</a>
                                <a class="dropdown-item" data-per-page="20">20 {{ __('records') }}</a>
                                <a class="dropdown-item" data-per-page="50">50 {{ __('records') }}</a>
                                <a class="dropdown-item" data-per-page="100">100 {{ __('records') }}</a>
                            </div>
                        </div>
                        {{ $roles->withQueryString()->links('pagination::bs5') }}
                    </div>
                </x-slot>
            </x-table.card>
        </div>
    </div>
</x-app-layout>
