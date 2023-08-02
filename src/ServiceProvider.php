<?php

namespace Mitoop\JPush;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider implements DeferrableProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     *
     * @throws \LogicException
     */
    public function register()
    {
        $this->app->bind(JPushService::class, function () {
            $jpushConfig = $this->app->config['services']['jpush'];

            return new JPushService(
                $jpushConfig['app_key'],
                $jpushConfig['master_secret'],
                $jpushConfig['apns_production'],
                $jpushConfig['log_file']
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
