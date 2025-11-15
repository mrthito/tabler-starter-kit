<x-guest-layout :page="__('Register')" layout="admin">

    <div class="card card-md">
        <div class="card-body">
            <h2 class="h2 text-center mb-4">{{ __('Register your account') }}</h2>
            <form action="{{ route('admin.register') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">{{ __('Name') }}</label>
                    <input type="text" class="form-control" placeholder="John Doe" name="name"
                        value="{{ old('email') }}" />
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('Email address') }}</label>
                    <input type="email" class="form-control" placeholder="email@example.com" name="email"
                        value="{{ old('email') }}" />
                </div>
                <div class="mb-3">
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
                <div class="mb-3">
                    <label class="form-label">
                        {{ __('Confirm Password') }}
                    </label>
                    <div class="input-group input-group-flat">
                        <input type="password" class="form-control" placeholder="{{ __('Confirm your password') }}"
                            name="password_confirmation" />
                        <span class="input-group-text">
                            <a href="#" class="link-secondary" title="{{ __('Show password') }}"
                                data-bs-toggle="tooltip" data-password-toggle="input[name='password_confirmation']">
                                <i class="ti ti-eye icon icon-1"></i>
                            </a>
                        </span>
                    </div>
                </div>
                <div class="form-footer">
                    <button type="submit" class="btn btn-primary w-100 gap-2">
                        {{ __('Register') }}
                        <i class="ti ti-arrow-right icon icon-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="text-center text-secondary mt-3">
        {{ __('Already have account?') }}
        <a href="{{ route('admin.login') }}" tabindex="-1">{{ __('Sign in') }}</a>
    </div>

</x-guest-layout>
