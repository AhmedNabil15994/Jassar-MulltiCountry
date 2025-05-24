<?php

namespace App\Providers;

use App\Services\Countries;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // telescope
        // if ($this->app->isLocal()) {
        //     $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
        //     $this->app->register(TelescopeServiceProvider::class);
        // }

        $this->registerServices();

        $tenant = request()->server('TENANT');
        if ($tenant && ! in_array($tenant, config('multitenancy.reserved_subdomains'))) {
            $this->app->register(\Vsch\TranslationManager\ManagerServiceProvider::class);
            $this->app->register(\Vsch\TranslationManager\TranslationServiceProvider::class);
        } else {
            $this->app->register(\Illuminate\Translation\TranslationServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        cache()->forever('default-mail-config', config('mail'));

        \URL::forceScheme(\App::environment('local') ? 'http' : 'https');
        \URL::defaults([
            'locale' => setting('default_locale'),
        ]);
    }

    public function registerServices()
    {
        // Countries service
        $this->app->singleton('countries', function () {
            return new Countries();
        });
    }
}
