<x-app-layout :page="__('Edit User')" layout="admin">

    <x-slot name="pretitle">{{ __('Users') }}</x-slot>
    <x-slot name="subtitle">{{ __('Edit User') }}</x-slot>

    <x-slot name="actions">
        <a href="{{ route('admin.users.index') }}" class="btn btn-primary">{{ __('Back') }}</a>
    </x-slot>

    <x-common.alert />

    <div class="row row-cards">
        <div class="col-12">
            <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
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
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">{{ __('First Name') }}</label>
                                            <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                                name="first_name" value="{{ old('first_name', $user->first_name) }}"
                                                placeholder="{{ __('Enter first name') }}" required>
                                            <x-common.error name="first_name" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">{{ __('Last Name') }}</label>
                                            <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                                name="last_name" value="{{ old('last_name', $user->last_name) }}"
                                                placeholder="{{ __('Enter last name') }}" required>
                                            <x-common.error name="last_name" />
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label required">{{ __('Email') }}</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email', $user->email) }}"
                                        placeholder="{{ __('Enter email address') }}" required>
                                    <x-common.error name="email" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Phone') }}</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                        name="phone" value="{{ old('phone', $user->phone) }}"
                                        placeholder="{{ __('Enter phone number') }}">
                                    <x-common.error name="phone" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Password') }}</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        name="password" placeholder="{{ __('Leave blank to keep current password') }}">
                                    <x-common.error name="password" />
                                    <small
                                        class="text-muted">{{ __('Leave blank if you don\'t want to change the password') }}</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Confirm Password') }}</label>
                                    <input type="password"
                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                        name="password_confirmation" placeholder="{{ __('Confirm password') }}">
                                    <x-common.error name="password_confirmation" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required">{{ __('Role') }}</label>
                                    <select class="form-select @error('role_id') is-invalid @enderror" name="role_id"
                                        required>
                                        <option value="">{{ __('Select role') }}</option>
                                        @foreach (\App\Models\Role::where('guard', 'web')->where('status', 1)->get() as $role)
                                            <option value="{{ $role->id }}"
                                                {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
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
                                            {{ old('status', $user->status) ? 'checked' : '' }}>
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
                                @if ($user->avatar)
                                    <div class="mb-3">
                                        <img src="{{ $user->avatar }}"
                                            alt="{{ $user->name }}" class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                @endif
                                <div class="mb-3">
                                    <input type="file"
                                        class="form-control @error('profile_picture') is-invalid @enderror"
                                        name="profile_picture" accept="image/*">
                                    <x-common.error name="profile_picture" />
                                </div>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Ban Status') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Ban Reason') }}</label>
                                    <textarea class="form-control @error('ban_reason') is-invalid @enderror" name="ban_reason" rows="3"
                                        placeholder="{{ __('Enter ban reason if applicable') }}">{{ old('ban_reason', $user->ban_reason) }}</textarea>
                                    <x-common.error name="ban_reason" />
                                </div>
                                @if ($user->banned_at)
                                    <small class="text-danger">
                                        {{ __('Banned on: ') . $user->banned_at->format('Y-m-d H:i:s') }}
                                    </small>
                                @endif
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
                                        {{ __('Update User') }}
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
