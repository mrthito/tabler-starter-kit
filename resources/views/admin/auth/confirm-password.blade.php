<x-guest-layout :page="__('Confirm Password')" layout="admin">

    <div class="card card-md">
        <div class="card-body">
            <h2 class="h2 text-center mb-4">{{ __('Confirm Password') }}</h2>

            <div class="alert alert-info">
                {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
            </div>

            <form action="{{ route('admin.password.confirm') }}" method="POST">
                @csrf
                <div class="mb-2">
                    <label class="form-label">
                        {{ __('Password') }}
                    </label>
                    <div class="input-group input-group-flat">
                        <input type="password" class="form-control" placeholder="{{ __('Your password') }}"
                            name="password" />
                        <span class="input-group-text">
                            <a href="#" class="link-secondary" title="{{ __('Show password') }}"
                                data-bs-toggle="tooltip" data-password-toggle="input[name='password']">
                                <i class="ti ti-eye icon icon-1"></i>
                            </a>
                        </span>
                    </div>
                </div>
                <div class="form-footer">
                    <button type="submit" class="btn btn-primary w-100 gap-2">
                        {{ __('Confirm Password') }}
                        <i class="ti ti-arrow-right icon icon-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-guest-layout>
