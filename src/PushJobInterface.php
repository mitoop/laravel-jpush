<?php

namespace Mitoop\JPush;

use Illuminate\Contracts\Foundation\Application;

interface PushJobInterface
{
   public function __construct(Application $app, PushServiceInterface $pushService);

   public function __clone();
}
