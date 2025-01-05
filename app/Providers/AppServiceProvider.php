<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        if (!defined('SIGTERM')) {
            define('SIGTERM', 15);
        }
        if (!defined('SIGINT')) {
            define('SIGINT', 2);
        }
        if (!defined('SIGHUP')) {
            define('SIGHUP', 1);
        }
    }
}
