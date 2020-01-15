<?php

namespace Tnaffh\Routee\Providers;

use Illuminate\Support\ServiceProvider;

class RouteeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->publishes([
            __DIR__.'/../../config/config.php' => config_path('routee.php'),
        ]);
    }
}
