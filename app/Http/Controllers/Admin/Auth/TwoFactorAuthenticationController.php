<?php

namespace App\Http\Controllers\Admin\Auth;

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
        return view('admin.auth.two-factor-auth');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|array',
            'code.*' => 'required|numeric',
        ]);

        $user = Auth::guard('admin')->user();
        if (!$user->verifyTwoFactorAuthCode(implode('', $request->input('code', [])))) {
            return back()->with('error', __('Invalid two factor authentication code.'));
        }

        $request->session()->regenerate();

        Session::put('two_factor_authenticated', true);

        return redirect()->intended(route('admin.dashboard', absolute: false))->with('success', __('Two factor authentication successful.'));
    }

    public function resend(Request $request): RedirectResponse
    {
        $user = Auth::guard('admin')->user();
        $user->sendTwoFactorAuthCode();

        return back()->with('success', __('Code resend successfully.'));
    }
}
