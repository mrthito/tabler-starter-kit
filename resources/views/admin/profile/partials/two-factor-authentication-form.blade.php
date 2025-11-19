<div class="card-body">
    @if (!$mfaHelper->confirmed)
        <div class="mb-4">
            <span class="badge bg-orange-lt">{{ __('Disabled') }}</span>
        </div>
        <p class="text-muted mb-4">
            {{ __('When you enable 2FA, you\'ll be prompted for a secure code during login, which can be retrieved from your phone\'s Google Authenticator app, email, or SMS.') }}
        </p>

        <div class="mb-4">
            <label class="form-label">{{ __('Choose 2FA Method') }}</label>
            <div class="form-selectgroup form-selectgroup-boxes d-flex flex-column gap-2">
                <label class="form-selectgroup-item">
                    <input type="radio" name="mfa_method" value="3" class="form-selectgroup-input" checked>
                    <div class="form-selectgroup-label d-flex align-items-center p-3">
                        <div class="me-3">
                            <span class="form-selectgroup-check"></span>
                        </div>
                        <div>
                            <strong class="form-selectgroup-label-content">{{ __('Google Authenticator') }}</strong>
                            <span class="d-block text-muted">{{ __('Use an authenticator app on your phone') }}</span>
                        </div>
                    </div>
                </label>
                <label class="form-selectgroup-item">
                    <input type="radio" name="mfa_method" value="1" class="form-selectgroup-input">
                    <div class="form-selectgroup-label d-flex align-items-center p-3">
                        <div class="me-3">
                            <span class="form-selectgroup-check"></span>
                        </div>
                        <div>
                            <strong class="form-selectgroup-label-content">{{ __('Email') }}</strong>
                            <span class="d-block text-muted">{{ __('Receive codes via email') }}</span>
                        </div>
                    </div>
                </label>
                <label class="form-selectgroup-item">
                    <input type="radio" name="mfa_method" value="2" class="form-selectgroup-input">
                    <div class="form-selectgroup-label d-flex align-items-center p-3">
                        <div class="me-3">
                            <span class="form-selectgroup-check"></span>
                        </div>
                        <div>
                            <strong class="form-selectgroup-label-content">{{ __('SMS') }}</strong>
                            <span class="d-block text-muted">{{ __('Receive codes via SMS') }}</span>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        <button type="button" class="btn btn-primary" id="enable-2fa-btn" onclick="enable2FA()">
            <span class="spinner-border spinner-border-sm d-none" id="enable-spinner" role="status"></span>
            {{ __('Enable 2FA') }}
        </button>

        <!-- Modal for QR Code / Verification -->
        <div class="modal fade" id="verifyModal" tabindex="-1" aria-labelledby="verifyModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="verifyModalLabel">
                            <span id="modal-title-text">{{ __('Turn on 2-step Verification') }}</span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="qr-code-section" class="d-none">
                            <p class="text-muted mb-4" id="qr-instructions">
                                {{ __('Open your authenticator app and choose Scan QR code') }}
                            </p>

                            <div class="text-center mb-4">
                                <div class="d-inline-block border rounded p-3 bg-white position-relative"
                                    id="qr-container">
                                    <div class="spinner-border text-primary d-none" id="qr-spinner" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <img id="qr-image" src="" alt="QR Code" class="d-none"
                                        style="max-width: 256px;">
                                </div>
                            </div>

                            <div class="mb-3">
                                <button type="button" class="btn btn-primary w-100"
                                    onclick="setVerifyMode()">{{ __('Continue') }}</button>
                            </div>

                            <div class="divider">
                                <div class="divider-text">{{ __('or, enter the code manually') }}</div>
                            </div>

                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="secret-key" readonly>
                                <button class="btn btn-outline-secondary" type="button" onclick="copySecretKey()"
                                    id="copy-btn">
                                    <span id="copy-icon">📋</span>
                                </button>
                            </div>
                        </div>

                        <div id="verify-code-section" class="d-none">
                            <p class="text-muted mb-4" id="verify-instructions">
                                {{ __('Enter the 6-digit code from your authenticator app') }}</p>

                            <div class="mb-3">
                                <label for="auth-code" class="form-label">{{ __('Verification Code') }}</label>
                                <input type="text" class="form-control text-center" id="auth-code"
                                    placeholder="000000" maxlength="6" pattern="[0-9]{6}">
                                <div class="invalid-feedback" id="code-error"></div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary flex-fill"
                                    onclick="cancelVerify()">{{ __('Back') }}</button>
                                <button type="button" class="btn btn-primary flex-fill" onclick="verifyCode()">
                                    <span class="spinner-border spinner-border-sm d-none" id="verify-spinner"
                                        role="status"></span>
                                    {{ __('Confirm') }}
                                </button>
                            </div>
                        </div>

                        <div id="email-sms-section" class="d-none">
                            <p class="text-muted mb-4" id="email-sms-instructions">
                                {{ __('A verification code will be sent to your registered email/phone.') }}</p>

                            <div class="mb-3">
                                <label for="email-sms-code" class="form-label">{{ __('Verification Code') }}</label>
                                <input type="text" class="form-control text-center" id="email-sms-code"
                                    placeholder="000000" maxlength="6" pattern="[0-9]{6}">
                                <div class="invalid-feedback" id="email-sms-code-error"></div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary flex-fill"
                                    onclick="cancelVerify()">{{ __('Cancel') }}</button>
                                <button type="button" class="btn btn-primary flex-fill"
                                    onclick="verifyEmailSmsCode()">
                                    <span class="spinner-border spinner-border-sm d-none"
                                        id="email-sms-verify-spinner" role="status"></span>
                                    {{ __('Verify') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="mb-4">
            <span class="badge bg-success">{{ __('Enabled') }}</span>
            @if ($mfaHelper->method)
                <span class="badge bg-info ms-2" id="current-method-badge">
                    @if ($mfaHelper->method->value === 3)
                        {{ __('Google Authenticator') }}
                    @elseif($mfaHelper->method->value === 1)
                        {{ __('Email') }}
                    @elseif($mfaHelper->method->value === 2)
                        {{ __('SMS') }}
                    @endif
                </span>
            @endif
        </div>
        <p class="text-muted mb-4">
            {{ __('With two factor authentication enabled, you\'ll be prompted for a secure, random token during login, which you can retrieve from your Google Authenticator app, email, or SMS.') }}
        </p>


        @if (auth('admin')->user()->mfa_recovery_codes)
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">{{ __('2FA Recovery Codes') }}</h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        {{ __('Recovery codes let you regain access if you lose your 2FA device. Store them in a secure password manager.') }}
                    </p>

                    <div class="mb-3">
                        <button type="button" class="btn btn-sm btn-outline-primary"
                            onclick="toggleRecoveryCodes()">
                            <span id="toggle-codes-text">{{ __('View My Recovery Codes') }}</span>
                        </button>
                        <button type="button" class="btn btn-sm btn-primary ms-2 d-none" id="regenerate-btn"
                            onclick="regenerateCodes()">
                            <span class="spinner-border spinner-border-sm d-none" id="regenerate-spinner"
                                role="status"></span>
                            {{ __('Regenerate Codes') }}
                        </button>
                    </div>

                    <div id="recovery-codes-container" class="d-none">
                        <div class="bg-light rounded p-3 mb-3">
                            <div class="row g-2" id="recovery-codes-list">
                                @if (auth('admin')->user()->mfa_recovery_codes)
                                    @foreach (json_decode(decrypt(auth('admin')->user()->mfa_recovery_codes), true) as $code)
                                        <div class="col-6 col-md-4">
                                            <code class="d-block">{{ $code }}</code>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <p class="text-muted small mb-0">
                            {{ __('You have') }}
                            <span
                                id="codes-count">{{ auth('admin')->user()->mfa_recovery_codes ? count(json_decode(decrypt(auth('admin')->user()->mfa_recovery_codes), true)) : 0 }}</span>
                            {{ __('recovery codes left.') }}
                            {{ __('Each can be used once to access your account and will be removed after use.') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <button type="button" class="btn btn-danger" onclick="disable2FA()">
            <span class="spinner-border spinner-border-sm d-none" id="disable-spinner" role="status"></span>
            {{ __('Disable 2FA') }}
        </button>
    @endif
</div>

@push('scripts')
    <script>
        const mfaUrl = '{{ route('admin.profile.two-factor-authentication') }}';
        let currentMethod = 3;
        let showRecoveryCodes = false;

        function enable2FA() {
            const method = document.querySelector('input[name="mfa_method"]:checked').value;
            currentMethod = parseInt(method);
            const btn = document.getElementById('enable-2fa-btn');
            const spinner = document.getElementById('enable-spinner');

            btn.disabled = true;
            spinner.classList.remove('d-none');

            axios.post(mfaUrl, {
                    action: 'enable',
                    method: method
                })
                .then(response => {
                    if (response.data.success) {
                        if (currentMethod === 3) {
                            // Google Authenticator - show QR code
                            document.getElementById('qr-code-section').classList.remove('d-none');
                            document.getElementById('verify-code-section').classList.add('d-none');
                            document.getElementById('email-sms-section').classList.add('d-none');

                            if (response.data.qr) {
                                document.getElementById('qr-image').src = 'data:image/svg+xml;base64,' + response.data
                                    .qr;
                                document.getElementById('qr-image').classList.remove('d-none');
                                document.getElementById('qr-spinner').classList.add('d-none');
                            }

                            if (response.data.secret) {
                                document.getElementById('secret-key').value = response.data.secret;
                            }
                        } else {
                            // Email or SMS - send code and show input
                            document.getElementById('qr-code-section').classList.add('d-none');
                            document.getElementById('verify-code-section').classList.add('d-none');
                            document.getElementById('email-sms-section').classList.remove('d-none');

                            const methodName = currentMethod === 1 ? 'email' : 'SMS';
                            document.getElementById('email-sms-instructions').textContent =
                                `{{ __('A verification code has been sent to your') }} ${methodName}. {{ __('Please enter it below.') }}`;
                        }

                        if (document.readyState === 'complete') {
                            const modal = new bootstrap.Modal(document.getElementById('verifyModal'));
                            modal.show();
                        } else {
                            window.addEventListener('load', function() {
                                const modal = new bootstrap.Modal(document.getElementById('verifyModal'));
                                modal.show();
                            });
                        }
                    } else {
                        alert(response.data.message || '{{ __('Failed to enable 2FA') }}');
                    }
                })
                .catch(error => {
                    console.error(error);
                    alert(error.response?.data?.message || '{{ __('An error occurred') }}');
                })
                .finally(() => {
                    btn.disabled = false;
                    spinner.classList.add('d-none');
                });
        }

        function setVerifyMode() {
            if (currentMethod === 3) {
                document.getElementById('qr-code-section').classList.add('d-none');
                document.getElementById('verify-code-section').classList.remove('d-none');
                document.getElementById('modal-title-text').textContent = '{{ __('Verify Authentication Code') }}';
                document.getElementById('verify-instructions').textContent =
                    '{{ __('Enter the 6-digit code from your authenticator app') }}';
            }
        }

        function cancelVerify() {
            axios.post(mfaUrl, {
                    action: 'cancel'
                })
                .then(() => {
                    if (document.readyState === 'complete') {
                        const modalElement = document.getElementById('verifyModal');
                        let modal = bootstrap.Modal.getInstance(modalElement);
                        if (!modal) {
                            modal = new bootstrap.Modal(modalElement);
                        }
                        modal.hide();
                        location.reload();
                    } else {
                        window.addEventListener('load', function() {
                            const modalElement = document.getElementById('verifyModal');
                            let modal = bootstrap.Modal.getInstance(modalElement);
                            if (!modal) {
                                modal = new bootstrap.Modal(modalElement);
                            }
                            modal.hide();
                            location.reload();
                        });
                    }
                });
        }

        function verifyCode() {
            const code = document.getElementById('auth-code').value;
            const btn = document.querySelector('#verify-code-section .btn-primary');
            const spinner = document.getElementById('verify-spinner');
            const codeInput = document.getElementById('auth-code');
            const errorDiv = document.getElementById('code-error');

            if (!code || code.length !== 6) {
                codeInput.classList.add('is-invalid');
                errorDiv.textContent = '{{ __('Please enter a valid 6-digit code') }}';
                return;
            }

            btn.disabled = true;
            spinner.classList.remove('d-none');
            codeInput.classList.remove('is-invalid');

            axios.post(mfaUrl, {
                    action: 'verify',
                    code: code,
                    method: currentMethod
                })
                .then(response => {
                    if (response.data.success) {
                        alert(response.data.message || '{{ __('2FA enabled successfully!') }}');
                        location.reload();
                    } else {
                        codeInput.classList.add('is-invalid');
                        errorDiv.textContent = response.data.message || '{{ __('Invalid verification code') }}';
                    }
                })
                .catch(error => {
                    codeInput.classList.add('is-invalid');
                    errorDiv.textContent = error.response?.data?.message || '{{ __('An error occurred') }}';
                })
                .finally(() => {
                    btn.disabled = false;
                    spinner.classList.add('d-none');
                });
        }

        function verifyEmailSmsCode() {
            const code = document.getElementById('email-sms-code').value;
            const btn = document.querySelector('#email-sms-section .btn-primary');
            const spinner = document.getElementById('email-sms-verify-spinner');
            const codeInput = document.getElementById('email-sms-code');
            const errorDiv = document.getElementById('email-sms-code-error');

            if (!code || code.length !== 6) {
                codeInput.classList.add('is-invalid');
                errorDiv.textContent = '{{ __('Please enter a valid 6-digit code') }}';
                return;
            }

            btn.disabled = true;
            spinner.classList.remove('d-none');
            codeInput.classList.remove('is-invalid');

            axios.post(mfaUrl, {
                    action: 'verify',
                    code: code,
                    method: currentMethod
                })
                .then(response => {
                    if (response.data.success) {
                        alert(response.data.message || '{{ __('2FA enabled successfully!') }}');
                        location.reload();
                    } else {
                        codeInput.classList.add('is-invalid');
                        errorDiv.textContent = response.data.message || '{{ __('Invalid verification code') }}';
                    }
                })
                .catch(error => {
                    codeInput.classList.add('is-invalid');
                    errorDiv.textContent = error.response?.data?.message || '{{ __('An error occurred') }}';
                })
                .finally(() => {
                    btn.disabled = false;
                    spinner.classList.add('d-none');
                });
        }

        function disable2FA() {
            if (!confirm('{{ __('Are you sure you want to disable 2FA? This will make your account less secure.') }}')) {
                return;
            }

            const btn = document.querySelector('.btn-danger');
            const spinner = document.getElementById('disable-spinner');

            btn.disabled = true;
            spinner.classList.remove('d-none');

            axios.post(mfaUrl, {
                    action: 'disable'
                })
                .then(response => {
                    if (response.data.success) {
                        alert(response.data.message || '{{ __('2FA disabled successfully') }}');
                        location.reload();
                    } else {
                        alert(response.data.message || '{{ __('Failed to disable 2FA') }}');
                    }
                })
                .catch(error => {
                    alert(error.response?.data?.message || '{{ __('An error occurred') }}');
                })
                .finally(() => {
                    btn.disabled = false;
                    spinner.classList.add('d-none');
                });
        }

        function toggleRecoveryCodes() {
            showRecoveryCodes = !showRecoveryCodes;
            const container = document.getElementById('recovery-codes-container');
            const toggleText = document.getElementById('toggle-codes-text');
            const regenerateBtn = document.getElementById('regenerate-btn');

            if (showRecoveryCodes) {
                container.classList.remove('d-none');
                toggleText.textContent = '{{ __('Hide Recovery Codes') }}';
                regenerateBtn.classList.remove('d-none');
            } else {
                container.classList.add('d-none');
                toggleText.textContent = '{{ __('View My Recovery Codes') }}';
                regenerateBtn.classList.add('d-none');
            }
        }

        function regenerateCodes() {
            if (!confirm(
                    '{{ __('Are you sure you want to regenerate recovery codes? Your old codes will no longer work.') }}'
                    )) {
                return;
            }

            const btn = document.getElementById('regenerate-btn');
            const spinner = document.getElementById('regenerate-spinner');

            btn.disabled = true;
            spinner.classList.remove('d-none');

            axios.post(mfaUrl, {
                    action: 'regenerate_codes'
                })
                .then(response => {
                    if (response.data.success) {
                        const codesList = document.getElementById('recovery-codes-list');
                        codesList.innerHTML = '';
                        response.data.codes.forEach(code => {
                            const col = document.createElement('div');
                            col.className = 'col-6 col-md-4';
                            col.innerHTML = `<code class="d-block">${code}</code>`;
                            codesList.appendChild(col);
                        });
                        document.getElementById('codes-count').textContent = response.data.codes.length;
                        alert(response.data.message || '{{ __('Recovery codes regenerated successfully') }}');
                    } else {
                        alert(response.data.message || '{{ __('Failed to regenerate codes') }}');
                    }
                })
                .catch(error => {
                    alert(error.response?.data?.message || '{{ __('An error occurred') }}');
                })
                .finally(() => {
                    btn.disabled = false;
                    spinner.classList.add('d-none');
                });
        }

        function copySecretKey() {
            const secretInput = document.getElementById('secret-key');
            secretInput.select();
            document.execCommand('copy');

            const copyBtn = document.getElementById('copy-btn');
            const copyIcon = document.getElementById('copy-icon');
            copyIcon.textContent = '✓';
            copyBtn.classList.add('btn-success');
            copyBtn.classList.remove('btn-outline-secondary');

            setTimeout(() => {
                copyIcon.textContent = '📋';
                copyBtn.classList.remove('btn-success');
                copyBtn.classList.add('btn-outline-secondary');
            }, 2000);
        }

        // Auto-focus code inputs
        document.addEventListener('DOMContentLoaded', function() {
            const authCodeInput = document.getElementById('auth-code');
            const emailSmsCodeInput = document.getElementById('email-sms-code');

            if (authCodeInput) {
                authCodeInput.addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^0-9]/g, '');
                    if (this.value.length === 6) {
                        verifyCode();
                    }
                });
            }

            if (emailSmsCodeInput) {
                emailSmsCodeInput.addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^0-9]/g, '');
                    if (this.value.length === 6) {
                        verifyEmailSmsCode();
                    }
                });
            }
        });
    </script>
@endpush
