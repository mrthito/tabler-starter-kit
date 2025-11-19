<div class="card-body">

    <h2 class="mb-4">{{ __('Profile Information') }}</h2>

    <form id="send-verification" class="d-none" method="post" action="{{ route('admin.verification.send') }}">
        @csrf
    </form>
    <form method="POST" action="{{ route('admin.profile.update') }}" id="updateProfileInformationForm">
        @csrf
        @method('patch')

        <div class="row align-items-center">
            <div class="col-auto">
                <span class="avatar avatar-xl" style="background-image: url({{ $admin->avatar }})"></span>
            </div>
            <div class="col-auto">
                <a href="javascript:void(0)" class="btn btn-1" data-bs-toggle="modal"
                    data-bs-target="#changeAvatarModal">
                    {{ __('Change Avatar') }}
                </a>
            </div>
        </div>

        <h3 class="card-title mt-4">{{ __('Profile Information') }}</h3>
        <div class="row g-3">
            <div class="col-md-12">
                <div class="form-label">
                    {{ __('Name') }}
                </div>
                <input type="text" class="form-control" value="{{ old('name', $admin->name) }}" name="name" />
            </div>
        </div>

        <h3 class="card-title mt-4">{{ __('Email') }}</h3>
        <div>
            <div class="row g-2">
                <div class="col-auto profile-email-container">
                    <input type="text" class="form-control w-auto profile-email"
                        value="{{ old('email', $admin->email) }}" name="email" readonly disabled />
                    @if ($admin instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$admin->hasVerifiedEmail())
                        <div class="mt-2">
                            <p class="mb-0">
                                {{ __('Your email address is unverified.') }}

                                <button form="send-verification" class="btn btn-link p-0">
                                    {{ __('Click here to re-send the verification email.') }}
                                </button>
                            </p>

                            @if (session('status') === 'verification-link-sent')
                                <div class="alert alert-success mt-3 mb-0" role="alert">
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="col-auto">
                    <a href="javascript:void(0)" class="btn btn-1 edit-email-button" onclick="editEmail()">
                        {{ __('Change') }}
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="card-footer bg-transparent mt-auto">
    <div class="btn-list justify-content-end">
        <button type="submit" class="btn btn-primary btn-2" form="updateProfileInformationForm">
            {{ __('Save Changes') }}
        </button>
    </div>
</div>


<div class="modal fade" id="changeAvatarModal" tabindex="-1" aria-labelledby="changeAvatarModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changeAvatarModalLabel">Change Avatar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.profile.update', ['mode' => 'avatar']) }}" id="updateAvatarForm" enctype="multipart/form-data">
                @csrf
                @method('patch')
                <div class="modal-body">
                    <div class="row gap-3">
                        <div class="col-md-3">
                            <img src="{{ $admin->avatar }}" id="avatarImage" class="img-fluid" />
                        </div>
                        <div class="col-md-12">
                            <input type="file" class="form-control" name="avatar" />
                            <small class="text-muted">
                                {{ __('The avatar will be displayed in the profile.') }}
                            </small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{ __('Close') }}
                    </button>
                    <button type="submit" class="btn btn-primary" form="updateAvatarForm">
                        {{ __('Save Changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function editEmail() {
            const emailContainer = document.querySelector('.profile-email-container');
            const emailInput = document.querySelector('.profile-email');
            const editEmailButton = document.querySelector('.edit-email-button');

            // Remove disabled and readonly
            emailInput.removeAttribute('readonly');
            emailInput.removeAttribute('disabled');
            editEmailButton.style.display = 'none';
            emailContainer.classList.add('col-md-12');
            emailContainer.classList.remove('col-auto');
            emailInput.classList.remove('w-auto');
            emailInput.classList.add('w-100');
            // Focus the input
            emailInput.focus();
        }
    </script>
@endpush
