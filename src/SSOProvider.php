<?php
namespace Cyrex\SSO;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Gate;

use Cyrex\SSO\Controllers\User;
use Cyrex\SSO\Controllers\Guard;
use Cyrex\SSO\Controllers\Provider;

class SSOProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        include __DIR__.'/routes/web.php';
        $this->loadViewsFrom(__DIR__.'/resources/views/auth', 'sso');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->publishes([
            __DIR__.'/resources/views/errors' => resource_path('views/errors'),
            __DIR__.'/config/config.php' => config_path('sso.php')
        ]);

        Gate::define('sso', function(User $user, ...$action){
            return true;
        });
        Auth::provider('sso', function($app, array $config) {
            return new Provider();
        });
        Auth::extend('sso', function ($app, $name, array $config) {
            return new Guard(Auth::createUserProvider($config['provider']), $this->app['request']);
        });
    }
}
