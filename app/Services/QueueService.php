<?php

namespace App\Services;

use App\Data\Queue\QueueManager;
use App\Models\User;

class QueueService
{

    const QUEUE_CACHE_ID = 'queue-cache';

    protected QueueManager $queue;

    public function __construct()
    {
        $this->queue = new QueueManager(self::QUEUE_CACHE_ID);
    }

    public function getQueueManager() : QueueManager
    {
        return $this->queue;
    }

    public function heartbeat(User|int $user) : bool 
    {
        if ($user = $this->queue->get($user instanceof User ? $user->id : $user)) {
            $user->heartBeat();
            $this->queue->saveUpdates($user);
            return true;
        }
        return false;
    }

    /**
     * NOTE: If this method return true, the player was added (or is already added) to the queue
     *
     * @param User|integer $user
     * @return boolean
     */
    public function tryAddToQueue(User|int $user) : bool 
    {
        if ($user instanceof User) {
            $userId = $user->id;
        } else {
            $userId = $user;
            $user = null;
        }
        if (!app(OnlineUserService::class)->allowedToBeOnline($userId, $user)) {
            if (!$this->queue->isWaiting($userId)) {
                $this->queue->addWaitingUser($userId);
            }
            return true;
        }
        $this->queue->remove($userId);
        return false;
    }

}