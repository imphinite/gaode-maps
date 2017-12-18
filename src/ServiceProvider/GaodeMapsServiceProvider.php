<?php

namespace GaodeMaps\ServiceProvider;

use Illuminate\Support\ServiceProvider;

class GaodeMapsServiceProvider extends ServiceProvider
{
    /**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;
    

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
                __DIR__.'/../config/gaodemaps.php' => config_path('gaodemaps.php'),
            ], 'gaodemaps');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('GaodeMaps', function($app)
        {
            return new \GaodeMaps\GaodeMaps();
        });
    }
        
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('GaodeMaps');
    }
}
