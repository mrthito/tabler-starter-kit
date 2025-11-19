<?php

namespace App\Actions\MFA;

class DisableTwoFactorAuthentication
{
    /**
     * Disable two factor authentication for the user.
     *
     * @return void
     */
    public function __invoke($user)
    {
        if (
            ! is_null($user->mfa_secret) ||
            ! is_null($user->mfa_recovery_codes) ||
            $user->mfa_enabled == 1
        ) {
            $user->forceFill([
                'mfa_secret' => null,
                'mfa_recovery_codes' => null,
                'mfa_enabled' => 0,
            ])->save();
        }
    }
}