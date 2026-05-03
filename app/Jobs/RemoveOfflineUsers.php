<?php

namespace App\Jobs;

use App\Data\Online\OnlineUsersManager;
use App\Data\Queue\QueueManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Data\Utils\ExpirableObjectsManager;
use App\Services\OnlineUserService;
use App\Services\QueueService;
use Illuminate\Support\Facades\Log;

class RemoveOfflineUsers implements ShouldQueue
{
    use Queueable;

    /** @var ExpirableObjectsManager[] */
    protected array $managers = [];

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->managers = [
            app(OnlineUserService::class)->getOnlineUsersManager(),
            app(QueueService::class)->getQueueManager()
        ];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $timeout = config('waiting_room.online_timeout', 5);
        foreach ($this->managers as $manager) {
            $manager->clearExpiredWaitingList($timeout);
        }
    }
}
