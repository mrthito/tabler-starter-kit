<?php

namespace App\Providers;

use App\Facades\Menu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::shouldBeStrict();

        // create view namespace called theme
        $theme = config('app.theme');
        View::addNamespace('theme', resource_path('views/themes/' . $theme));

        if (config('app.debug', false)) {
            Menu::bypassPermissions(true);
        }

        // Register Blade directives
        $this->registerBladeDirectives();
    }

    /**
     * Register custom Blade directives
     */
    protected function registerBladeDirectives()
    {
        Blade::directive('menu', function ($expression) {
            if (empty($expression)) {
                return abort(403, 'Menu directive requires both menu name and view parameters');
            }

            // Parse the expression to extract parameters
            $params = explode(',', trim($expression, '()'));

            // Extract menu name and view - both are required
            $menuName = isset($params[0]) ? trim($params[0], ' "\'') : null;
            $view = isset($params[1]) ? trim($params[1], ' "\'') : null;

            if (empty($menuName) || empty($view)) {
                return abort(403, 'Menu directive requires both menu name and view parameters');
            }

            return "<?php echo \App\Facades\Menu::renderGroup('$menuName', '$view'); ?>";
        });
    }
}
