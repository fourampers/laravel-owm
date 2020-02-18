<?php

namespace Fourampers\LaravelOWM;

use Illuminate\Support\ServiceProvider;
use Fourampers\LaravelOWM\LaravelOWM;

class LaravelOWMServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton(LaravelOWM::class, function ($app) {
            return new LaravelOWM();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        
        $config = $this->app['config']->get('laravel-owm');
        
        if ($config !== null) {
            if ($config['routes_enabled']) {
                $this->loadRoutesFrom(__DIR__ . '/Http/routes/web.php');
            }
        }
        
        $this->publishes([
            __DIR__ . '/config/laravel-owm.php' => config_path('laravel-owm.php'),
        ]);
    }
}
