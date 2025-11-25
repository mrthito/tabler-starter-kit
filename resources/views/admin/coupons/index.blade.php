<x-app-layout :page="__('Coupons')" layout="admin">

    <x-slot name="pretitle">{{ __('Coupons') }}</x-slot>
    <x-slot name="subtitle">{{ __('Manage Coupons') }}</x-slot>

    <x-slot name="actions">
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">{{ __('Create Coupon') }}</a>
    </x-slot>

    <div class="row row-cards">
        <div class="col-12">
            <x-table.card :title="__('Coupons')" :description="__('Manage all coupons here')" :action="route('admin.coupons.destroy', 'bulk')">
                <x-table.table>
                    <x-slot name="thead">
                        <tr>
                            <th class="w-1"></th>
                            <th>
                                <button class="table-sort d-flex justify-content-between"
                                    data-sort="sort-code">{{ __('Code') }}</button>
                            </th>
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
                                    data-sort="sort-value">{{ __('Value') }}</button>
                            </th>
                            <th>
                                <button class="table-sort d-flex justify-content-between"
                                    data-sort="sort-usage">{{ __('Usage') }}</button>
                            </th>
                            <th>
                                <button class="table-sort d-flex justify-content-between"
                                    data-sort="sort-validity">{{ __('Validity') }}</button>
                            </th>
                            <th>
                                <button class="table-sort d-flex justify-content-between"
                                    data-sort="sort-status">{{ __('Status') }}</button>
                            </th>
                            <th width="10px"></th>
                        </tr>
                    </x-slot>
                    <x-slot name="tbody">
                        @forelse ($coupons as $coupon)
                            <tr>
                                <td>
                                    <input class="form-check-input m-0 align-middle table-selectable-check"
                                        name="row[]" type="checkbox" value="{{ $coupon->id }}">
                                </td>
                                <td class="sort-code">
                                    <div class="fw-bold">
                                        <code>{{ $coupon->code }}</code>
                                    </div>
                                </td>
                                <td class="sort-name">
                                    <div class="fw-bold">{{ $coupon->name ?? '-' }}</div>
                                    @if ($coupon->description)
                                        <small
                                            class="text-muted">{{ \Illuminate\Support\Str::limit($coupon->description, 50) }}</small>
                                    @endif
                                </td>
                                <td class="sort-type">
                                    <span
                                        class="badge bg-{{ $coupon->type === 'percentage' ? 'info' : 'primary' }}-lt">
                                        {{ ucfirst($coupon->type) }}
                                    </span>
                                </td>
                                <td class="sort-value">
                                    @if ($coupon->type === 'percentage')
                                        <span class="fw-bold">{{ $coupon->value }}%</span>
                                    @else
                                        <span class="fw-bold">USD {{ number_format($coupon->value, 2) }}</span>
                                    @endif
                                </td>
                                <td class="sort-usage">
                                    <div>
                                        <span class="fw-bold">{{ $coupon->used_count }}</span>
                                        @if ($coupon->usage_limit)
                                            <span class="text-muted">/ {{ $coupon->usage_limit }}</span>
                                        @else
                                            <span class="text-muted">/ {{ __('Unlimited') }}</span>
                                        @endif
                                    </div>
                                    @if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit)
                                        <small class="text-danger">{{ __('Limit Reached') }}</small>
                                    @endif
                                </td>
                                <td class="sort-validity">
                                    @if ($coupon->valid_from || $coupon->valid_until)
                                        <div>
                                            @if ($coupon->valid_from)
                                                <small class="text-muted">{{ __('From') }}:
                                                    {{ $coupon->valid_from->format('M d, Y') }}</small>
                                            @endif
                                            @if ($coupon->valid_until)
                                                <div>
                                                    <small class="text-muted">{{ __('Until') }}:
                                                        {{ $coupon->valid_until->format('M d, Y') }}</small>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">{{ __('No expiry') }}</span>
                                    @endif
                                    @if ($coupon->valid_until && $coupon->valid_until->isPast())
                                        <small class="text-danger d-block">{{ __('Expired') }}</small>
                                    @endif
                                </td>
                                <td class="sort-status">
                                    @if ($coupon->status && $coupon->isValid())
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
                                    <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
                                        class="btn btn-primary btn-sm">
                                        <i class="ti ti-edit icon icon-1"></i>
                                        {{ __('Edit') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">{{ __('No data found') }}</td>
                            </tr>
                        @endforelse
                    </x-slot>
                </x-table.table>
                <x-slot name="footer">
                    <div class="card-footer d-flex align-items-center">
                        <div class="dropdown">
                            <a class="btn dropdown-toggle" data-bs-toggle="dropdown">
                                <span id="page-count" class="me-1">{{ $coupons->perPage() }}</span>
                                <span>{{ __('records') }}</span>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" data-per-page="10">10 {{ __('records') }}</a>
                                <a class="dropdown-item" data-per-page="20">20 {{ __('records') }}</a>
                                <a class="dropdown-item" data-per-page="50">50 {{ __('records') }}</a>
                                <a class="dropdown-item" data-per-page="100">100 {{ __('records') }}</a>
                            </div>
                        </div>
                        {{ $coupons->withQueryString()->links('pagination::bs5') }}
                    </div>
                </x-slot>
            </x-table.card>
        </div>
    </div>
</x-app-layout>
