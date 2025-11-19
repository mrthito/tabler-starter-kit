<x-app-layout :page="__('User Details')" layout="admin">

    <x-slot name="breadcrumbs">
        <div class="page-pretitle">{{ __('Users') }}</div>
        <h2 class="page-title">{{ __('User Details') }}</h2>
    </x-slot>

    <x-slot name="actions">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-left icon icon-1"></i>
            {{ __('Back') }}
        </a>
        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
            <i class="ti ti-edit icon icon-1"></i>
            {{ __('Edit') }}
        </a>
    </x-slot>

    <x-common.alert />

    <div class="row row-cards">
        <!-- Profile Card -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    @if ($user->avatar)
                        <div class="mb-3">
                            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="avatar avatar-xl rounded">
                        </div>
                    @else
                        <div class="mb-3">
                            <span class="avatar avatar-xl rounded">{{ substr($user->name, 0, 2) }}</span>
                        </div>
                    @endif
                    <h3 class="m-0 mb-1">{{ $user->name }}</h3>
                    <div class="text-muted mb-3">{{ $user->email }}</div>

                    @if ($user->banned_at)
                        <span class="badge bg-danger-lt badge-lg">
                            <i class="ti ti-ban icon icon-1"></i>
                            {{ __('Banned') }}
                        </span>
                    @elseif ($user->status)
                        <span class="badge bg-success-lt badge-lg">
                            <i class="ti ti-check icon icon-1"></i>
                            {{ __('Active') }}
                        </span>
                    @else
                        <span class="badge bg-warning-lt badge-lg">
                            <i class="ti ti-x icon icon-1"></i>
                            {{ __('Inactive') }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Role Information -->
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Role Information') }}</h3>
                </div>
                <div class="list-group list-group-flush">
                    <div class="list-group-item">
                        <div class="row align-items-center">
                            <div class="col">
                                <strong>{{ __('Role') }}</strong>
                            </div>
                            <div class="col-auto">
                                <span class="badge bg-warning-lt">
                                    {{ $user->role?->name ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="row align-items-center">
                            <div class="col">
                                <strong>{{ __('Guard') }}</strong>
                            </div>
                            <div class="col-auto">
                                <span class="text-muted">{{ $user->role?->guard ?? 'web' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Status -->
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Account Status') }}</h3>
                </div>
                <div class="list-group list-group-flush">
                    <div class="list-group-item">
                        <div class="row align-items-center">
                            <div class="col">
                                <strong>{{ __('Status') }}</strong>
                            </div>
                            <div class="col-auto">
                                @if ($user->status)
                                    <span class="badge bg-success-lt">{{ __('Active') }}</span>
                                @else
                                    <span class="badge bg-warning-lt">{{ __('Inactive') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="row align-items-center">
                            <div class="col">
                                <strong>{{ __('Email Verified') }}</strong>
                            </div>
                            <div class="col-auto">
                                @if ($user->email_verified_at)
                                    <span class="badge bg-success-lt">{{ __('Yes') }}</span>
                                @else
                                    <span class="badge bg-danger">{{ __('No') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if ($user->phone)
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col">
                                    <strong>{{ __('Phone Verified') }}</strong>
                                </div>
                                <div class="col-auto">
                                    @if ($user->phone_verified_at)
                                        <span class="badge bg-success">{{ __('Yes') }}</span>
                                    @else
                                        <span class="badge bg-danger-lt">{{ __('No') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Details Card -->
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Basic Information') }}</h3>
                </div>
                <div class="card-body">
                    <div class="datagrid">
                        <div class="datagrid-item">
                            <div class="datagrid-title">{{ __('Full Name') }}</div>
                            <div class="datagrid-content">{{ $user->name }}</div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">{{ __('Email Address') }}</div>
                            <div class="datagrid-content">
                                <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                            </div>
                        </div>
                        @if ($user->phone)
                            <div class="datagrid-item">
                                <div class="datagrid-title">{{ __('Phone Number') }}</div>
                                <div class="datagrid-content">
                                    <a href="tel:{{ $user->phone }}">{{ $user->phone }}</a>
                                </div>
                            </div>
                        @endif
                        <div class="datagrid-item">
                            <div class="datagrid-title">{{ __('Role') }}</div>
                            <div class="datagrid-content">
                                <span class="badge bg-warning-lt">
                                    {{ $user->role?->name ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ban Information (if banned) -->
            @if ($user->banned_at)
                <div class="card mt-3">
                    <div class="card-header bg-danger-lt">
                        <h3 class="card-title">
                            <i class="ti ti-ban icon icon-1 me-1"></i>
                            {{ __('Ban Information') }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="datagrid">
                            <div class="datagrid-item">
                                <div class="datagrid-title">{{ __('Banned At') }}</div>
                                <div class="datagrid-content text-danger">
                                    {{ $user->banned_at->format('F j, Y \a\t g:i A') }}
                                </div>
                            </div>
                            @if ($user->banned_reason)
                                <div class="datagrid-item">
                                    <div class="datagrid-title">{{ __('Ban Reason') }}</div>
                                    <div class="datagrid-content">{{ $user->banned_reason }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Permissions -->
            @if ($user->role && $user->role->permissions)
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Permissions') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            @forelse($user->role->permissions as $permission)
                                @if ($permission == '*')
                                    <div class="col-md-4 col-lg-3">
                                        <div class="badge bg-primary-lt w-100 text-start">
                                            <i class="ti ti-check icon icon-1 me-1"></i>
                                            {{ __('All Permissions') }}
                                        </div>
                                    </div>
                                @else
                                    <div class="col-md-4 col-lg-3">
                                        <div class="badge bg-primary-lt w-100 text-start">
                                            <i class="ti ti-check icon icon-1 me-1"></i>
                                            {{ str_replace('_', ' ', ucwords($permission)) }}
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <div class="col-12">
                                    <div class="text-muted">{{ __('No permissions assigned') }}</div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif

            <!-- Timestamps -->
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Account Timeline') }}</h3>
                </div>
                <div class="card-body">
                    <div class="datagrid">
                        <div class="datagrid-item">
                            <div class="datagrid-title">{{ __('Created At') }}</div>
                            <div class="datagrid-content">
                                {{ $user->created_at->format('F j, Y \a\t g:i A') }}
                                <span class="text-muted">({{ $user->created_at->diffForHumans() }})</span>
                            </div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">{{ __('Last Updated') }}</div>
                            <div class="datagrid-content">
                                {{ $user->updated_at->format('F j, Y \a\t g:i A') }}
                                <span class="text-muted">({{ $user->updated_at->diffForHumans() }})</span>
                            </div>
                        </div>
                        @if ($user->email_verified_at)
                            <div class="datagrid-item">
                                <div class="datagrid-title">{{ __('Email Verified At') }}</div>
                                <div class="datagrid-content">
                                    {{ $user->email_verified_at->format('F j, Y \a\t g:i A') }}
                                    <span class="text-muted">({{ $user->email_verified_at->diffForHumans() }})</span>
                                </div>
                            </div>
                        @endif
                        @if ($user->phone_verified_at)
                            <div class="datagrid-item">
                                <div class="datagrid-title">{{ __('Phone Verified At') }}</div>
                                <div class="datagrid-content">
                                    {{ $user->phone_verified_at->format('F j, Y \a\t g:i A') }}
                                    <span class="text-muted">({{ $user->phone_verified_at->diffForHumans() }})</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
