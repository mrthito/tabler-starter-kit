<x-app-layout :page="__('Create Menu')" layout="admin">

    <x-slot name="breadcrumbs">
        <div class="page-pretitle">{{ __('Appearance') }}</div>
        <h2 class="page-title">{{ __('Create Menu') }}</h2>
    </x-slot>

    <x-slot name="actions">
        <a href="{{ route('admin.appearance.menus.index') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-left icon icon-1"></i>
            {{ __('Back') }}
        </a>
    </x-slot>

    <x-common.alert />

    <div class="row row-cards">
        <div class="col-12">
            <form action="{{ route('admin.appearance.menus.store') }}" method="POST">
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
                                    <label class="form-label required">{{ __('Menu Title') }}</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        name="title" value="{{ old('title') }}"
                                        placeholder="{{ __('e.g., Main Navigation, Footer Menu') }}" required>
                                    <small class="form-hint">
                                        {{ __('A descriptive name for this menu (used in admin only).') }}
                                    </small>
                                    <x-common.error name="title" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Menu Name') }}</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name') }}"
                                        placeholder="{{ __('e.g., main-nav, footer-menu') }}">
                                    <small class="form-hint">
                                        {{ __('Unique identifier (slug). Leave empty to auto-generate from title.') }}
                                    </small>
                                    <x-common.error name="name" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Location') }}</label>
                                    <input type="text" class="form-control @error('location') is-invalid @enderror"
                                        name="location" value="{{ old('location') }}"
                                        placeholder="{{ __('e.g., header, footer, sidebar') }}">
                                    <small class="form-hint">
                                        {{ __('Where this menu appears (optional, used for theme integration).') }}
                                    </small>
                                    <x-common.error name="location" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Description') }}</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3"
                                        placeholder="{{ __('Optional description for this menu.') }}">{{ old('description') }}</textarea>
                                    <x-common.error name="description" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Settings -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Menu Settings') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Display Order') }}</label>
                                    <input type="number" class="form-control @error('order') is-invalid @enderror"
                                        name="order" value="{{ old('order', 0) }}" min="0">
                                    <small class="form-hint">
                                        {{ __('Lower numbers appear first.') }}
                                    </small>
                                    <x-common.error name="order" />
                                </div>

                                <div class="mb-3">
                                    <input type="hidden" name="status" value="0">
                                    <label class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="status" value="1"
                                            {{ old('status', true) ? 'checked' : '' }}>
                                        <span class="form-check-label">{{ __('Active') }}</span>
                                    </label>
                                    <small class="form-hint d-block mt-1">
                                        {{ __('Active menus can be displayed on the frontend.') }}
                                    </small>
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
                                    <a href="{{ route('admin.appearance.menus.index') }}" class="btn btn-secondary">
                                        <i class="ti ti-x icon icon-1"></i>
                                        {{ __('Cancel') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-check icon icon-1"></i>
                                        {{ __('Create Menu') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
