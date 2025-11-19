<x-guest-layout :page="__('Reset Password')" layout="admin">

    <div class="card card-md">
        <div class="card-body">
            <h2 class="h2 text-center mb-4">{{ __('Reset Password') }}</h2>
            <form action="{{ route('admin.password.store') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                <input type="hidden" name="email" value="{{ $request->email }}">
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
                        {{ __('Reset Password') }}
                        <i class="ti ti-arrow-right icon icon-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-guest-layout>
