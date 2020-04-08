<?php

namespace Mitoop\JPush;

use Illuminate\Contracts\Foundation\Application;

class JPushJob implements PushJobInterface
{
    /**
     * The push service instance.
     *
     * @var \Mitoop\JPush\PushJobInterface
     */
    protected $pushService;

    /**
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Create a new job instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Mitoop\JPush\PushServiceInterface  $pushService
     */
    public function __construct(Application $app, PushServiceInterface $pushService)
    {
        $this->app = $app;
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
        $this->app->log->error('JPush job error', [
            'msg'  => $e->getMessage(),
            'file' => $e->getFile().':'.$e->getLine()
        ]);
    }

    public function __clone()
    {
        $this->pushService = (clone $this->pushService)->setQueue();
    }

}
