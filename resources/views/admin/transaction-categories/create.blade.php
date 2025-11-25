<x-app-layout :page="__('Create Transaction Category')" layout="admin">

    <x-slot name="pretitle">{{ __('Transaction Categories') }}</x-slot>
    <x-slot name="subtitle">{{ __('Create Transaction Category') }}</x-slot>

    <x-slot name="actions">
        <a href="{{ route('admin.transaction-categories.index') }}" class="btn btn-primary">{{ __('Back') }}</a>
    </x-slot>

    <x-common.alert />

    <div class="row row-cards">
        <div class="col-12">
            <form action="{{ route('admin.transaction-categories.store') }}" method="POST">
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
                                    <label class="form-label required">{{ __('Category Name') }}</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name') }}"
                                        placeholder="{{ __('Enter category name') }}" required>
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
                                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="4"
                                        placeholder="{{ __('Enter category description (optional)') }}">{{ old('description') }}</textarea>
                                    <x-common.error name="description" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        <!-- Category Settings -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Category Settings') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label required">{{ __('Type') }}</label>
                                    <select class="form-select @error('type') is-invalid @enderror" name="type"
                                        required>
                                        <option value="income" {{ old('type') == 'income' ? 'selected' : '' }}>
                                            {{ __('Income') }}</option>
                                        <option value="expense"
                                            {{ old('type', 'expense') == 'expense' ? 'selected' : '' }}>
                                            {{ __('Expense') }}</option>
                                    </select>
                                    <x-common.error name="type" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Color') }}</label>
                                    <input type="color"
                                        class="form-control form-control-color @error('color') is-invalid @enderror"
                                        name="color" value="{{ old('color', '#066fd1') }}">
                                    <x-common.error name="color" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Icon') }}</label>
                                    <input type="text" class="form-control @error('icon') is-invalid @enderror"
                                        name="icon" value="{{ old('icon') }}"
                                        placeholder="{{ __('e.g., ti ti-wallet') }}">
                                    <small class="text-muted">{{ __('Tabler icon class name') }}</small>
                                    <x-common.error name="icon" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Sort Order') }}</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror"
                                        name="sort_order" value="{{ old('sort_order', 0) }}" placeholder="0">
                                    <small class="text-muted">{{ __('Lower numbers appear first') }}</small>
                                    <x-common.error name="sort_order" />
                                </div>

                                <input type="hidden" name="status" value="0">
                                <div class="mb-3">
                                    <label class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="status" value="1"
                                            {{ old('status', true) ? 'checked' : '' }}>
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
                                    <a href="{{ route('admin.transaction-categories.index') }}"
                                        class="btn btn-secondary">
                                        {{ __('Cancel') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-check icon icon-1"></i>
                                        {{ __('Create Category') }}
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
            });
        </script>
    @endpush
</x-app-layout>
