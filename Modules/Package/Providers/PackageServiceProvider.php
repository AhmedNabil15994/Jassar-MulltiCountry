<?php

namespace Modules\Package\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Blade;
use Modules\Package\Components\Dashboard\Packages\PackageCount;

class PackageServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerComponents();
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(module_path('Package', 'Database/Migrations'));
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
     * Register components.
     *
     * @return void
     */
    protected function registerComponents()
    {
        Blade::component('dashboard-package-count', PackageCount::class);
    }


    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path('Package', 'Config/config.php') => config_path('package.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('Package', 'Config/config.php'), 'package'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/package');

        $sourcePath = module_path('Package', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/package';
        }, \Config::get('view.paths')), [$sourcePath]), 'package');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/package');

        $attributesPath = module_path('Package','Resources/lang/'.app()->getLocale().'/attributes.php');
        if(file_exists($attributesPath))
            setValidationAttributes(include $attributesPath);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'package');
        } else {
            $this->loadTranslationsFrom(module_path('Package', 'Resources/lang'), 'package');
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
            app(Factory::class)->load(module_path('Package', 'Database/factories'));
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
