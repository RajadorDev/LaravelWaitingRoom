<?php

declare (strict_types=1);

namespace App\Data\Queue;

class WaitingUser 
{

    public function __construct(
        public readonly int $userId,
        protected float $lastHeartbeat
    )
    {}

    public static function new(
        int $userId
    ) : WaitingUser
    {
        return new self($userId, microtime(true));
    }

    public function heartBeat() : void 
    {
        $this->lastHeartbeat = microtime(true);
    }

    public function getLastHeartBeatInterval() : float
    {
        return microtime(true) - $this->lastHeartbeat;
    }

    public function isExpired(float|int $maxTime) : bool 
    {
        return $this->getLastHeartBeatInterval() >= $maxTime;
    }

}