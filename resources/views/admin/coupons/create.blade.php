<x-app-layout :page="__('Create Coupon')" layout="admin">

    <x-slot name="pretitle">{{ __('Coupons') }}</x-slot>
    <x-slot name="subtitle">{{ __('Create Coupon') }}</x-slot>

    <x-slot name="actions">
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-primary">{{ __('Back') }}</a>
    </x-slot>

    <x-common.alert />

    <div class="row row-cards">
        <div class="col-12">
            <form action="{{ route('admin.coupons.store') }}" method="POST">
                @csrf

                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Basic Information') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label required">{{ __('Coupon Code') }}</label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror"
                                        name="code" value="{{ old('code') }}"
                                        placeholder="{{ __('Leave blank to auto-generate') }}"
                                        style="text-transform: uppercase;">
                                    <small
                                        class="text-muted">{{ __('Leave blank to auto-generate a unique code') }}</small>
                                    <x-common.error name="code" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Coupon Name') }}</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name') }}"
                                        placeholder="{{ __('Enter coupon name') }}">
                                    <x-common.error name="name" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Description') }}</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3"
                                        placeholder="{{ __('Enter coupon description') }}">{{ old('description') }}</textarea>
                                    <x-common.error name="description" />
                                </div>
                            </div>
                        </div>

                        <!-- Discount Settings -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Discount Settings') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label required">{{ __('Discount Type') }}</label>
                                    <select class="form-select @error('type') is-invalid @enderror" name="type"
                                        id="discount_type" required>
                                        <option value="percentage"
                                            {{ old('type', 'percentage') == 'percentage' ? 'selected' : '' }}>
                                            {{ __('Percentage') }}</option>
                                        <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>
                                            {{ __('Fixed Amount') }}</option>
                                    </select>
                                    <x-common.error name="type" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required"
                                        id="value-label">{{ __('Discount Value (%)') }}</label>
                                    <input type="number" step="0.01" min="0" max="100"
                                        class="form-control @error('value') is-invalid @enderror" name="value"
                                        value="{{ old('value', 0) }}" placeholder="0.00" required>
                                    <small class="text-muted"
                                        id="value-help">{{ __('Enter discount percentage (0-100)') }}</small>
                                    <x-common.error name="value" />
                                </div>

                                <div class="mb-3" id="maximum_discount_container">
                                    <label class="form-label">{{ __('Maximum Discount') }}</label>
                                    <input type="number" step="0.01" min="0"
                                        class="form-control @error('maximum_discount') is-invalid @enderror"
                                        name="maximum_discount" value="{{ old('maximum_discount') }}"
                                        placeholder="0.00">
                                    <small
                                        class="text-muted">{{ __('Maximum discount amount for percentage type') }}</small>
                                    <x-common.error name="maximum_discount" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Minimum Amount') }}</label>
                                    <input type="number" step="0.01" min="0"
                                        class="form-control @error('minimum_amount') is-invalid @enderror"
                                        name="minimum_amount" value="{{ old('minimum_amount') }}" placeholder="0.00">
                                    <small
                                        class="text-muted">{{ __('Minimum order amount to apply this coupon') }}</small>
                                    <x-common.error name="minimum_amount" />
                                </div>
                            </div>
                        </div>

                        <!-- Usage Limits -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Usage Limits') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Total Usage Limit') }}</label>
                                    <input type="number" min="1"
                                        class="form-control @error('usage_limit') is-invalid @enderror"
                                        name="usage_limit" value="{{ old('usage_limit') }}"
                                        placeholder="{{ __('Leave blank for unlimited') }}">
                                    <small
                                        class="text-muted">{{ __('Maximum number of times this coupon can be used') }}</small>
                                    <x-common.error name="usage_limit" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Usage Limit Per User') }}</label>
                                    <input type="number" min="1"
                                        class="form-control @error('usage_limit_per_user') is-invalid @enderror"
                                        name="usage_limit_per_user" value="{{ old('usage_limit_per_user') }}"
                                        placeholder="{{ __('Leave blank for unlimited') }}">
                                    <small
                                        class="text-muted">{{ __('Maximum number of times a single user can use this coupon') }}</small>
                                    <x-common.error name="usage_limit_per_user" />
                                </div>
                            </div>
                        </div>

                        <!-- Validity -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Validity Period') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('Valid From') }}</label>
                                            <input type="datetime-local"
                                                class="form-control @error('valid_from') is-invalid @enderror"
                                                name="valid_from" value="{{ old('valid_from') }}">
                                            <x-common.error name="valid_from" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('Valid Until') }}</label>
                                            <input type="datetime-local"
                                                class="form-control @error('valid_until') is-invalid @enderror"
                                                name="valid_until" value="{{ old('valid_until') }}">
                                            <x-common.error name="valid_until" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Applicability -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Applicability') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label required">{{ __('Applicable To') }}</label>
                                    <select class="form-select @error('applicable_to') is-invalid @enderror"
                                        name="applicable_to" id="applicable_to" required>
                                        <option value="all"
                                            {{ old('applicable_to', 'all') == 'all' ? 'selected' : '' }}>
                                            {{ __('All Plans') }}</option>
                                        <option value="plans"
                                            {{ old('applicable_to') == 'plans' ? 'selected' : '' }}>
                                            {{ __('All Plans (explicit)') }}</option>
                                        <option value="specific_plans"
                                            {{ old('applicable_to') == 'specific_plans' ? 'selected' : '' }}>
                                            {{ __('Specific Plans') }}</option>
                                    </select>
                                    <x-common.error name="applicable_to" />
                                </div>

                                <div class="mb-3" id="plan_ids_container" style="display: none;">
                                    <label class="form-label">{{ __('Select Plans') }}</label>
                                    <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                        @foreach ($plans as $plan)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="plan_ids[]"
                                                    value="{{ $plan->id }}" id="plan_{{ $plan->id }}"
                                                    {{ in_array($plan->id, old('plan_ids', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="plan_{{ $plan->id }}">
                                                    {{ $plan->name }}
                                                    <small class="text-muted">({{ $plan->plan_type }})</small>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <x-common.error name="plan_ids" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        <!-- Settings -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Settings') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Sort Order') }}</label>
                                    <input type="number"
                                        class="form-control @error('sort_order') is-invalid @enderror"
                                        name="sort_order" value="{{ old('sort_order', 0) }}" placeholder="0">
                                    <small class="text-muted">{{ __('Lower numbers appear first') }}</small>
                                    <x-common.error name="sort_order" />
                                </div>

                                <input type="hidden" name="status" value="0">
                                <div class="mb-3">
                                    <label class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="status"
                                            value="1" {{ old('status', true) ? 'checked' : '' }}>
                                        <span class="form-check-label">{{ __('Active') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-footer">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
                                        {{ __('Cancel') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-check icon icon-1"></i>
                                        {{ __('Create Coupon') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Auto-uppercase coupon code
                const codeInput = document.querySelector('input[name="code"]');
                if (codeInput) {
                    codeInput.addEventListener('input', function() {
                        this.value = this.value.toUpperCase();
                    });
                }

                // Discount type reactive behavior
                const discountTypeSelect = document.getElementById('discount_type');
                const valueLabel = document.getElementById('value-label');
                const valueHelp = document.getElementById('value-help');
                const valueInput = document.querySelector('input[name="value"]');
                const maxDiscountContainer = document.getElementById('maximum_discount_container');

                function updateDiscountFields() {
                    if (discountTypeSelect && valueLabel && valueHelp && valueInput) {
                        if (discountTypeSelect.value === 'percentage') {
                            valueLabel.textContent = '{{ __('Discount Value (%)') }}';
                            valueHelp.textContent = '{{ __('Enter discount percentage (0-100)') }}';
                            valueInput.setAttribute('max', '100');
                            if (maxDiscountContainer) {
                                maxDiscountContainer.style.display = 'block';
                            }
                        } else {
                            valueLabel.textContent = '{{ __('Discount Value (Amount)') }}';
                            valueHelp.textContent = '{{ __('Enter fixed discount amount') }}';
                            valueInput.removeAttribute('max');
                            if (maxDiscountContainer) {
                                maxDiscountContainer.style.display = 'none';
                            }
                        }
                    }
                }

                if (discountTypeSelect) {
                    discountTypeSelect.addEventListener('change', updateDiscountFields);
                    updateDiscountFields();
                }

                // Applicable to reactive behavior
                const applicableToSelect = document.getElementById('applicable_to');
                const planIdsContainer = document.getElementById('plan_ids_container');

                function togglePlanSelection() {
                    if (applicableToSelect && planIdsContainer) {
                        if (applicableToSelect.value === 'specific_plans') {
                            planIdsContainer.style.display = 'block';
                        } else {
                            planIdsContainer.style.display = 'none';
                        }
                    }
                }

                if (applicableToSelect) {
                    applicableToSelect.addEventListener('change', togglePlanSelection);
                    togglePlanSelection();
                }
            });
        </script>
    @endpush
</x-app-layout>
