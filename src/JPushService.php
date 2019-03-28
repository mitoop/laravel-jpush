<?php

namespace Mitoop\JPush;

use JPush\Client;
use Illuminate\Contracts\Queue\Factory as QueueContract;

class JPushService implements PushServiceInterface
{
    protected $client;
    protected $payload;
    protected $notification;
    protected $extras = [];

    /**
     * The queue factory implementation.
     *
     * @var \Illuminate\Contracts\Queue\Factory
     */
    protected $queue;

    /**
     * 构造方法, 初始化极光Client 和 Payload.
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

    /**
     * 设置推送平台.
     * @param $platform
     * @return $this
     */
    public function setPlatform($platform)
    {
        $this->payload->setPlatform($platform);

        return $this;
    }

    /**
     * 给所有人推送.
     * @return $this
     */
    public function toAll()
    {
        $this->payload->addAllAudience();

        return $this;
    }

    /**
     * 给别名推送.
     * @param $alias
     * @return $this
     */
    public function toAlias($alias)
    {
        $this->payload->addAlias($alias);

        return $this;
    }

    /**
     * 给某类标签的人推送.
     * @param $tag
     * @return $this
     */
    public function toTag($tag)
    {
        $this->payload->addTag($tag);

        return $this;
    }

    /**
     * 设置通知信息.
     * @param $notification
     * @return $this
     */
    public function notify($notification)
    {
        $this->notification = $notification;

        return $this;
    }

    /**
     * 发送方法.
     * @return array|bool
     */
    public function send()
    {
        $this->payload
             ->androidNotification($this->notification, [
                 'extras' => $this->extras,
             ])
             ->iosNotification($this->notification, [
                 'extras' => $this->extras,
                 'sound'  => 'default'
             ]);

        try {
            return $this->payload->send();
        } catch (\JPush\Exceptions\JPushException $e){
            return false;
        }
    }

    /**
     * 推送的快捷方法, 并不发送. 可以之后调用send, 或者 queue.
     * @param $alias
     * @param $notification
     * @param array $extras
     * @return $this
     */
    public function push($alias, $notification, array $extras = [])
    {
        $this->setPlatform('all')
             ->notify($notification)
             ->attachExtras($extras);

        if ($alias == 'all'){
            $this->toAll();
        } else {
            $this->toAlias($alias);
        }

        return $this;
    }

    /**
     * 同步推送.
     * @param $alias
     * @param $notification
     * @param array $extras
     * @return array|bool
     */
    public function pushNow($alias, $notification, array $extras = [])
    {
        return $this->push($alias, $notification,$extras)->send();
    }

    /**
     * 附加的信息.
     * @param array $extras
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
     * @param \Mitoop\JPush\PushJobInterface|null $job 任务类
     * @param null $queue 使用的队列 为 null 时 调用默认队列
     * @param null $connection 使用的队列连接 为 null 时 调用默认连接
     * @return mixed
     */
    public function queue(PushJobInterface $job = null, $queue = null, $connection = null)
    {
        return $this->queue->connection($connection)->pushOn($queue, $job ? :  new JPushJob($this));
    }

    /**
     * Set the queue manager instance.
     *
     * @param  \Illuminate\Contracts\Queue\Factory  $queue
     * @return $this
     */
    public function setQueue(QueueContract $queue = null)
    {
        $this->queue = $queue;

        return $this;
    }

    /**
     * Dynamically handle calls to the class.
     * @description 为了满足复杂使用, 提供一个直接调用Payload的通道.
     * @param  string  $method
     * @param  array   $arguments
     * @return mixed
     *
     */
    public function __call($method, $arguments)
    {
        $this->payload->$method(...$arguments);

        return $this;
    }
}