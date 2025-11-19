<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Enums\MFAMethods;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        $showRegister = Admin::count() === 0;
        $socialiteEnabled = config('services.socialite.enabled', false);
        return view('admin.auth.login', compact('showRegister', 'socialiteEnabled'));
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate('admin');

        $user = Auth::guard('admin')->user();
        if ($user->twoFactorAuthEnabled()) {
            $mfaMethod = MFAMethods::from($user->mfa_method);

            // Only send codes for Email and SMS methods
            // Google Authenticator users will enter code from their app
            if (in_array($mfaMethod, [MFAMethods::EMAIL, MFAMethods::SMS])) {
                $user->sendTwoFactorAuthCode();
            }

            return redirect()->route('admin.login.2fa');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        // Clear 2FA session
        Session::forget('two_factor_authenticated_admin');

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(route('admin.login', absolute: false));
    }
}
