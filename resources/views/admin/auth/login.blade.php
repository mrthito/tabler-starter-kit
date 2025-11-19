<x-guest-layout :page="__('Login')" layout="admin">

    <div class="card card-md">
        <div class="card-body">
            <h2 class="h2 text-center mb-4">{{ __('Login to your account') }}</h2>
            <form action="{{ route('admin.login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">{{ __('Email address') }}</label>
                    <input type="email" class="form-control" placeholder="email@example.com" name="email"
                        value="{{ old('email') }}" />
                </div>
                <div class="mb-2">
                    <label class="form-label">
                        {{ __('Password') }}
                        <span class="form-label-description">
                            <a href="{{ route('admin.password.request') }}">
                                {{ __('I forgot password') }}
                            </a>
                        </span>
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
                <div class="mb-2">
                    <label class="form-check">
                        <input type="checkbox" class="form-check-input" name="remember" />
                        <span class="form-check-label">{{ __('Remember me on this device') }}</span>
                    </label>
                </div>
                <div class="form-footer">
                    <button type="submit" class="btn btn-primary w-100 gap-2">
                        {{ __('Sign in') }}
                        <i class="ti ti-arrow-right icon icon-1"></i>
                    </button>
                </div>
            </form>
        </div>
        @if ($socialiteEnabled)
            <div class="hr-text">or</div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <a href="#" class="btn btn-4 w-100">
                            <i class="ti ti-brand-github icon icon-2"></i>
                            {{ __('Login with Github') }}
                        </a>
                    </div>
                    <div class="col">
                        <a href="#" class="btn btn-4 w-100">
                            <i class="ti ti-brand-x icon icon-2"></i>
                            {{ __('Login with X') }}
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
    @if ($showRegister)
        <div class="text-center text-secondary mt-3">
            {{ __('Don\'t have account yet?') }}
            <a href="{{ route('admin.register') }}" tabindex="-1">{{ __('Sign up') }}</a>
        </div>
    @endif

</x-guest-layout>
