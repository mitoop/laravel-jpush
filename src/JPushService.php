<?php

namespace Mitoop\JPush;

use JPush\Client;

class JPushService
{
    private $client;
    private $payload;
    private $notification;
    private $extras = [];

    /**
     * JPushService constructor.
     *
     * @param $appKey
     * @param $masterSecret
     * @param bool $apnProduction
     * @param null $logFile
     * @throws \InvalidArgumentException
     */
    public function __construct($appKey, $masterSecret, $apnProduction = false, $logFile = null)
    {
         $this->client = new Client($appKey, $masterSecret, $logFile);

         $this->payload = $this->client->push();
         $this->payload->options([
             'apns_production' => $apnProduction,
         ]);
    }


    public function setPlatform($platform)
    {
        $this->payload->setPlatform($platform);

        return $this;
    }

    public function toAll()
    {
        $this->payload->addAllAudience();

        return $this;
    }

    public function toAlias($alias)
    {
        $this->payload->addAlias($alias);

        return $this;
    }

    public function toTag($tag)
    {
        $this->payload->addTag($tag);

        return $this;
    }

    public function notify($notification)
    {
        $this->notification = $notification;

        return $this;
    }

    public function send()
    {
        return $this->payload
                    ->androidNotification($this->notification, [
                        'extras' => $this->extras,
                    ])
                    ->iosNotification($this->notification, [
                        'extras' => $this->extras,
                    ])
                    ->send();
    }

    public function extras(array $extras)
    {
        $this->extras = $extras;

        return $this;
    }

    public function __call($method, $arguments)
    {
        $this->payload->$method(...$arguments);

        return $this;
    }
}