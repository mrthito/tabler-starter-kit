<x-app-layout :page="__('Create Role')" layout="admin">

    <x-slot name="breadcrumbs">
        <div class="page-pretitle">{{ __('Roles') }}</div>
        <h2 class="page-title">{{ __('Create Role') }}</h2>
    </x-slot>

    <x-slot name="actions">
        <a href="{{ route('admin.roles.index') }}" class="btn btn-primary">{{ __('Back') }}</a>
    </x-slot>

    <x-common.alert />

    <div class="row row-cards">
        <div class="col-12">
            <form action="{{ route('admin.roles.store') }}" method="POST">
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
                                    <label class="form-label required">{{ __('Role Name') }}</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name') }}"
                                        placeholder="{{ __('Enter role name (e.g., admin, manager)') }}" required>
                                    <x-common.error name="name" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required">{{ __('Guard') }}</label>
                                    <select class="form-select @error('guard') is-invalid @enderror" name="guard"
                                        required>
                                        <option value="">{{ __('Select guard') }}</option>
                                        <option value="admin" {{ old('guard') == 'admin' ? 'selected' : '' }}>
                                            {{ __('Admin') }}</option>
                                        <option value="web" {{ old('guard') == 'web' ? 'selected' : '' }}>
                                            {{ __('Web') }}</option>
                                    </select>
                                    <x-common.error name="guard" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Card -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Role Status') }}</h3>
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="status" value="0">
                                <div class="mb-3">
                                    <label class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="status" value="1"
                                            {{ old('status', true) ? 'checked' : '' }}>
                                        <span class="form-check-label">{{ __('Active') }}</span>
                                    </label>
                                </div>
                                <small class="text-muted">
                                    {{ __('Active roles can be assigned to users and are visible throughout the system.') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Permissions Section -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Permissions') }}</h3>
                                <div class="card-actions">
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                        id="selectAllPermissions">
                                        {{ __('Select All') }}
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                        id="deselectAllPermissions">
                                        {{ __('Deselect All') }}
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="permissions-container">
                                    @php
                                        $permissions = config('permissions');
                                    @endphp

                                    @foreach ($permissions as $groupKey => $groupPermissions)
                                        <div class="permission-group mb-4">
                                            <div class="border rounded p-3">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h4 class="m-0 text-capitalize">
                                                        <i class="ti ti-folder icon icon-1 me-1"></i>
                                                        {{ __(str_replace('_', ' ', ucwords($groupKey))) }}
                                                    </h4>
                                                    <div>
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-primary select-group"
                                                            data-group="{{ $groupKey }}">
                                                            {{ __('Select All') }}
                                                        </button>
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-secondary deselect-group"
                                                            data-group="{{ $groupKey }}">
                                                            {{ __('Deselect All') }}
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    @foreach ($groupPermissions as $permissionKey => $permissionValue)
                                                        <div class="col-md-6 col-lg-4 mb-2">
                                                            <label class="form-check form-switch">
                                                                <input class="form-check-input permission-checkbox"
                                                                    type="checkbox" name="permissions[]"
                                                                    value="{{ $permissionValue }}"
                                                                    data-group="{{ $groupKey }}"
                                                                    {{ in_array($permissionValue, old('permissions', [])) ? 'checked' : '' }}>
                                                                <span class="form-check-label">
                                                                    {{ __(str_replace('_', ' ', ucwords($permissionKey))) }}
                                                                </span>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <x-common.error name="permissions" />
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
                                    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                                        {{ __('Cancel') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-check icon icon-1"></i>
                                        {{ __('Create Role') }}
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
                // Select all permissions
                document.getElementById('selectAllPermissions')?.addEventListener('click', function() {
                    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
                        checkbox.checked = true;
                    });
                });

                // Deselect all permissions
                document.getElementById('deselectAllPermissions')?.addEventListener('click', function() {
                    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
                        checkbox.checked = false;
                    });
                });

                // Select all in a group
                document.querySelectorAll('.select-group').forEach(button => {
                    button.addEventListener('click', function() {
                        const group = this.getAttribute('data-group');
                        document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`)
                            .forEach(checkbox => {
                                checkbox.checked = true;
                            });
                    });
                });

                // Deselect all in a group
                document.querySelectorAll('.deselect-group').forEach(button => {
                    button.addEventListener('click', function() {
                        const group = this.getAttribute('data-group');
                        document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`)
                            .forEach(checkbox => {
                                checkbox.checked = false;
                            });
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
