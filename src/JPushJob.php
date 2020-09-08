<?php

namespace Mitoop\JPush;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class JPushJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * The push service instance.
     *
     * @var \Mitoop\JPush\PushServiceInterface
     */
    protected $pushService;

    /**
     * Create a new job instance.
     *
     * @param  \Mitoop\JPush\PushServiceInterface  $pushService
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

    public function tags()
    {
        return [
            'JPushJob',
        ];
    }
}
