<x-app-layout :page="__('Transaction Categories')" layout="admin">

    <x-slot name="pretitle">{{ __('Transaction Categories') }}</x-slot>
    <x-slot name="subtitle">{{ __('Manage Transaction Categories') }}</x-slot>

    <x-slot name="actions">
        <a href="{{ route('admin.transaction-categories.create') }}"
            class="btn btn-primary">{{ __('Create Category') }}</a>
    </x-slot>

    <div class="row row-cards">
        <div class="col-12">
            <x-table.card :title="__('Transaction Categories')" :description="__('Manage all transaction categories here')" :action="route('admin.transaction-categories.destroy', 'bulk')">
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
                                    data-sort="sort-type">{{ __('Type') }}</button>
                            </th>
                            <th>
                                <button class="table-sort d-flex justify-content-between"
                                    data-sort="sort-status">{{ __('Status') }}</button>
                            </th>
                            <th>
                                <button class="table-sort d-flex justify-content-between"
                                    data-sort="sort-order">{{ __('Order') }}</button>
                            </th>
                            <th width="10px"></th>
                        </tr>
                    </x-slot>
                    <x-slot name="tbody">
                        @forelse ($transactionCategories as $category)
                            <tr>
                                <td>
                                    <input class="form-check-input m-0 align-middle table-selectable-check"
                                        name="row[]" type="checkbox" value="{{ $category->id }}">
                                </td>
                                <td class="sort-name">
                                    <div class="d-flex align-items-center">
                                        @if ($category->color)
                                            <span class="badge me-2"
                                                style="background-color: {{ $category->color }}; width: 20px; height: 20px; border-radius: 50%;"></span>
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $category->name }}</div>
                                            @if ($category->description)
                                                <small
                                                    class="text-muted">{{ \Illuminate\Support\Str::limit($category->description, 50) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="sort-type">
                                    @if ($category->type === 'income')
                                        <span class="badge bg-success-lt">{{ __('Income') }}</span>
                                    @else
                                        <span class="badge bg-danger-lt">{{ __('Expense') }}</span>
                                    @endif
                                </td>
                                <td class="sort-status">
                                    @if ($category->status)
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
                                <td class="sort-order">
                                    <span class="text-muted">{{ $category->sort_order }}</span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.transaction-categories.edit', $category->id) }}"
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
                                <span id="page-count" class="me-1">{{ $transactionCategories->perPage() }}</span>
                                <span>{{ __('records') }}</span>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" data-per-page="10">10 {{ __('records') }}</a>
                                <a class="dropdown-item" data-per-page="20">20 {{ __('records') }}</a>
                                <a class="dropdown-item" data-per-page="50">50 {{ __('records') }}</a>
                                <a class="dropdown-item" data-per-page="100">100 {{ __('records') }}</a>
                            </div>
                        </div>
                        {{ $transactionCategories->withQueryString()->links('pagination::bs5') }}
                    </div>
                </x-slot>
            </x-table.card>
        </div>
    </div>
</x-app-layout>
