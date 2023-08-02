<?php

namespace Mitoop\JPush;

use Illuminate\Contracts\Queue\ShouldQueue;
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
 * @method static null queue(ShouldQueue $job, string $queue, string $connection)
 */
class JPushServiceFacade extends Facade
{
    public static $cached = false;

    protected static function getFacadeAccessor()
    {
        return JPushService::class;
    }
}
