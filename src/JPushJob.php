<?php

namespace Mitoop\JPush;

use Log;

class JPushJob implements PushJobInterface
{
    /**
     * The push service instance.
     *
     * @var \Mitoop\JPush\PushJobInterface
     */
    protected $pushService;

    /**
     * Create a new job instance.
     *
     * @param \Mitoop\JPush\PushServiceInterface $pushService
     */
    public function __construct(PushServiceInterface $pushService)
    {
        $this->pushService = $pushService;
    }

    /**
     * Handle the queued job.
     *
     * @return void
     */
    public function handle()
    {
        $this->pushService->send();
    }

    /**
     * Get the display name for the queued job.
     *
     * @return string
     */
    public function displayName()
    {
        return get_class($this);
    }

    /**
     * Call the failed method on the mailable instance.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function failed($e)
    {
        Log::error('JPush job error', [
            'msg'  => $e->getMessage(),
            'file' => $e->getFile().':'.$e->getLine()
        ]);
    }

    public function __clone()
    {
        $this->pushService = (clone $this->pushService)->setQueue();
    }

}