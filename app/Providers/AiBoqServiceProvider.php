<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AiBoqServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('AiBoqService', function ($app) {
            return new \App\Services\AiBoqService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
