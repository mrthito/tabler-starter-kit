<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\CommissionTypeController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\TransactionCategoryController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\StorageSettingController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\PostCategoryController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth:admin', 'verified:admin.verification.notice', '2fa:admin'])
    ->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::resource('roles', RoleController::class);
        Route::get('/roles/export/{filetype}', [RoleController::class, 'show'])->name('roles.export');

        Route::resource('posts', PostController::class);

        Route::resource('post-categories', PostCategoryController::class);

        Route::resource('pages', PageController::class);

        Route::resource('plans', PlanController::class);

        Route::resource('coupons', CouponController::class);

        // Commission Type Settings (Singleton)
        Route::get('commission-types/edit', [CommissionTypeController::class, 'edit'])->name('commission-types.edit');
        Route::put('commission-types', [CommissionTypeController::class, 'update'])->name('commission-types.update');

        Route::resource('transaction-categories', TransactionCategoryController::class);
        Route::resource('transactions', TransactionController::class);

        // Storage Settings (Singleton)
        Route::get('storage-settings/edit', [StorageSettingController::class, 'edit'])->name('storage-settings.edit');
        Route::put('storage-settings', [StorageSettingController::class, 'update'])->name('storage-settings.update');

        Route::resource('admins', AdminController::class);
        Route::get('/admins/export/{filetype}', [AdminController::class, 'export'])->name('admins.export');

        Route::resource('users', UserController::class);
        Route::get('/users/export/{filetype}', [UserController::class, 'export'])->name('users.export');

        Route::get('media/list', [MediaController::class, 'list'])->name('media.list');
        Route::get('media/folders', [MediaController::class, 'folders'])->name('media.folders');
        Route::resource('media', MediaController::class)->names('media');

        Route::resource('notifications', NotificationController::class)->only(['index', 'update', 'destroy']);
        Route::get('notifications/unread/count', [NotificationController::class, 'unreadCount'])->name('notifications.unread.count');

        Route::controller(ProfileController::class)->group(function () {
            Route::get('/profile', 'edit')->name('profile.edit');
            Route::patch('/profile', 'update')->name('profile.update');
            Route::delete('/profile', 'destroy')->name('profile.destroy');
            Route::post('/profile/two-factor-authentication', 'twoFactorAuthentication')->name('profile.two-factor-authentication');
        });

        // Appearance > Menus
        Route::prefix('appearance/menus')->name('appearance.menus.')->group(function () {
            Route::get('/', [MenuController::class, 'index'])->name('index');
            Route::get('/create', [MenuController::class, 'create'])->name('create');
            Route::post('/', [MenuController::class, 'store'])->name('store');
            Route::get('/{menu}', [MenuController::class, 'show'])->name('show');
            Route::get('/{menu}/edit', [MenuController::class, 'edit'])->name('edit');
            Route::patch('/{menu}', [MenuController::class, 'update'])->name('update');
            Route::delete('/{menu}', [MenuController::class, 'destroy'])->name('destroy');

            // Menu items
            Route::post('/{menu}/items', [MenuController::class, 'storeItem'])->name('items.store');
            Route::patch('/{menu}/items/{menuItem}', [MenuController::class, 'updateItem'])->name('items.update');
            Route::delete('/{menu}/items/{menuItem}', [MenuController::class, 'destroyItem'])->name('items.destroy');
            Route::post('/{menu}/items/reorder', [MenuController::class, 'reorderItems'])->name('items.reorder');
            Route::post('/{menu}/items/{menuItem}/link', [MenuController::class, 'linkToModel'])->name('items.link');
        });
    });
