<?php

namespace Modules\Catalog\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Modules\Catalog\Components\{Category,Product,ProductTag,AddCartModal};
use Modules\Catalog\Components\Checkout\{Steps,Address,CouponForm};

class CatalogServiceProvider extends ServiceProvider
{

    protected $middleware = [
        'Catalog' => [
            'empty.cart' => 'EmptyCart',
            'empty.address' => 'EmptyAddress'
        ],
    ];

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerComponents();
        $this->registerMiddleware($this->app['router']);
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(module_path('Catalog', 'Database/Migrations'));
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerComponents()
    {
        Blade::component('dukaan-category-item', Category::class);
        Blade::component('dukaan-product-item', Product::class);
        Blade::component('dukaan-product-tag', ProductTag::class);
        Blade::component('dukaan-add-cart-modal', AddCartModal::class);
        Blade::component('dukaan-checkout-steps', Steps::class);
        Blade::component('dukaan-checkout-address', Address::class);
        Blade::component('dukaan-checkout-coupon-form', CouponForm::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);

        $this->app->resolving(\Illuminate\Pagination\LengthAwarePaginator::class, function ($paginator) {
            return $paginator->appends(Arr::except(request()->query(), $paginator->getPageName()));
        });
    }

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
    protected function registerConfig()
    {
        $this->publishes([
            module_path('Catalog', 'Config/config.php') => config_path('catalog.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('Catalog', 'Config/config.php'), 'catalog'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/catalog');

        $sourcePath = module_path('Catalog', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], 'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/catalog';
        }, \Config::get('view.paths')), [$sourcePath]), 'catalog');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/catalog');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'catalog');
        } else {
            $this->loadTranslationsFrom(module_path('Catalog', 'Resources/lang'), 'catalog');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (!app()->environment('production') && $this->app->runningInConsole()) {
            // app(Factory::class)->load(module_path('Catalog', 'Database/factories'));
            $this->loadFactoriesFrom(module_path("Catalog", 'Database/factories'));
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
