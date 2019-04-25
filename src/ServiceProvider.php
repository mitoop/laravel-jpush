<?php
namespace Mitoop\JPush;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use JPush\Client;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @deprecated Implement the \Illuminate\Contracts\Support\DeferrableProvider interface instead. Will be removed in Laravel 5.9.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     * @throws \LogicException
     */
    public function register()
    {
        $this->app->bind(JPushService::class, function (){
             $jPush = new JPushService(
                 config('services.jpush.app_key'),
                 config('services.jpush.master_secret'),
                 config('services.jpush.apns_production'),
                 config('services.jpush.log_file')
             );

            if ($this->app->bound('queue')) {
                $jPush->setQueue($this->app['queue']);
            }

            return $jPush;
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