<x-guest-layout :page="__('Verify Email')" layout="admin">

    <div class="card card-md">
        <div class="card-body">
            <h2 class="h2 text-center mb-4">{{ __('Verify Email') }}</h2>
            <div class="alert alert-info">
                {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
            </div>
            @if (session('status') == 'verification-link-sent')
                <div class="alert alert-success">
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </div>
            @endif
            <div class="form-footer row">
                <div class="col-8">
                    <form action="{{ route('admin.verification.send') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100 gap-2">
                            {{ __('Resend Verification Email') }}
                            <i class="ti ti-arrow-right icon icon-1"></i>
                        </button>
                    </form>
                </div>
                <div class="col-4">
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-secondary w-100 gap-2">
                            {{ __('Log Out') }}
                            <i class="ti ti-logout icon icon-1"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-guest-layout>
