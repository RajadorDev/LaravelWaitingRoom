<?php 

namespace App\Data\Queue;

use App\Data\Utils\ExpirableObjectsManager;
use App\Data\Utils\HeartbeatObject;
use App\Exception\RecordAlreadyRegisteredException;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class QueueManager extends ExpirableObjectsManager
{

    const CACHE_POSITIONS = 'queue_positions';

    public function createFromData(array $data): HeartbeatObject
    {
        return WaitingUser::fromData($data);
    }

    public function next() : ?WaitingUser
    {
        $list = $this->all();
        return array_shift($list);
    }

    public function getQueuePosition(int|User $user) : int 
    {
        $value = Redis::hget(self::CACHE_POSITIONS, $user instanceof User ? $user->id : $user);
        if (is_numeric($value) && $value > 0) {
            return (int) $value;
        }
        return -1;
    }

    protected function setPosition(int $id, int $position) : void 
    {
        Redis::hset(self::CACHE_POSITIONS, $id, $position);
    }

    protected function unsetPosition(int $id) : void 
    {
        Redis::hdel(self::CACHE_POSITIONS, $id);
    }

    protected function onAdd(HeartbeatObject $object): void
    {
        $this->setPosition($object->id, $this->count());
    }

    protected function onRemove(int $identifier): void
    {
        $this->unsetPosition($identifier);
    }

    public function isWaiting(int $id) : bool 
    {
        return $this->has($id);
    }

    public function updateUsersPositions() : void 
    {
        $position = 1;
        foreach (Redis::hkeys($this->cacheId) as $identifier) {
            $this->setPosition($identifier, $position++);
        }
    }

    /**
     * @param integer $identifier
     * @return WaitingUser
     * @throws RecordAlreadyRegisteredException
     */
    public function addWaitingUser(int $identifier) : WaitingUser
    {
        $user = WaitingUser::new($identifier);
        $this->add($user);
        return $user;
    }

    public function clearExpiredWaitingList(int $timeout): bool
    {
        $result = parent::clearExpiredWaitingList($timeout);
        if ($result) {
            $this->updateUsersPositions();
        }
        return $result;
    }

}