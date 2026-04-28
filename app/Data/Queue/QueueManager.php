<?php 

namespace App\Data\Queue;

use App\Exception\Queue\QueueUserAlreadyRegisteredException;

class QueueManager 
{

    /** @var array<int,WaitingUser> */
    protected array $list = [];

    /**
     * @param WaitingUser $user
     * @return void
     * @throws QueueUserAlreadyRegisteredException
     */
    public function push(WaitingUser $user) : void 
    {
        $userId = $user->userId;
        if (isset($this->list[$userId])) {
            throw new QueueUserAlreadyRegisteredException()
        }
    }
}