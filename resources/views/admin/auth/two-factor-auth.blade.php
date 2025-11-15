<x-guest-layout :page="__('Two Factor Verification')" layout="admin">

    <div class="card card-md">
        <div class="card-body">
            <h2 class="h2 text-center mb-4">{{ __('Two Factor Verification') }}</h2>
            <p class="my-4 text-center">
                {{ __('Enter the verification code sent to your email address.') }}
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
                    <div class="text-end mt-2">
                        <button type="button" class="btn text-muted btn-sm gap-2 d-inline-flex align-items-end"
                            onclick="document.getElementById('resend').submit()">
                            {{ __('Resend code') }}
                            <i class="ti ti-refresh icon icon-1"></i>
                        </button>
                    </div>
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
            // make sure pasting the code works
            document.querySelectorAll('[data-code-input]').forEach(input => {
                input.addEventListener('paste', (e) => {
                    e.preventDefault();
                    const code = e.clipboardData.getData('text').trim();
                    const codeArray = code.split('');
                    codeArray.forEach((char, index) => {
                        document.querySelector(`[name="code[${index + 1}]"]`).value = char;
                    });
                });
            });
            document.querySelectorAll('[data-code-input]').forEach(input => {
                input.addEventListener('input', (e) => {
                    const code = e.target.value.trim();
                    const codeArray = code.split('');
                    codeArray.forEach((char, index) => {
                        document.querySelector(`[name="code[${index + 1}]"]`).value = char;
                    });
                });
            });
        </script>
    @endpush

</x-guest-layout>
