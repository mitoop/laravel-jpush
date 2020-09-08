<?php

namespace Mitoop\JPush;

use Illuminate\Contracts\Queue\ShouldQueue;

interface PushServiceInterface
{
    public function send();

    public function queue(ShouldQueue $job = null, $queue = null, $connection = null);
}
