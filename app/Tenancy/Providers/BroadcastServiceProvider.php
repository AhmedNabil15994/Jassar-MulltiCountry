<?php

namespace App\Tenancy\Providers;

use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Contracts\Broadcasting\Broadcaster as BroadcasterContract;
use Illuminate\Contracts\Broadcasting\Factory as BroadcastingFactory;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BroadcastManager::class, function ($app) {
            return new BroadcastManager($app);
        });

        $this->app->bind(BroadcasterContract::class, function ($app) {
            return $app->make(BroadcastManager::class)->connection();
        });

        $this->app->alias(
            BroadcastManager::class,
            BroadcastingFactory::class
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            BroadcastManager::class,
            BroadcastingFactory::class,
            BroadcasterContract::class,
        ];
    }
}
