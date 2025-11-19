<x-app-layout :page="__('Create User')" layout="admin">

    <x-slot name="breadcrumbs">
        <div class="page-pretitle">{{ __('Users') }}</div>
        <h2 class="page-title">{{ __('Create User') }}</h2>
    </x-slot>

    <x-slot name="actions">
        <a href="{{ route('admin.users.index') }}" class="btn btn-primary">{{ __('Back') }}</a>
    </x-slot>

    <x-common.alert />

    <div class="row row-cards">
        <div class="col-12">
            <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Basic Information') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">{{ __('First Name') }}</label>
                                            <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                                name="first_name" value="{{ old('first_name') }}"
                                                placeholder="{{ __('Enter first name') }}" required>
                                            <x-common.error name="first_name" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">{{ __('Last Name') }}</label>
                                            <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                                name="last_name" value="{{ old('last_name') }}"
                                                placeholder="{{ __('Enter last name') }}" required>
                                            <x-common.error name="last_name" />
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required">{{ __('Email') }}</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}"
                                        placeholder="{{ __('Enter email address') }}" required>
                                    <x-common.error name="email" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Phone') }}</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                        name="phone" value="{{ old('phone') }}"
                                        placeholder="{{ __('Enter phone number') }}">
                                    <x-common.error name="phone" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required">{{ __('Password') }}</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        name="password" placeholder="{{ __('Enter password') }}" required>
                                    <x-common.error name="password" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required">{{ __('Confirm Password') }}</label>
                                    <input type="password"
                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                        name="password_confirmation" placeholder="{{ __('Confirm password') }}"
                                        required>
                                    <x-common.error name="password_confirmation" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required">{{ __('Role') }}</label>
                                    <select class="form-select @error('role_id') is-invalid @enderror" name="role_id"
                                        required>
                                        <option value="">{{ __('Select role') }}</option>
                                        @foreach (\App\Models\Role::where('guard', 'web')->where('status', 1)->get() as $role)
                                            <option value="{{ $role->id }}"
                                                {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-common.error name="role_id" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Card -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('User Status') }}</h3>
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
                                    {{ __('Active users can log in and access their assigned permissions.') }}
                                </small>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Profile Picture') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <input type="file"
                                        class="form-control @error('profile_picture_path') is-invalid @enderror"
                                        name="profile_picture_path" accept="image/*">
                                    <x-common.error name="profile_picture_path" />
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
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                        {{ __('Cancel') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-check icon icon-1"></i>
                                        {{ __('Create User') }}
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
