<x-app-layout :page="__('Profile')" layout="admin">

    <div class="card">
        <div class="row g-0">
            <div class="col-12 col-md-3 border-end">
                <div class="card-body">
                    <h4 class="subheader">{{ __('Profile Settings') }}</h4>
                    <div class="list-group list-group-transparent">
                        <a href="{{ route('admin.profile.edit') }}"
                            class="list-group-item list-group-item-action d-flex align-items-center {{ $tab === 'profile' ? 'active' : '' }} gap-2">
                            <i class="ti ti-user icon icon-1"></i>
                            {{ __('Profile') }}
                        </a>
                        <a href="{{ route('admin.profile.edit', ['tab' => 'password']) }}"
                            class="list-group-item list-group-item-action d-flex align-items-center {{ $tab === 'password' ? 'active' : '' }} gap-2">
                            <i class="ti ti-lock icon icon-1"></i>
                            {{ __('Change Password') }}
                        </a>
                        <a href="{{ route('admin.profile.edit', ['tab' => 'two-factor-authentication']) }}"
                            class="list-group-item list-group-item-action d-flex align-items-center {{ $tab === 'two-factor-authentication' ? 'active' : '' }} gap-2">
                            <i class="ti ti-shield icon icon-1"></i>
                            {{ __('Two-Factor Authentication') }}
                        </a>
                        <a href="{{ route('admin.profile.edit', ['tab' => 'danger-zone']) }}"
                            class="list-group-item list-group-item-action d-flex align-items-center {{ $tab === 'danger-zone' ? 'active' : '' }} gap-2">
                            <i class="ti ti-alert-triangle icon icon-1"></i>
                            {{ __('Danger Zone') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-9 d-flex flex-column">
                @if ($tab === 'password')
                    @include('admin.profile.partials.update-password-form')
                @elseif ($tab === 'two-factor-authentication')
                    @include('admin.profile.partials.two-factor-authentication-form')
                @elseif ($tab === 'danger-zone')
                    @include('admin.profile.partials.delete-user-form')
                @else
                    @include('admin.profile.partials.update-profile-information-form')
                @endif
            </div>
        </div>
    </div>

</x-app-layout>
