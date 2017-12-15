<?php

namespace Imphinite\GaodeMaps;

use Illuminate\Support\ServiceProvider;

class GaodeMapsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        require __DIR__.'/routes/routes.php';
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('imphinite-gaode-maps', function() {
            return new GaodeMaps();
        });
    }
}
