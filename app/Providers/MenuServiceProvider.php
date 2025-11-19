<?php

namespace App\Providers;

use App\Facades\Menu;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register Menu as singleton
        $this->app->singleton(\App\Helpers\Menu::class, function ($app) {
            return new \App\Helpers\Menu;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerMenus();
    }

    /**
     * Register admin menus
     */
    protected function registerMenus()
    {
        // Dashboard
        Menu::name('admin')->create('dashboard', 'Dashboard', 'admin.dashboard', 'home')
            ->position(1)
            ->permissions('admin.dashboard');

        // Roles
        Menu::name('admin')->create('roles', 'Roles', '/admin/roles', 'users')
            ->position(2)
            ->permissions('admin.roles.view')
            ->add('All Roles', 'admin.roles.index', '', 1)
            ->add('Create Role', 'admin.roles.create', '', 2);

        // Products
        Menu::name('admin')->create('users', 'Users', '#', 'users')
            ->position(4)
            ->permissions(['admin.users.view', 'admin.users.create'])
            ->add('Admins', 'admin.admins.index', '', 1)
            ->add('Users', 'admin.users.index', '', 3);

        // Pages
        Menu::name('admin')->create('pages', 'Pages', '#', 'file')
            ->position(6)
            ->permissions(['admin.pages.view', 'admin.pages.create'])
            ->add('All Pages', 'admin.pages.index', '', 1)
            ->add('Create Page', 'admin.pages.create', '', 2);

        //Appearance
        Menu::name('admin')->create('appearance', 'Appearance', '#', 'palette')
            ->position(12)
            ->permissions('admin.appearance')
            ->add('Themes', 'admin.appearance.themes.index', '', 1)
            ->add('Menus', 'admin.appearance.menus.index', '', 2)
            ->add('Widgets', 'admin.appearance.widgets.index', '', 3);

        // Plugins
        Menu::name('admin-right')->create('plugins', 'Plugins', 'admin.plugins.index', 'plug')
            ->position(13)
            ->permissions('admin.plugins');

        // Settings
        Menu::name('admin')->create('settings', 'Settings', '#', 'settings')
            ->position(25)
            ->permissions('admin.settings')
            ->add('General Settings', 'admin.settings.general.index', '', 1)
            ->add('Email Settings', 'admin.settings.email.index', '', 2)
            ->add('Payment Settings', 'admin.settings.payment.index', '', 3)
            ->add('SMS Settings', 'admin.settings.sms.index', '', 4)
            ->add('Languages', 'admin.settings.languages.index', '', 5)
            ->add('Currency', 'admin.settings.currency.index', '', 6)
            ->add('Vat & TAX', 'admin.settings.tax.index', '', 7)
            ->add('Order Settings', 'admin.settings.order-settings.index', '', 9)
            ->add('Cache Settings', 'admin.settings.cache-settings.index', '', 10)
            ->add('File Settings', 'admin.settings.file-settings.index', '', 11)
            ->add('SSO Settings', 'admin.settings.sso-settings.index', '', 12)
            ->add('Meta Settings', 'admin.settings.meta-settings.index', '', 13)
            ->add('Google Settings', 'admin.settings.google-settings.index', '', 14)
            ->add('Shipping Settings', 'admin.settings.shipping-settings.index', '', 15);
    }
}
