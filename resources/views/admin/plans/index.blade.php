<x-app-layout :page="__('Plans')" layout="admin">

    <x-slot name="pretitle">{{ __('Plans') }}</x-slot>
    <x-slot name="subtitle">{{ __('Manage Plans') }}</x-slot>

    <x-slot name="actions">
        <a href="{{ route('admin.plans.create') }}" class="btn btn-primary">{{ __('Create Plan') }}</a>
    </x-slot>

    <div class="row row-cards">
        <div class="col-12">
            <x-table.card :title="__('Plans')" :description="__('Manage all plans here')" :action="route('admin.plans.destroy', 'bulk')">
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
                                    data-sort="sort-price">{{ __('Pricing') }}</button>
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
                        @forelse ($plans as $plan)
                            <tr>
                                <td>
                                    <input class="form-check-input m-0 align-middle table-selectable-check"
                                        name="row[]" type="checkbox" value="{{ $plan->id }}">
                                </td>
                                <td class="sort-name">
                                    <div class="fw-bold">{{ $plan->name }}</div>
                                    @if ($plan->description)
                                        <small
                                            class="text-muted">{{ \Illuminate\Support\Str::limit($plan->description, 50) }}</small>
                                    @endif
                                </td>
                                <td class="sort-price">
                                    <div>
                                        @if ($plan->monthly_price > 0)
                                            <span class="fw-bold">{{ $plan->currency }}
                                                {{ number_format($plan->monthly_price, 2) }}</span>
                                            <small class="text-muted">/{{ __('month') }}</small>
                                        @else
                                            <span class="text-muted">{{ __('Free') }}</span>
                                        @endif
                                    </div>
                                    @if ($plan->yearly_price > 0)
                                        <div>
                                            <small class="text-muted">
                                                {{ $plan->currency }}
                                                {{ number_format($plan->yearly_price, 2) }}/{{ __('year') }}
                                                @if ($plan->yearly_discount_percentage > 0)
                                                    <span
                                                        class="badge bg-success-lt">{{ $plan->yearly_discount_percentage }}%
                                                        {{ __('off') }}</span>
                                                @endif
                                            </small>
                                        </div>
                                    @endif
                                </td>
                                <td class="sort-type">
                                    <span
                                        class="badge bg-{{ $plan->plan_type === 'free' ? 'secondary' : ($plan->plan_type === 'enterprise' ? 'primary' : 'info') }}-lt">
                                        {{ ucfirst($plan->plan_type ?? 'paid') }}
                                    </span>
                                    @if ($plan->is_popular)
                                        <span class="badge bg-warning-lt ms-1">{{ __('Popular') }}</span>
                                    @endif
                                </td>
                                <td class="sort-status">
                                    @if ($plan->status)
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
                                    <span class="text-muted">{{ $plan->sort_order }}</span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.plans.edit', $plan->id) }}"
                                        class="btn btn-primary btn-sm">
                                        <i class="ti ti-edit icon icon-1"></i>
                                        {{ __('Edit') }}
                                    </a>
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
                                <span id="page-count" class="me-1">{{ $plans->perPage() }}</span>
                                <span>{{ __('records') }}</span>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" data-per-page="10">10 {{ __('records') }}</a>
                                <a class="dropdown-item" data-per-page="20">20 {{ __('records') }}</a>
                                <a class="dropdown-item" data-per-page="50">50 {{ __('records') }}</a>
                                <a class="dropdown-item" data-per-page="100">100 {{ __('records') }}</a>
                            </div>
                        </div>
                        {{ $plans->withQueryString()->links('pagination::bs5') }}
                    </div>
                </x-slot>
            </x-table.card>
        </div>
    </div>
</x-app-layout>
