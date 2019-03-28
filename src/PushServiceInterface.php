<?php

namespace Mitoop\JPush;

use Illuminate\Contracts\Queue\Factory as QueueContract;

interface PushServiceInterface
{
    public function send();

    public function setQueue(QueueContract $queue = null);
}