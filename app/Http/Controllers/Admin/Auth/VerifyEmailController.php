<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated admin's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $admin = $request->user('admin');
        if ($admin->hasVerifiedEmail()) {
            return redirect()->intended(route('admin.dashboard', absolute: false) . '?verified=1');
        }

        if ($admin->markEmailAsVerified()) {
            event(new Verified($admin));
        }

        return redirect()->intended(route('admin.dashboard', absolute: false) . '?verified=1');
    }
}
