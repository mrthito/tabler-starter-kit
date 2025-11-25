<x-app-layout :page="__('Create Plan')" layout="admin">

    <x-slot name="pretitle">{{ __('Plans') }}</x-slot>
    <x-slot name="subtitle">{{ __('Create Plan') }}</x-slot>

    <x-slot name="actions">
        <a href="{{ route('admin.plans.index') }}" class="btn btn-primary">{{ __('Back') }}</a>
    </x-slot>

    <x-common.alert />

    <div class="row row-cards">
        <div class="col-12">
            <form action="{{ route('admin.plans.store') }}" method="POST">
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
                                    <label class="form-label required">{{ __('Plan Name') }}</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name') }}"
                                        placeholder="{{ __('Enter plan name') }}" required>
                                    <x-common.error name="name" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Slug') }}</label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                        name="slug" value="{{ old('slug') }}"
                                        placeholder="{{ __('Leave blank to auto-generate from name') }}">
                                    <small
                                        class="text-muted">{{ __('Leave blank to auto-generate from name') }}</small>
                                    <x-common.error name="slug" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Description') }}</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3"
                                        placeholder="{{ __('Enter plan description') }}">{{ old('description') }}</textarea>
                                    <x-common.error name="description" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Detail') }}</label>
                                    <textarea class="form-control @error('detail') is-invalid @enderror" name="detail" rows="5"
                                        placeholder="{{ __('Enter detailed description') }}">{{ old('detail') }}</textarea>
                                    <x-common.error name="detail" />
                                </div>
                            </div>
                        </div>

                        <!-- Pricing -->
                        <div class="card mt-3" id="pricing-card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Pricing') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('Monthly Price') }}</label>
                                            <div class="input-group">
                                                <input type="text"
                                                    class="form-control @error('currency') is-invalid @enderror"
                                                    name="currency" value="{{ old('currency', 'USD') }}"
                                                    placeholder="USD" style="max-width: 80px;">
                                                <input type="number" step="0.01" min="0"
                                                    class="form-control @error('monthly_price') is-invalid @enderror"
                                                    name="monthly_price" value="{{ old('monthly_price', 0) }}"
                                                    placeholder="0.00">
                                            </div>
                                            <x-common.error name="monthly_price" />
                                            <x-common.error name="currency" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('Yearly Price') }}</label>
                                            <input type="number" step="0.01" min="0"
                                                class="form-control @error('yearly_price') is-invalid @enderror"
                                                name="yearly_price" value="{{ old('yearly_price', 0) }}"
                                                placeholder="0.00">
                                            <x-common.error name="yearly_price" />
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Yearly Discount (%)') }}</label>
                                    <input type="number" min="0" max="100"
                                        class="form-control @error('yearly_discount_percentage') is-invalid @enderror"
                                        name="yearly_discount_percentage"
                                        value="{{ old('yearly_discount_percentage', 0) }}" placeholder="0">
                                    <small
                                        class="text-muted">{{ __('Discount percentage for yearly billing') }}</small>
                                    <x-common.error name="yearly_discount_percentage" />
                                </div>
                            </div>
                        </div>

                        <!-- Trial -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Trial Settings') }}</h3>
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="has_trial" value="0">
                                <div class="mb-3">
                                    <label class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="has_trial"
                                            value="1" id="has_trial" {{ old('has_trial') ? 'checked' : '' }}>
                                        <span class="form-check-label">{{ __('Enable Trial') }}</span>
                                    </label>
                                </div>
                                <div class="mb-3" id="trial-days-container">
                                    <label class="form-label">{{ __('Trial Days') }}</label>
                                    <input type="number" min="0"
                                        class="form-control @error('trial_days') is-invalid @enderror"
                                        name="trial_days" value="{{ old('trial_days', 0) }}" placeholder="0">
                                    <x-common.error name="trial_days" />
                                </div>
                            </div>
                        </div>

                        <!-- Features -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Features') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Plan Features') }}</label>
                                    <div id="features-container">
                                        @if (old('features') && is_array(old('features')))
                                            @foreach (old('features') as $index => $feature)
                                                <div class="input-group mb-2 feature-item">
                                                    <input type="text"
                                                        class="form-control @error('features.' . $index) is-invalid @enderror"
                                                        name="features[]" value="{{ $feature }}"
                                                        placeholder="{{ __('Enter feature') }}">
                                                    <button type="button"
                                                        class="btn btn-outline-danger remove-feature"
                                                        {{ $loop->first && count(old('features')) == 1 ? 'disabled' : '' }}>
                                                        <i class="ti ti-minus"></i>
                                                    </button>
                                                    @error('features.' . $index)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="input-group mb-2 feature-item">
                                                <input type="text" class="form-control" name="features[]"
                                                    placeholder="{{ __('Enter feature') }}">
                                                <button type="button" class="btn btn-outline-danger remove-feature"
                                                    disabled>
                                                    <i class="ti ti-minus"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm mt-2"
                                        id="add-feature">
                                        <i class="ti ti-plus"></i> {{ __('Add Feature') }}
                                    </button>
                                    <x-common.error name="features" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        <!-- Plan Settings -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Plan Settings') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Plan Type') }}</label>
                                    <select class="form-select @error('plan_type') is-invalid @enderror"
                                        name="plan_type" id="plan_type">
                                        <option value="paid"
                                            {{ old('plan_type', 'paid') == 'paid' ? 'selected' : '' }}>
                                            {{ __('Paid') }}</option>
                                        <option value="free" {{ old('plan_type') == 'free' ? 'selected' : '' }}>
                                            {{ __('Free') }}</option>
                                        <option value="enterprise"
                                            {{ old('plan_type') == 'enterprise' ? 'selected' : '' }}>
                                            {{ __('Enterprise') }}</option>
                                        <option value="custom" {{ old('plan_type') == 'custom' ? 'selected' : '' }}>
                                            {{ __('Custom') }}</option>
                                    </select>
                                    <x-common.error name="plan_type" />
                                </div>

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

                                <input type="hidden" name="is_popular" value="0">
                                <div class="mb-3">
                                    <label class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_popular"
                                            value="1" {{ old('is_popular') ? 'checked' : '' }}>
                                        <span class="form-check-label">{{ __('Popular') }}</span>
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
                                    <a href="{{ route('admin.plans.index') }}" class="btn btn-secondary">
                                        {{ __('Cancel') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-check icon icon-1"></i>
                                        {{ __('Create Plan') }}
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
                // Auto-generate slug from name
                const nameInput = document.querySelector('input[name="name"]');
                const slugInput = document.querySelector('input[name="slug"]');
                let slugManuallyEdited = false;

                if (nameInput && slugInput) {
                    nameInput.addEventListener('input', function() {
                        if (!slugManuallyEdited && !slugInput.value) {
                            slugInput.value = this.value.toLowerCase()
                                .replace(/[^a-z0-9]+/g, '-')
                                .replace(/^-+|-+$/g, '');
                        }
                    });

                    slugInput.addEventListener('input', function() {
                        slugManuallyEdited = true;
                    });
                }

                // Features repeater
                const featuresContainer = document.getElementById('features-container');
                const addFeatureBtn = document.getElementById('add-feature');

                if (addFeatureBtn && featuresContainer) {
                    addFeatureBtn.addEventListener('click', function() {
                        const featureItem = document.createElement('div');
                        featureItem.className = 'input-group mb-2 feature-item';
                        featureItem.innerHTML = `
                            <input type="text" class="form-control" name="features[]"
                                placeholder="{{ __('Enter feature') }}">
                            <button type="button" class="btn btn-outline-danger remove-feature">
                                <i class="ti ti-minus"></i>
                            </button>
                        `;
                        featuresContainer.appendChild(featureItem);
                        updateRemoveButtons();
                    });

                    featuresContainer.addEventListener('click', function(e) {
                        if (e.target.closest('.remove-feature')) {
                            const featureItem = e.target.closest('.feature-item');
                            if (featureItem) {
                                featureItem.remove();
                                updateRemoveButtons();
                            }
                        }
                    });

                    function updateRemoveButtons() {
                        const featureItems = featuresContainer.querySelectorAll('.feature-item');
                        featureItems.forEach((item, index) => {
                            const removeBtn = item.querySelector('.remove-feature');
                            if (removeBtn) {
                                removeBtn.disabled = featureItems.length === 1;
                            }
                        });
                    }

                    // Initialize on page load
                    updateRemoveButtons();
                }

                // Plan type reactive behavior
                const planTypeSelect = document.getElementById('plan_type');
                const pricingCard = document.getElementById('pricing-card');

                function togglePricingCard() {
                    if (planTypeSelect && pricingCard) {
                        const selectedType = planTypeSelect.value;
                        if (selectedType === 'free') {
                            pricingCard.style.display = 'none';
                        } else {
                            pricingCard.style.display = 'block';
                        }
                    }
                }

                if (planTypeSelect) {
                    planTypeSelect.addEventListener('change', togglePricingCard);
                    // Initialize on page load
                    togglePricingCard();
                }

                // Trial switch reactive behavior
                const hasTrialCheckbox = document.getElementById('has_trial');
                const trialDaysContainer = document.getElementById('trial-days-container');

                function toggleTrialDays() {
                    if (hasTrialCheckbox && trialDaysContainer) {
                        if (hasTrialCheckbox.checked) {
                            trialDaysContainer.style.display = 'block';
                        } else {
                            trialDaysContainer.style.display = 'none';
                        }
                    }
                }

                if (hasTrialCheckbox) {
                    hasTrialCheckbox.addEventListener('change', toggleTrialDays);
                    // Initialize on page load
                    toggleTrialDays();
                }
            });
        </script>
    @endpush
</x-app-layout>
