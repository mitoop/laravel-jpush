<?php
namespace Mitoop\JPush;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use JPush\Client;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     * @throws \LogicException
     */
    public function register()
    {
        $this->app->bind(JPushService::class, function (){
             return new JPushService(
                 config('services.jpush.app_key'),
                 config('services.jpush.master_secret'),
                 config('services.jpush.apns_production'),
                 config('services.jpush.log_file')
             );
        });
        
        $this->app->alias(JPushService::class, 'laravel-jpush');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [JPushService::class, 'laravel-jpush'];
    }
}