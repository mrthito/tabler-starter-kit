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

    public function twoFactorAuthentication(Request $request) {}
}
