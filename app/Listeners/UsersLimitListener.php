<?php

namespace App\Listeners;

use App\Events\UserSetOnlineEvent;
use App\Services\OnlineUserService;
use App\Services\QueueService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UsersLimitListener
{

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserSetOnlineEvent $event): void
    {
        if (!$event->setOnline) {
            $queueManager = app(QueueService::class)->getQueueManager();
            $next = $queueManager->next();
            if ($next) {
                if (app(OnlineUserService::class)->allowedToBeOnline($next->id, $event->user)) {
                    $queueManager->remove($next->id);
                    $queueManager->updateUsersPositions();
                }
            }
        }
    }
}
