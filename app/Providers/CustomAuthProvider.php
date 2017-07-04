<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\ServiceProvider;

class CustomAuthProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /*$this->app['auth']->extend('custom',function()
        {
            return new CustomUserProvider(new User);
        });*/
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['auth']->extend(
            'legacy',
            function () {
                return new \Illuminate\Auth\Guard(
                    new \App\Providers\CustomUserProvider(
                        $this->app['config']['auth.model']
                    ),
                    $this->app['session.store']
                );
            }
        );
    }
}
