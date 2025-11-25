<x-app-layout :page="__('Edit Post Category')" layout="admin">

    <x-slot name="pretitle">{{ __('Post Categories') }}</x-slot>
    <x-slot name="subtitle">{{ __('Edit Post Category') }}</x-slot>

    <x-slot name="actions">
        <a href="{{ route('admin.post-categories.index') }}" class="btn btn-primary">{{ __('Back') }}</a>
    </x-slot>

    <x-common.alert />

    <div class="row row-cards">
        <div class="col-12">
            <form action="{{ route('admin.post-categories.update', $postCategory) }}" method="POST">
                @csrf
                @method('PUT')

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
                                        name="name" value="{{ old('name', $postCategory->name) }}"
                                        placeholder="{{ __('Enter category name') }}" required>
                                    <x-common.error name="name" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Slug') }}</label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                        name="slug" value="{{ old('slug', $postCategory->slug) }}"
                                        placeholder="{{ __('Leave blank to auto-generate from name') }}">
                                    <small
                                        class="text-muted">{{ __('Leave blank to auto-generate from name') }}</small>
                                    <x-common.error name="slug" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Description') }}</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="4"
                                        placeholder="{{ __('Enter category description (optional)') }}">{{ old('description', $postCategory->description) }}</textarea>
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
                                    <label class="form-label">{{ __('Parent Category') }}</label>
                                    <select class="form-select @error('parent_id') is-invalid @enderror"
                                        name="parent_id">
                                        <option value="">{{ __('None (Top Level)') }}</option>
                                        @foreach ($parentCategories as $parent)
                                            <option value="{{ $parent->id }}"
                                                {{ old('parent_id', $postCategory->parent_id) == $parent->id ? 'selected' : '' }}>
                                                {{ $parent->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small
                                        class="text-muted">{{ __('Select a parent category to create a subcategory') }}</small>
                                    <x-common.error name="parent_id" />
                                </div>

                                <input type="hidden" name="status" value="0">
                                <div class="mb-3">
                                    <label class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="status" value="1"
                                            {{ old('status', $postCategory->status) ? 'checked' : '' }}>
                                        <span class="form-check-label">{{ __('Active') }}</span>
                                    </label>
                                </div>
                                <small class="text-muted">
                                    {{ __('Active categories can be assigned to posts and are visible throughout the system.') }}
                                </small>
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
                                    <a href="{{ route('admin.post-categories.index') }}" class="btn btn-secondary">
                                        {{ __('Cancel') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-check icon icon-1"></i>
                                        {{ __('Update Category') }}
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
                    // Check if slug was manually edited
                    const originalSlug = slugInput.value;
                    slugInput.addEventListener('input', function() {
                        if (this.value !== originalSlug) {
                            slugManuallyEdited = true;
                        }
                    });

                    nameInput.addEventListener('input', function() {
                        if (!slugManuallyEdited) {
                            slugInput.value = this.value.toLowerCase()
                                .replace(/[^a-z0-9]+/g, '-')
                                .replace(/^-+|-+$/g, '');
                        }
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
