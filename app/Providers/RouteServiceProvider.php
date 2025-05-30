<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapTenantApiRoutes();

        $this->mapWebRoutes();

        $this->mapTenantWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('default-web')
            ->domain(config('multitenancy.www_domain'))
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
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
            ->domain(config('multitenancy.www_domain'))
            ->middleware('default-api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    /**
     * Define the "api" routes for the current tenant.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapTenantApiRoutes()
    {
        Route::prefix('api')
        // domain('{tenant}.toucart.wip')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/tenant-api.php'));
    }

    /**
     * Define the "web" routes for the current tenant.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapTenantWebRoutes()
    {
        Route::middleware('web')
        // domain('{tenant}.toucart.wip')
            ->namespace($this->namespace)
            ->group(base_path('routes/tenant-web.php'));
    }
}
