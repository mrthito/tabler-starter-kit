<x-app-layout :page="__('Admins')" layout="admin">

    <x-slot name="pretitle">{{ __('Admins') }}</x-slot>
    <x-slot name="subtitle">{{ __('Manage Admins') }}</x-slot>

    <x-slot name="actions">
        <a href="{{ route('admin.admins.create') }}" class="btn btn-primary">{{ __('Create Admin') }}</a>
    </x-slot>

    <x-common.alert />

    <div class="row row-cards">
        <div class="col-12">
            <x-table.card :title="__('Admins')" :description="__('Manage all admins here')" :export="[
                'pdf' => route('admin.admins.export', 'pdf'),
                'xls' => route('admin.admins.export', 'xls'),
                'csv' => route('admin.admins.export', 'csv'),
            ]" :action="route('admin.admins.destroy', 'bulk')">
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
                        @forelse ($admins as $admin)
                            <tr>
                                <td>
                                    <input class="form-check-input m-0 align-middle table-selectable-check"
                                        name="row[]" type="checkbox" value="{{ $admin->id }}">
                                </td>
                                <td class="sort-name">
                                    {{ $admin->name }}
                                </td>
                                <td class="sort-email">
                                    {{ $admin->email }}
                                </td>
                                <td class="sort-phone">
                                    {{ $admin->phone ?? 'N/A' }}
                                </td>
                                <td class="sort-role">
                                    <span class="badge bg-info-lt">
                                        {{ $admin->role?->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="sort-status">
                                    @if ($admin->status)
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
                                        <a href="{{ route('admin.admins.show', $admin->id) }}"
                                            class="btn btn-secondary btn-sm">
                                            <i class="ti ti-eye icon icon-1"></i>
                                            {{ __('View') }}
                                        </a>
                                        <a href="{{ route('admin.admins.edit', $admin->id) }}"
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
                                <span id="page-count" class="me-1">{{ $admins->perPage() }}</span>
                                <span>{{ __('records') }}</span>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" data-per-page="10">10 {{ __('records') }}</a>
                                <a class="dropdown-item" data-per-page="20">20 {{ __('records') }}</a>
                                <a class="dropdown-item" data-per-page="50">50 {{ __('records') }}</a>
                                <a class="dropdown-item" data-per-page="100">100 {{ __('records') }}</a>
                            </div>
                        </div>
                        {{ $admins->withQueryString()->links('pagination::bs5') }}
                    </div>
                </x-slot>
            </x-table.card>
        </div>
    </div>
</x-app-layout>
