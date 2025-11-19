<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\MFAHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the admin's profile form.
     */
    public function edit(Request $request): View
    {
        $mfaHelper = new MFAHelper($request->user('admin'));
        return view('admin.profile.edit', [
            'tab' => $request->query('tab', 'profile'),
            'admin' => $request->user('admin'),
            'mfaHelper' => $mfaHelper,
        ]);
    }

    /**
     * Update the admin's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $admin = $request->user('admin');

        if ($request->mode === 'avatar') {
            $avatar = $request->file('avatar');
            $name = $avatar->store('admin/avatars');
            $admin->profile_picture_path = $name;
            $admin->save();

            return Redirect::route('admin.profile.edit')->with('status', 'avatar-updated');
        }

        $admin->fill($request->validated());

        if ($request->has('email')) {
            if ($admin->isDirty('email')) {
                $admin->email_verified_at = null;
            }
        }

        $admin->save();

        return Redirect::route('admin.profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the admin's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('adminDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $admin = $request->user('admin');

        Auth::guard('admin')->logout();

        $admin->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::route('admin.login')->with('status', 'admin-deleted');
    }

    public function twoFactorAuthentication(Request $request)
    {
        $admin = $request->user('admin');
        $mfaHelper = new MFAHelper($admin);
        $action = $request->input('action');

        try {
            switch ($action) {
                case 'enable':
                    $method = $request->input('method');
                    if (!in_array($method, [1, 2, 3])) {
                        return response()->json(['success' => false, 'message' => 'Invalid MFA method'], 400);
                    }
                    $mfaHelper->enable($method);

                    // Send code for email/SMS methods
                    if (in_array($method, [1, 2])) {
                        // Refresh the admin model to ensure we have the latest data
                        $admin->refresh();
                        $admin->sendTwoFactorAuthCode();
                    }

                    return response()->json([
                        'success' => true,
                        'enabled' => $mfaHelper->enabled,
                        'qr' => $mfaHelper->qr,
                        'secret' => $mfaHelper->secret,
                        'method' => $method,
                    ]);

                case 'verify':
                    $code = $request->input('code');
                    $method = $request->input('method');
                    if (empty($code)) {
                        return response()->json(['success' => false, 'message' => 'Code is required'], 400);
                    }
                    $mfaHelper->submitCode($code, $method);
                    if ($mfaHelper->confirmed) {
                        return response()->json([
                            'success' => true,
                            'confirmed' => true,
                            'message' => 'Two-factor authentication enabled successfully',
                        ]);
                    }
                    return response()->json(['success' => false, 'message' => 'Invalid verification code'], 400);

                case 'disable':
                    $mfaHelper->disable();
                    return response()->json([
                        'success' => true,
                        'message' => 'Two-factor authentication disabled successfully',
                    ]);

                case 'regenerate_codes':
                    $mfaHelper->regenerateCodes();
                    $codes = json_decode(decrypt($admin->fresh()->mfa_recovery_codes), true);
                    return response()->json([
                        'success' => true,
                        'codes' => $codes,
                        'message' => 'Recovery codes regenerated successfully',
                    ]);

                case 'set_verify':
                    $mfaHelper->verify2fa();
                    return response()->json([
                        'success' => true,
                        'verify' => $mfaHelper->verify,
                    ]);

                case 'cancel':
                    $mfaHelper->cancelTwoFactor();
                    return response()->json([
                        'success' => true,
                        'enabled' => false,
                    ]);

                default:
                    return response()->json(['success' => false, 'message' => 'Invalid action'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
