<div class="card-body">
    <h2 class="mb-4">{{ __('Change Password') }}</h2>
    <div class="alert alert-info">
        {{ __('Ensure your account is using a long, random password to stay secure.') }}
    </div>

    @if (session('status') === 'password-updated')
        <div class="alert alert-success">
            {{ __('Password updated successfully.') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.password.update') }}" id="updatePasswordForm">
        @csrf
        @method('put')

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <label for="current_password" class="col-md-4 col-form-label">
                    {{ __('Current Password') }}
                </label>

                <input id="current_password" type="password"
                    class="form-control @error('current_password') is-invalid @enderror" name="current_password"
                    required autocomplete="current-password">
                @error('current_password')
                    <span class="small text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="col-md-12">
                <label for="password" class="col-md-4 col-form-label">
                    {{ __('New Password') }}
                </label>

                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" required autocomplete="new-password">
                @error('password')
                    <span class="small text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="col-md-12">
                <label for="password_confirmation" class="col-md-4 col-form-label">
                    {{ __('Confirm Password') }}
                </label>

                <input id="password_confirmation" type="password"
                    class="form-control @error('password_confirmation') is-invalid @enderror"
                    name="password_confirmation" required autocomplete="new-password">
                @error('password_confirmation')
                    <span class="small text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

    </form>
</div>
<div class="card-footer bg-transparent mt-auto">
    <div class="btn-list justify-content-end">
        <button type="submit" class="btn btn-primary btn-2" form="updatePasswordForm">
            {{ __('Save') }}
        </button>
    </div>
</div>
