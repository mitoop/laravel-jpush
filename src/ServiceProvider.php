<?php
namespace Mitoop\JPush;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

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
        $this->app->bind('jpush', function (){
            $jpushConfig  = $this->app->config['services']['jpush'];

            return new JPushService(
                $jpushConfig['app_key'],
                $jpushConfig['master_secret'],
                $jpushConfig['apns_production'],
                $jpushConfig['log_file']
            );
        });
    }
}
