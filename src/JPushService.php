<?php

namespace Mitoop\JPush;

use Illuminate\Contracts\Queue\ShouldQueue;
use JPush\Client;

class JPushService implements PushServiceInterface
{
    protected $client;

    protected $payload;

    protected $title;
    protected $notification;

    protected $extras = [];

    /**
     * 构造方法, 初始化极光Client 和 Payload.
     *
     * @param  bool  $apnProduction
     * @param  null  $logFile
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($appKey, $masterSecret, $apnProduction = false, $logFile = null)
    {
        if (! $logFile) {
            $logFile = null;
        }

        $this->client = new Client($appKey, $masterSecret, $logFile);

        $this->payload = $this->client->push();
        $this->payload->options([
            'apns_production' => $apnProduction,
        ]);
    }

    /**
     * 设置推送平台.
     *
     * @return $this
     */
    public function setPlatform($platform)
    {
        $this->payload->setPlatform($platform);

        return $this;
    }

    /**
     * 给所有人推送.
     *
     * @return $this
     */
    public function toAll()
    {
        $this->payload->addAllAudience();

        return $this;
    }

    /**
     * 给别名推送.
     *
     * @return $this
     */
    public function toAlias($alias)
    {
        $this->payload->addAlias($alias);

        return $this;
    }

    /**
     * 给某类标签的人推送.
     *
     * @return $this
     */
    public function toTag($tag)
    {
        $this->payload->addTag($tag);

        return $this;
    }

    /**
     * 设置通知信息.
     *
     * @return $this
     */
    public function notify($title, $notification)
    {
        $this->title = $title;
        $this->notification = $notification;

        return $this;
    }

    /**
     * 发送方法.
     *
     * @return array|bool
     */
    public function send()
    {
        $notification = $this->notification;
        if ($this->title) {
            $notification = [
                'title' => $this->title,
                'body' => $this->notification,
            ];
        }
        $this->payload
            ->addAndroidNotification($this->notification, $this->title, 2, $this->extras)
            ->iosNotification($notification, [
                'extras' => $this->extras,
                'sound' => 'default',
                'content-available' => true,
            ]);

        try {
            return $this->payload->send();
        } catch (\JPush\Exceptions\JPushException $e) {
            return false;
        }
    }

    /**
     * 推送的快捷方法, 并不发送. 可以之后调用send, 或者 queue.
     *
     * @return $this
     */
    public function push($alias, $notification, $title, array $extras = [])
    {
        $this->setPlatform('all')
            ->notify($title, $notification)
            ->attachExtras($extras);

        if ($alias == 'all') {
            $this->toAll();
        } else {
            $this->toAlias($alias);
        }

        return $this;
    }

    /**
     * 同步推送.
     *
     * @return array|bool
     */
    public function pushNow($alias, $notification, $title, array $extras = [])
    {
        return $this->push($alias, $notification, $title, $extras)->send();
    }

    /**
     * 队列推送
     *
     * @return mixed
     */
    public function pushQueue($alias, $notification, $title, array $extras = [])
    {
        return $this->push($alias, $notification, $title, $extras)->queue();
    }

    /**
     * 附加的信息.
     *
     * @return $this
     */
    public function attachExtras(array $extras)
    {
        $this->extras = $extras;

        return $this;
    }

    /**
     * 调用队列发送.
     *
     * @param  ShouldQueue|null  $job  任务类
     * @param  null  $queue  使用的队列 为 null 时 调用默认队列
     * @param  null  $connection  使用的队列连接 为 null 时 调用默认连接
     * @return mixed
     */
    public function queue(ShouldQueue $job = null, $queue = null, $connection = null)
    {
        $pending = dispatch($job ?: new JPushJob($this));

        if ($queue) {
            $pending->onQueue($queue);
        }

        if ($connection) {
            $pending->onConnection($connection);
        }
    }

    /**
     * Dynamically handle calls to the class.
     *
     * @description 为了满足复杂使用, 提供一个直接调用Payload的通道.
     *
     * @param  string  $method
     * @param  array  $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        $this->payload->$method(...$arguments);

        return $this;
    }
}
