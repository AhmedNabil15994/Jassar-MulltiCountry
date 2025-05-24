<?php

namespace Modules\Apps\Providers;

use Illuminate\Support\Facades\Blade;
use Modules\Apps\Components\Dukaan\Home\Categories;
use Modules\Apps\Components\Dukaan\Home\Products;
use Modules\Apps\Components\Dukaan\Layouts\Footer;
use Modules\Apps\Components\Dukaan\Layouts\Head;
use Modules\Apps\Components\Dukaan\Layouts\Header;
use Modules\Apps\Components\Dukaan\Layouts\HeaderMenu\{HeaderMenu,NstedMenu,FinalBuilder,CategoryList};
use Modules\Apps\Components\Dukaan\Layouts\HeaderSearch;
use Modules\Apps\Components\Dukaan\Layouts\HeaderCart;
use Modules\Apps\Components\Dukaan\Home\HomeBuilder;
use Modules\Apps\Components\Dukaan\Home\HomeSection;
use Modules\Apps\Components\Dukaan\Layouts\Js;
use Modules\Apps\Components\Dukaan\Layouts\Scripts;
use Illuminate\Support\ServiceProvider;
use Modules\Apps\Components\Dukaan\Layouts\SiteColors;
use Modules\Apps\Components\Dukaan\Loaders\BtnLoader;
use Modules\Apps\Components\Dukaan\Loaders\BallsLoader;
use Modules\Apps\Components\Dukaan\Loaders\SppinerLoader;
use Modules\Apps\Components\Dukaan\Loaders\PageLoader;
use Modules\Apps\Components\Dukaan\Loaders\Paginator;
use Illuminate\Routing\Router;

class AppsServiceProvider extends ServiceProvider
{
    
    protected $middleware = [
        'Apps' => [
            'dashboard.install'  => 'InstallSetting',
            'dashboard.installed'  => 'InstalledSetting',
        ],
    ];
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerMiddleware($this->app['router']);
        $this->registerComponents();
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(module_path('Apps', 'Database/Migrations'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register the filters.
     *
     * @param  Router $router
     * @return void
     */
    public function registerMiddleware(Router $router)
    {
        foreach ($this->middleware as $module => $middlewares) {
            foreach ($middlewares as $name => $middleware) {
                $class = "Modules\\{$module}\\Http\\Middleware\\{$middleware}";

                $router->aliasMiddleware($name, $class);
            }
        }
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerComponents()
    {
        //headers
        Blade::component('dukaan-head', Head::class);
        Blade::component('dukaan-site-colors', SiteColors::class);
        Blade::component('dukaan-header', Header::class);
        Blade::component('dukaan-header-search', HeaderSearch::class);
        Blade::component('dukaan-header-cart', HeaderCart::class);
        Blade::component('dukaan-header-menu', HeaderMenu::class);
        Blade::component('dukaan-header-nsted-menu', NstedMenu::class);
        Blade::component('dukaan-header-final-builder', FinalBuilder::class);

        //category lists

        Blade::component('dukaan-header-category-list-index', CategoryList\Index::class);
        Blade::component('dukaan-header-category-list-mega-menu', CategoryList\MegaMenu::class);
        Blade::component('dukaan-header-category-list-nsted-menu', CategoryList\NestedMenu::class);
        ////////////////////

        //home sections
        Blade::component('dukaan-home-builder', HomeBuilder::class);
        Blade::component('dukaan-home-section', HomeSection::class);
        Blade::component('dukaan-home-categories', Categories::class);
        Blade::component('dukaan-home-products', Products::class);
        /////////////////

        //Loaders
        Blade::component('dukaan-page-loader', PageLoader::class);
        Blade::component('dukaan-btn-loader', BtnLoader::class);
        Blade::component('dukaan-balls-loader', BallsLoader::class);
        Blade::component('dukaan-sppiner-loader', SppinerLoader::class);
        Blade::component('dukaan-paginator', Paginator::class);
        /////////////////

        //footer
        Blade::component('dukaan-footer', Footer::class);
        Blade::component('dukaan-js', Js::class);
        Blade::component('dukaan-scripts', Scripts::class);
        //////////////////////////
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path('Apps', 'Config/config.php') => config_path('apps.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('Apps', 'Config/config.php'), 'apps'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/apps');

        $sourcePath = module_path('Apps', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/apps';
        }, \Config::get('view.paths')), [$sourcePath]), 'apps');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/apps');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'apps');
        } else {
            $this->loadTranslationsFrom(module_path('Apps', 'Resources/lang'), 'apps');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production') && $this->app->runningInConsole()) {
            // app(Factory::class)->load(module_path('Apps', 'Database/factories'));
            $this->loadFactoriesFrom(module_path("Apps", 'Database/factories'));
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
