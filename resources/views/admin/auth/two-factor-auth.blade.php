<x-guest-layout :page="__('Two Factor Verification')" layout="admin">

    <div class="card card-md">
        <div class="card-body">
            <h2 class="h2 text-center mb-4">{{ __('Two Factor Verification') }}</h2>
            <p class="my-4 text-center">
                {{ $instructions ?? __('Enter the verification code.') }}
            </p>
            <p class="text-muted text-center small mb-4">
                {{ __('You can also use a recovery code if you have one.') }}
            </p>
            <form action="{{ route('admin.login.2fa') }}" method="POST">
                @csrf
                <div class="my-5">
                    <div class="row g-4">
                        <div class="col">
                            <div class="row g-2">
                                <div class="col">
                                    <input type="text" class="form-control form-control-lg text-center px-3 py-3"
                                        maxlength="1" inputmode="numeric" pattern="[0-9]*" data-code-input=""
                                        name="code[1]">
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control form-control-lg text-center px-3 py-3"
                                        maxlength="1" inputmode="numeric" pattern="[0-9]*" data-code-input=""
                                        name="code[2]">
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control form-control-lg text-center px-3 py-3"
                                        maxlength="1" inputmode="numeric" pattern="[0-9]*" data-code-input=""
                                        name="code[3]">
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="row g-2">
                                <div class="col">
                                    <input type="text" class="form-control form-control-lg text-center px-3 py-3"
                                        maxlength="1" inputmode="numeric" pattern="[0-9]*" data-code-input=""
                                        name="code[4]">
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control form-control-lg text-center px-3 py-3"
                                        maxlength="1" inputmode="numeric" pattern="[0-9]*" data-code-input=""
                                        name="code[5]">
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control form-control-lg text-center px-3 py-3"
                                        maxlength="1" inputmode="numeric" pattern="[0-9]*" data-code-input=""
                                        name="code[6]">
                                </div>
                            </div>
                        </div>
                        @error('code')
                            <div class="col-12">
                                <div class="text-danger small">
                                    {{ $message }}
                                </div>
                            </div>
                        @enderror
                    </div>
                    @if ($showResend ?? false)
                        <div class="text-end mt-2">
                            <button type="button" class="btn text-muted btn-sm gap-2 d-inline-flex align-items-end"
                                onclick="document.getElementById('resend').submit()">
                                {{ __('Resend code') }}
                                <i class="ti ti-refresh icon icon-1"></i>
                            </button>
                        </div>
                    @endif
                </div>

                <div class="form-footer">
                    <div class="btn-list flex-nowrap">
                        <a href="javascript:void(0)" class="btn btn-3 w-100"
                            onclick="document.getElementById('logout').submit()">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary btn-3 w-100 gap-2">
                            {{ __('Verify') }}
                            <i class="ti ti-arrow-right icon icon-1"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <form action="{{ route('admin.logout') }}" method="POST" id="logout" name="logout" style="display: none;">
        @csrf
    </form>

    <form action="{{ route('admin.login.2fa.resend') }}" method="POST" id="resend" name="resend"
        style="display: none;">
        @csrf
    </form>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const inputs = document.querySelectorAll('[data-code-input]');

                // Handle paste event
                inputs.forEach(input => {
                    input.addEventListener('paste', (e) => {
                        e.preventDefault();
                        const pastedData = e.clipboardData.getData('text').trim();
                        const codeArray = pastedData.split('').slice(0, 6);

                        codeArray.forEach((char, index) => {
                            if (index < inputs.length) {
                                inputs[index].value = char;
                            }
                        });

                        // Focus the next empty input or the last one
                        const nextEmptyIndex = codeArray.length;
                        if (nextEmptyIndex < inputs.length) {
                            inputs[nextEmptyIndex].focus();
                        } else {
                            inputs[inputs.length - 1].focus();
                        }
                    });
                });

                // Handle input events (typing)
                inputs.forEach((input, index) => {
                    input.addEventListener('input', (e) => {
                        // Only allow numeric characters
                        e.target.value = e.target.value.replace(/[^0-9]/g, '');

                        // If a character was entered, move to next input
                        if (e.target.value.length > 0 && index < inputs.length - 1) {
                            inputs[index + 1].focus();
                        }
                    });

                    // Handle keydown for backspace/delete
                    input.addEventListener('keydown', (e) => {
                        // If backspace is pressed and input is empty, move to previous input
                        if (e.key === 'Backspace' && e.target.value === '' && index > 0) {
                            e.preventDefault();
                            inputs[index - 1].focus();
                            inputs[index - 1].value = '';
                        }

                        // Handle arrow keys
                        if (e.key === 'ArrowLeft' && index > 0) {
                            e.preventDefault();
                            inputs[index - 1].focus();
                        }
                        if (e.key === 'ArrowRight' && index < inputs.length - 1) {
                            e.preventDefault();
                            inputs[index + 1].focus();
                        }
                    });

                    // Handle focus - select all text when focused
                    input.addEventListener('focus', (e) => {
                        e.target.select();
                    });
                });
            });
        </script>
    @endpush

</x-guest-layout>
