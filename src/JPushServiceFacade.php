<?php

namespace Mitoop\JPush;

use Illuminate\Support\Facades\Facade;

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