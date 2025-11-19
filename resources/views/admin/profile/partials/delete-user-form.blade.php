<div class="card-body">

    <h2 class="mb-4">{{ __('Danger Zone') }}</h2>

    <div class="alert alert-danger">
        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
    </div>
</div>
<div class="card-footer bg-transparent mt-auto">
    <div class="btn-list justify-content-end">
        <a href="javascript:void(0)" class="btn btn-danger btn-2" data-bs-toggle="modal"
            data-bs-target="#deleteAccountModal"> Delete Account </a>
    </div>
</div>

<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="deleteAccountModalLabel">
                    {{ __('Are you sure you want to delete your account?') }}
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </div>
                <form id="deleteAccountForm" method="post" action="{{ route('admin.profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="mb-3">
                        <input type="password"
                            class="form-control @error('password', 'adminDeletion') is-invalid @enderror" name="password"
                            placeholder="{{ __('Password') }}" required>

                        @error('password', 'adminDeletion')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    {{ __('Cancel') }}
                </button>
                <button type="submit" class="btn btn-danger" form="deleteAccountForm">
                    {{ __('Delete Account') }}
                </button>
            </div>
        </div>
    </div>
</div>


@push('scripts')
    @php $shouldOpenModal = $errors->adminDeletion->isNotEmpty(); @endphp

    <script>
        let shouldOpenModal = {{ Js::from($shouldOpenModal) }};

        if (shouldOpenModal) {
            window.addEventListener('load', function() {
                let deleteAccountModal = new bootstrap.Modal('#deleteAccountModal');
                deleteAccountModal.toggle();
            });
        }
    </script>
@endpush
