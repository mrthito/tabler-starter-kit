<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth:admin', 'verified:admin.verification.notice', '2fa:admin'])
    ->group(function () {
        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('dashboard');

    Route::resource('roles', RoleController::class);
    Route::get('/roles/export/{filetype}', [RoleController::class, 'show'])->name('roles.export');

    Route::resource('admins', AdminController::class);
    Route::get('/admins/export/{filetype}', [AdminController::class, 'export'])->name('admins.export');

    Route::resource('users', UserController::class);
    Route::get('/users/export/{filetype}', [UserController::class, 'export'])->name('users.export');

        Route::controller(ProfileController::class)->group(function () {
            Route::get('/profile', 'edit')->name('profile.edit');
            Route::patch('/profile', 'update')->name('profile.update');
            Route::delete('/profile', 'destroy')->name('profile.destroy');
            Route::post('/profile/two-factor-authentication', 'twoFactorAuthentication')->name('profile.two-factor-authentication');
        });
    });
