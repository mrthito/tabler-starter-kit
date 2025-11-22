<x-app-layout :page="__('Users')" layout="admin">

    <x-slot name="pretitle">{{ __('Users') }}</x-slot>
    <x-slot name="subtitle">{{ __('Manage Users') }}</x-slot>

    <x-slot name="actions">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">{{ __('Create User') }}</a>
    </x-slot>

    <x-common.alert />

    <div class="row row-cards">
        <div class="col-12">
            <x-table.card :title="__('Users')" :description="__('Manage all users here')" :export="[
                'pdf' => route('admin.users.export', 'pdf'),
                'xls' => route('admin.users.export', 'xls'),
                'csv' => route('admin.users.export', 'csv'),
            ]" :action="route('admin.users.destroy', 'bulk')">
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
                                    data-sort="sort-email">{{ __('Email') }}</button>
                            </th>
                            <th>
                                <button class="table-sort d-flex justify-content-between"
                                    data-sort="sort-phone">{{ __('Phone') }}</button>
                            </th>
                            <th>
                                <button class="table-sort d-flex justify-content-between"
                                    data-sort="sort-role">{{ __('Role') }}</button>
                            </th>
                            <th>
                                <button class="table-sort d-flex justify-content-between"
                                    data-sort="sort-status">{{ __('Status') }}</button>
                            </th>
                            <th width="10px"> </th>
                        </tr>
                    </x-slot>
                    <x-slot name="tbody">
                        @forelse ($users as $user)
                            <tr>
                                <td>
                                    <input class="form-check-input m-0 align-middle table-selectable-check"
                                        name="row[]" type="checkbox" value="{{ $user->id }}">
                                </td>
                                <td class="sort-name">
                                    {{ $user->name }}
                                </td>
                                <td class="sort-email">
                                    {{ $user->email }}
                                </td>
                                <td class="sort-phone">
                                    {{ $user->phone ?? 'N/A' }}
                                </td>
                                <td class="sort-role">
                                    <span class="badge bg-warning-lt">
                                        {{ $user->role?->display_name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="sort-status">
                                    @if ($user->banned_at)
                                        <span class="badge bg-danger-lt">
                                            <i class="ti ti-ban icon icon-1"></i>
                                            {{ __('Banned') }}
                                        </span>
                                    @elseif ($user->status)
                                        <span class="badge bg-success-lt">
                                            <i class="ti ti-check icon icon-1"></i>
                                            {{ __('Active') }}
                                        </span>
                                    @else
                                        <span class="badge bg-warning-lt">
                                            <i class="ti ti-x icon icon-1"></i>
                                            {{ __('Inactive') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-list flex-nowrap">
                                        <a href="{{ route('admin.users.show', $user->id) }}"
                                            class="btn btn-secondary btn-sm">
                                            <i class="ti ti-eye icon icon-1"></i>
                                            {{ __('View') }}
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                            class="btn btn-primary btn-sm">
                                            <i class="ti ti-edit icon icon-1"></i>
                                            {{ __('Edit') }}
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">{{ __('No data found') }}</td>
                            </tr>
                        @endforelse
                    </x-slot>
                </x-table.table>
                <x-slot name="footer">
                    <div class="card-footer d-flex align-items-center">
                        <div class="dropdown">
                            <a class="btn dropdown-toggle" data-bs-toggle="dropdown">
                                <span id="page-count" class="me-1">{{ $users->perPage() }}</span>
                                <span>{{ __('records') }}</span>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" data-per-page="10">10 {{ __('records') }}</a>
                                <a class="dropdown-item" data-per-page="20">20 {{ __('records') }}</a>
                                <a class="dropdown-item" data-per-page="50">50 {{ __('records') }}</a>
                                <a class="dropdown-item" data-per-page="100">100 {{ __('records') }}</a>
                            </div>
                        </div>
                        {{ $users->withQueryString()->links('pagination::bs5') }}
                    </div>
                </x-slot>
            </x-table.card>
        </div>
    </div>
</x-app-layout>
