<x-app-layout :page="__('Create Transaction')" layout="admin">

    <x-slot name="pretitle">{{ __('Transactions') }}</x-slot>
    <x-slot name="subtitle">{{ __('Create Transaction') }}</x-slot>

    <x-slot name="actions">
        <a href="{{ route('admin.transactions.index') }}" class="btn btn-primary">{{ __('Back') }}</a>
    </x-slot>

    <x-common.alert />

    <div class="row row-cards">
        <div class="col-12">
            <form action="{{ route('admin.transactions.store') }}" method="POST">
                @csrf

                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Transaction Information') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label required">{{ __('Category') }}</label>
                                    <select class="form-select @error('transaction_category_id') is-invalid @enderror"
                                        name="transaction_category_id" id="transaction_category_id" required>
                                        <option value="">{{ __('Select Category') }}</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('transaction_category_id') == $category->id ? 'selected' : '' }}
                                                data-type="{{ $category->type }}">
                                                {{ $category->name }} ({{ ucfirst($category->type) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-common.error name="transaction_category_id" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required">{{ __('Transaction Type') }}</label>
                                    <select class="form-select @error('type') is-invalid @enderror" name="type"
                                        id="transaction_type" required>
                                        <option value="income" {{ old('type') == 'income' ? 'selected' : '' }}>
                                            {{ __('Income') }}</option>
                                        <option value="expense"
                                            {{ old('type', 'expense') == 'expense' ? 'selected' : '' }}>
                                            {{ __('Expense') }}</option>
                                    </select>
                                    <x-common.error name="type" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required">{{ __('Title') }}</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        name="title" value="{{ old('title') }}"
                                        placeholder="{{ __('Enter transaction title') }}" required>
                                    <x-common.error name="title" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Description') }}</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3"
                                        placeholder="{{ __('Enter transaction description') }}">{{ old('description') }}</textarea>
                                    <x-common.error name="description" />
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">{{ __('Amount') }}</label>
                                            <div class="input-group">
                                                <input type="text"
                                                    class="form-control @error('currency') is-invalid @enderror"
                                                    name="currency" value="{{ old('currency', 'USD') }}"
                                                    placeholder="USD" style="max-width: 80px;">
                                                <input type="number" step="0.01" min="0"
                                                    class="form-control @error('amount') is-invalid @enderror"
                                                    name="amount" value="{{ old('amount', 0) }}" placeholder="0.00"
                                                    required>
                                            </div>
                                            <x-common.error name="amount" />
                                            <x-common.error name="currency" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">{{ __('Transaction Date') }}</label>
                                            <input type="date"
                                                class="form-control @error('transaction_date') is-invalid @enderror"
                                                name="transaction_date"
                                                value="{{ old('transaction_date', date('Y-m-d')) }}" required>
                                            <x-common.error name="transaction_date" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('Reference Number') }}</label>
                                            <input type="text"
                                                class="form-control @error('reference_number') is-invalid @enderror"
                                                name="reference_number" value="{{ old('reference_number') }}"
                                                placeholder="{{ __('Leave blank to auto-generate') }}">
                                            <small class="text-muted">{{ __('Leave blank to auto-generate') }}</small>
                                            <x-common.error name="reference_number" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('Payment Method') }}</label>
                                            <input type="text"
                                                class="form-control @error('payment_method') is-invalid @enderror"
                                                name="payment_method" value="{{ old('payment_method') }}"
                                                placeholder="{{ __('e.g., Cash, Bank Transfer, Credit Card') }}">
                                            <x-common.error name="payment_method" />
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Due Date') }}</label>
                                    <input type="datetime-local"
                                        class="form-control @error('due_date') is-invalid @enderror" name="due_date"
                                        value="{{ old('due_date') }}">
                                    <small class="text-muted">{{ __('For future transactions') }}</small>
                                    <x-common.error name="due_date" />
                                </div>
                            </div>
                        </div>

                        <!-- Tax Information -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Tax Information') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('Tax Type') }}</label>
                                            <input type="text"
                                                class="form-control @error('tax_type') is-invalid @enderror"
                                                name="tax_type" value="{{ old('tax_type') }}"
                                                placeholder="{{ __('e.g., VAT, GST') }}">
                                            <x-common.error name="tax_type" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('Tax Rate (%)') }}</label>
                                            <input type="number" step="0.01" min="0" max="100"
                                                class="form-control @error('tax_rate') is-invalid @enderror"
                                                name="tax_rate" value="{{ old('tax_rate', 0) }}" placeholder="0.00">
                                            <x-common.error name="tax_rate" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('Tax Amount') }}</label>
                                            <input type="number" step="0.01" min="0"
                                                class="form-control @error('tax_amount') is-invalid @enderror"
                                                name="tax_amount" value="{{ old('tax_amount', 0) }}"
                                                placeholder="0.00">
                                            <x-common.error name="tax_amount" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        <!-- Status -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Status') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Transaction Status') }}</label>
                                    <select class="form-select @error('status') is-invalid @enderror" name="status">
                                        <option value="completed"
                                            {{ old('status', 'completed') == 'completed' ? 'selected' : '' }}>
                                            {{ __('Completed') }}</option>
                                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>
                                            {{ __('Pending') }}</option>
                                        <option value="cancelled"
                                            {{ old('status') == 'cancelled' ? 'selected' : '' }}>
                                            {{ __('Cancelled') }}</option>
                                        <option value="failed" {{ old('status') == 'failed' ? 'selected' : '' }}>
                                            {{ __('Failed') }}</option>
                                    </select>
                                    <x-common.error name="status" />
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
                                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">
                                        {{ __('Cancel') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-check icon icon-1"></i>
                                        {{ __('Create Transaction') }}
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
                // Sync transaction type with category type
                const categorySelect = document.getElementById('transaction_category_id');
                const typeSelect = document.getElementById('transaction_type');

                if (categorySelect && typeSelect) {
                    categorySelect.addEventListener('change', function() {
                        const selectedOption = this.options[this.selectedIndex];
                        if (selectedOption.value && selectedOption.dataset.type) {
                            typeSelect.value = selectedOption.dataset.type;
                        }
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
