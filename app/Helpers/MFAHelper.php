<?php

namespace App\Helpers;

use App\Actions\MFA\DisableTwoFactorAuthentication;
use App\Actions\MFA\GenerateQrCodeAndSecretKey;
use App\Actions\MFA\GenerateNewRecoveryCodes;
use App\Actions\MFA\VerifyTwoFactorCode;
use App\Actions\MFA\ConfirmTwoFactorAuthentication;
use App\Enums\MFAMethods;

class MFAHelper
{
    public $enabled = false;
    public $confirmed = false;
    public $auth_code;
    public $secret = '';
    public $codes = '';
    public $qr = '';
    public $verify = false;
    public $showRecoveryCodes = true;
    public $method = MFAMethods::NONE;

    public function __construct(private $user)
    {
        if ($this->user->mfa_enabled == 0) {
            app(DisableTwoFactorAuthentication::class)($this->user);
        } else {
            $this->confirmed = true;
            $this->showRecoveryCodes = false;
            $this->method = MFAMethods::from($this->user->mfa_method);
        }
    }

    public function enable($method = MFAMethods::GOOGLE_AUTHENTICATOR)
    {
        $this->method = MFAMethods::from($method);

        // Only generate QR code and secret for Google Authenticator
        if ($this->method === MFAMethods::GOOGLE_AUTHENTICATOR) {
            [$this->qr, $this->secret] = app(GenerateQrCodeAndSecretKey::class)($this->user);
        } else {
            $this->qr = '';
            $this->secret = '';
        }

        $this->user->forceFill([
            'mfa_method' => $this->method->value,
            'mfa_secret' => $this->method === MFAMethods::GOOGLE_AUTHENTICATOR ? encrypt($this->secret) : null,
            'mfa_recovery_codes' => encrypt(json_encode($this->generateCodes()))
        ])->save();
        $this->enabled = true;
    }

    private function generateCodes()
    {
        return app(GenerateNewRecoveryCodes::class)();
    }

    public function regenerateCodes()
    {
        $this->user->forceFill([
            'mfa_recovery_codes' => encrypt(json_encode($this->generateCodes()))
        ])->save();
    }

    public function cancelTwoFactor()
    {
        app(DisableTwoFactorAuthentication::class)($this->user);
        $this->enabled = false;
        $this->verify = false;
    }

    public function submitCode($code, $method = null)
    {
        $this->auth_code = $code;
        $valid = false;

        // Refresh user to get latest data
        $this->user->refresh();

        // Set method from parameter or from user's saved method
        if ($method !== null) {
            $this->method = MFAMethods::from($method);
        } elseif ($this->user->mfa_method) {
            $this->method = MFAMethods::from($this->user->mfa_method);
        }

        if ($this->method === MFAMethods::GOOGLE_AUTHENTICATOR) {
            // For Google Authenticator, verify using the secret
            if ($this->user->mfa_secret) {
                $decryptedSecret = decrypt($this->user->mfa_secret);
                $valid = app(VerifyTwoFactorCode::class)($decryptedSecret, $code);
            }
        } elseif ($this->method === MFAMethods::EMAIL) {
            // For Email, verify using the user's method
            $valid = $this->user->verifyTwoFactorAuthCode($code);
        } elseif ($this->method === MFAMethods::SMS) {
            // For SMS, verify using the user's method (similar to email for now)
            $valid = $this->user->verifyTwoFactorAuthCode($code);
        }

        if ($valid) {
            $this->user->forceFill([
                'mfa_enabled' => true,
            ])->save();
            app(ConfirmTwoFactorAuthentication::class)($this->user);
            $this->confirmed = true;
        }

        return $valid;
    }

    public function disable()
    {
        app(DisableTwoFactorAuthentication::class)($this->user);
        $this->enabled = false;
        $this->confirmed = false;
        $this->method = MFAMethods::NONE;
    }

    public function verify2fa()
    {
        $this->verify = true;
    }
}
