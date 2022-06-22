<?php

namespace Laravue3\Stateless;

use Illuminate\Support\ServiceProvider;

class StatelessServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/stateless.php', 'stateless');
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes([
            __DIR__.'/../config/stateless.php' => config_path('stateless.php')
        ], 'stateless-config');

    }
}
