<?php
namespace Cyrex\SSO;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Gate;

use Cyrex\SSO\app\Http\Controllers\User;
use Cyrex\SSO\app\Http\Controllers\Guard;
use Cyrex\SSO\app\Http\Controllers\Provider;

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
        if($this->app['config']->has("auth.defaults.guard") != "sso") {
            $this->app['config']->set("auth.defaults.guard", "sso");
        }
        if(!$this->app['config']->has("auth.guards.sso")){
            $this->app['config']->set("auth.guards.sso", Array('driver' => 'sso', 'provider' => 'sso'));
        }
        if(!$this->app['config']->has("auth.providers.sso")){
            $this->app['config']->set("auth.providers.sso", Array('driver' => 'sso'));
        }


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
