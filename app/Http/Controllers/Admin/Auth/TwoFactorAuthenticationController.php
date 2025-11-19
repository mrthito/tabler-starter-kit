<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Actions\MFA\VerifyTwoFactorCode;
use App\Enums\MFAMethods;
use App\Models\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TwoFactorAuthenticationController extends Controller
{
    public function create(): View
    {
        $user = Auth::guard('admin')->user();
        $mfaMethod = MFAMethods::from($user->mfa_method);

        $instructions = match ($mfaMethod) {
            MFAMethods::GOOGLE_AUTHENTICATOR => __('Enter the 6-digit code from your authenticator app.'),
            MFAMethods::EMAIL => __('Enter the verification code sent to your email address.'),
            MFAMethods::SMS => __('Enter the verification code sent to your phone.'),
            default => __('Enter the verification code.'),
        };

        return view('admin.auth.two-factor-auth', [
            'mfaMethod' => $mfaMethod,
            'instructions' => $instructions,
            'showResend' => in_array($mfaMethod, [MFAMethods::EMAIL, MFAMethods::SMS]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|array',
            'code.*' => 'required|string',
        ]);

        $user = Auth::guard('admin')->user();
        $codeInput = $request->input('code', []);

        // Handle both 6-digit code format and recovery code format
        // If it's a recovery code, it might be pasted in one field or split
        $code = implode('', $codeInput);

        // Check if it looks like a recovery code (contains dash and is longer)
        if (strlen($code) > 10 && str_contains($code, '-')) {
            // It's a recovery code - use as is
            $code = $code;
        } else {
            // It's a 6-digit code - ensure it's numeric
            $code = preg_replace('/[^0-9]/', '', $code);
            if (strlen($code) !== 6) {
                return back()->with('error', __('Please enter a valid 6-digit code or recovery code.'));
            }
        }

        $mfaMethod = MFAMethods::from($user->mfa_method);

        $valid = false;

        // Try recovery codes first (they work for all methods)
        if ($this->verifyRecoveryCode($user, $code)) {
            $valid = true;
        } elseif ($mfaMethod === MFAMethods::GOOGLE_AUTHENTICATOR) {
            // Verify Google Authenticator code
            if (empty($user->mfa_secret)) {
                return back()->with('error', __('MFA secret not found. Please contact support.'));
            }

            try {
                $decryptedSecret = decrypt($user->mfa_secret);
                $valid = app(VerifyTwoFactorCode::class)($decryptedSecret, $code);
            } catch (\Exception $e) {
                return back()->with('error', __('Failed to verify code. Please try again.'));
            }
        } elseif (in_array($mfaMethod, [MFAMethods::EMAIL, MFAMethods::SMS])) {
            // Verify Email/SMS code
            /** @var Admin $user */
            $valid = $user->verifyTwoFactorAuthCode($code);
        }

        if (!$valid) {
            return back()->with('error', __('Invalid two factor authentication code.'));
        }

        $request->session()->regenerate();

        // Use guard-specific session key
        Session::put('two_factor_authenticated_admin', true);

        return redirect()->intended(route('admin.dashboard', absolute: false))->with('success', __('Two factor authentication successful.'));
    }

    public function resend(Request $request): RedirectResponse
    {
        $user = Auth::guard('admin')->user();
        $mfaMethod = MFAMethods::from($user->mfa_method);

        // Only allow resend for Email and SMS methods
        if (!in_array($mfaMethod, [MFAMethods::EMAIL, MFAMethods::SMS])) {
            return back()->with('error', __('Resend is only available for email and SMS methods.'));
        }

        /** @var Admin $user */
        $user->sendTwoFactorAuthCode();

        return back()->with('success', __('Code resend successfully.'));
    }

    /**
     * Verify a recovery code.
     */
    private function verifyRecoveryCode($user, string $code): bool
    {
        if (empty($user->mfa_recovery_codes)) {
            return false;
        }

        try {
            $recoveryCodes = json_decode(decrypt($user->mfa_recovery_codes), true);

            if (!is_array($recoveryCodes)) {
                return false;
            }

            // Normalize the code (remove extra dashes/spaces, convert to uppercase)
            $code = strtoupper(trim($code));
            $code = preg_replace('/[^A-Z0-9-]/', '', $code);

            // Check if the code matches any recovery code (case-insensitive)
            $codeIndex = false;
            foreach ($recoveryCodes as $index => $recoveryCode) {
                $normalizedRecoveryCode = strtoupper(trim($recoveryCode));
                if ($normalizedRecoveryCode === $code) {
                    $codeIndex = $index;
                    break;
                }
            }

            if ($codeIndex === false) {
                return false;
            }

            // Remove the used recovery code
            unset($recoveryCodes[$codeIndex]);
            $recoveryCodes = array_values($recoveryCodes); // Re-index array

            // Update the recovery codes
            $user->forceFill([
                'mfa_recovery_codes' => encrypt(json_encode($recoveryCodes))
            ])->save();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
