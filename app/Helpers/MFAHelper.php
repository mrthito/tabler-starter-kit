<?php

namespace App\Helpers;

use App\Actions\MFA\DisableTwoFactorAuthentication;
use App\Actions\MFA\GenerateQrCodeAndSecretKey;
use App\Actions\MFA\GenerateNewRecoveryCodes;
use App\Actions\MFA\VerifyTwoFactorCode;
use App\Actions\MFA\ConfirmTwoFactorAuthentication;

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

    public function __construct(private $user)
    {
        // dd($this->user->mfa_enabled);
        if ($this->user->mfa_enabled == 0) {
            app(DisableTwoFactorAuthentication::class)($this->user);
        } else {
            $this->confirmed = true;
            $this->showRecoveryCodes = false;
        }
    }
    public function enable()
    {
        [$this->qr, $this->secret] = app(GenerateQrCodeAndSecretKey::class)($this->user);

        $this->user->forceFill([
            'mfa_secret' => encrypt($this->secret),
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
    }

    public function submitCode($code)
    {
        $this->auth_code = $code;
        $valid = app(VerifyTwoFactorCode::class)($this->secret, $code);
        if ($valid) {
            app(ConfirmTwoFactorAuthentication::class)($this->user);
            $this->confirmed = true;
        } else {
        }
    }

    public function disable()
    {
        app(DisableTwoFactorAuthentication::class)($this->user);
        $this->enabled = false;
        $this->confirmed = false;
    }

    public function verify2fa()
    {
        $this->verify = true;
    }
}
