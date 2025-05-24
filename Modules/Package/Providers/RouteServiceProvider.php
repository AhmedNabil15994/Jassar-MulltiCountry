<?php

namespace Modules\Package\Providers;

use File;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Modules\Core\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    protected $moduleNamespace = 'Modules\Package\Http\Controllers';

    protected function mapWebRoutes()
    {
        Route::middleware('web', 'localizationRedirect' , 'localeSessionRedirect', 'localeViewPath' , 'localize')
            ->prefix(LaravelLocalization::setLocale())
            ->namespace($this->moduleNamespace)
            ->group(module_path('Package', '/Routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->moduleNamespace)
            ->group(module_path('Package', '/Routes/api.php'));
    }
}
