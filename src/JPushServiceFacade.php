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
 * @method static JPushService extras(array $extras)
 */
class JPushServiceFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return JPushService::class;
    }
}