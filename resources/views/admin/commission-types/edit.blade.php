<x-app-layout :page="__('Commission Settings')" layout="admin">

    <x-slot name="pretitle">{{ __('Settings') }}</x-slot>
    <x-slot name="subtitle">{{ __('Commission Settings') }}</x-slot>

    <x-common.alert />

    <div class="row row-cards">
        <div class="col-12">
            <form action="{{ route('admin.commission-types.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Commission Settings -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Commission Settings') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label required">{{ __('Commission Type') }}</label>
                                    <select class="form-select @error('type') is-invalid @enderror" name="type"
                                        id="commission_type" required>
                                        <option value="subscription_only"
                                            {{ old('type', $commissionType->type) == 'subscription_only' ? 'selected' : '' }}>
                                            {{ __('Subscription Only') }}</option>
                                        <option value="order_only"
                                            {{ old('type', $commissionType->type) == 'order_only' ? 'selected' : '' }}>
                                            {{ __('Order Commission Only') }}</option>
                                        <option value="both"
                                            {{ old('type', $commissionType->type) == 'both' ? 'selected' : '' }}>
                                            {{ __('Both') }}</option>
                                    </select>
                                    <x-common.error name="type" />

                                    <!-- Type Explanations -->
                                    <div class="row mt-3">
                                        <div class="col-md-4">
                                            <div class="card border-info" style="border-width: 2px;">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <span class="badge bg-info-lt me-2">{{ __('Type') }}</span>
                                                        <h5 class="card-title mb-0">{{ __('Subscription Only') }}</h5>
                                                    </div>
                                                    <p class="card-text text-muted mb-0">
                                                        {{ __('Commission will only be calculated and paid for subscription-based transactions. No commission will be applied to one-time orders or purchases.') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card border-primary" style="border-width: 2px;">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <span class="badge bg-primary-lt me-2">{{ __('Type') }}</span>
                                                        <h5 class="card-title mb-0">{{ __('Order Only') }}</h5>
                                                    </div>
                                                    <p class="card-text text-muted mb-0">
                                                        {{ __('Commission will only be calculated and paid for one-time orders or purchases. No commission will be applied to subscription transactions.') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card border-success" style="border-width: 2px;">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <span class="badge bg-success-lt me-2">{{ __('Type') }}</span>
                                                        <h5 class="card-title mb-0">{{ __('Both') }}</h5>
                                                    </div>
                                                    <p class="card-text text-muted mb-0">
                                                        {{ __('Commission will be calculated and paid for both subscription-based transactions and one-time orders. You can set a percentage for order commissions separately.') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3" id="order_commission_container">
                                    <label class="form-label">{{ __('Order Commission Percentage') }}</label>
                                    <input type="number" step="0.01" min="0" max="100"
                                        class="form-control @error('order_commission_percentage') is-invalid @enderror"
                                        name="order_commission_percentage"
                                        value="{{ old('order_commission_percentage', $commissionType->order_commission_percentage) }}"
                                        placeholder="0.00">
                                    <small
                                        class="text-muted">{{ __('Enter percentage for order commission (0-100)') }}</small>
                                    <x-common.error name="order_commission_percentage" />
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
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                        {{ __('Cancel') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-check icon icon-1"></i>
                                        {{ __('Update Settings') }}
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
                // Commission type reactive behavior
                const commissionTypeSelect = document.getElementById('commission_type');
                const orderCommissionContainer = document.getElementById('order_commission_container');

                function toggleOrderCommission() {
                    if (commissionTypeSelect && orderCommissionContainer) {
                        const selectedType = commissionTypeSelect.value;
                        if (selectedType === 'subscription_only') {
                            orderCommissionContainer.style.display = 'none';
                        } else {
                            orderCommissionContainer.style.display = 'block';
                        }
                    }
                }

                if (commissionTypeSelect) {
                    commissionTypeSelect.addEventListener('change', toggleOrderCommission);
                    // Initialize on page load
                    toggleOrderCommission();
                }
            });
        </script>
    @endpush
</x-app-layout>
