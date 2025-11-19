<?php

namespace App\Actions\MFA;

class ConfirmTwoFactorAuthentication
{
    /**
     * Confirm two factor authentication for the user.
     *
     * @param mixed $user
     * @return bool
     */
    public function __invoke($user)
    {
        $user->forceFill([
            'mfa_enabled' => 1,
        ])->save();

        return true;
    }
}
