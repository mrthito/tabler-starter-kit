<?php

namespace App\Actions\MFA;

use PragmaRX\Google2FA\Google2FA;

class VerifyTwoFactorCode
{
    /**
     * Verify a two-factor authentication code.
     *
     * @param  string  $secret The decrypted secret key
     * @param  string  $code The code to verify
     * @return bool
     */
    public function __invoke(
        #[\SensitiveParameter] string $secret,
        #[\SensitiveParameter] string $code
    ): bool {
        // Clean the code: remove spaces and ensure it's numeric
        $code = preg_replace('/[^0-9]/', '', $code);

        // Ensure code is exactly 6 digits
        if (strlen($code) !== 6) {
            return false;
        }

        $google2fa = new Google2FA();

        // Use a window of 4 to allow for clock skew (checks current time ± 4 time steps)
        // This accounts for devices that might be slightly out of sync
        return $google2fa->verifyKey($secret, $code, 4);
    }
}
