<x-guest-layout :page="__('Forgot Password')" layout="admin">

    <div class="card card-md">
        <div class="card-body">
            <h2 class="h2 text-center mb-4">{{ __('Forgot Password') }}</h2>

            <div class="alert alert-info">
                {{ __('No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </div>

            <form action="{{ route('admin.password.email') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">{{ __('Email address') }}</label>
                    <input type="email" class="form-control" placeholder="email@example.com" name="email"
                        value="{{ old('email') }}" />
                </div>
                <div class="form-footer">
                    <button type="submit" class="btn btn-primary w-100 gap-2">
                        {{ __('Email Password Reset Link') }}
                        <i class="ti ti-arrow-right icon icon-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-guest-layout>
