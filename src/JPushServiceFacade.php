<?php

namespace Mitoop\JPush;

use Illuminate\Support\Facades\Facade;

/**
 * @method static JPushService setPlatform(string|array $platform)
 * @method static JPushService toAll();
 * @method static JPushService toAlias(string|array $alias)
 * @method static JPushService toTag(string|array $tag)
 * @method static JPushService notify(string $notification)
 * @method static mixed send()
 * @method static JPushService attachExtras(array $extras)
 * @method static JPushService push(string|array $alias, string $notification, array $extras)
 * @method static mixed pushNow(string|array $alias, string $notification, array $extras)
 * @method static mixed pushQueue(string|array $alias, string $notification, array $extras)
 * @method static mixed queue(PushJobInterface $job, string $queue, string $connection)
 */
class JPushServiceFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected static function getFacadeAccessor()
    {
        return self::$app->make(JPushService::class);
    }
}
