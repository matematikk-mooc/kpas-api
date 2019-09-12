<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] === '80') {
            $_SERVER['HTTPS'] = 'ok';
            $_SERVER['SERVER_PORT'] = 443;
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
