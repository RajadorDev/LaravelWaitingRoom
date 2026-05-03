<?php

declare (strict_types=1);

namespace App\Data\Utils;

use App\Exception\RecordAlreadyRegisteredException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

abstract class ExpirableObjectsManager 
{

    /** @var array<int,HeartbeatObject> */
    protected array $unserializedObjectsList = [];

    public function __construct(
        protected readonly string $cacheId
    )
    {}

    /**
     * @param boolean $useFromCache
     * @return HeartbeatObject[]
     */
    public function all(bool $useFromCache = true) : array
    {
        $objectList = [];
        foreach (Redis::hgetall($this->cacheId) as $id => $_) {
            $objectList[$id] = $this->get($id, $useFromCache);
        }
        return $objectList;
    }

    /**
     * @param array $data
     * @return HeartbeatObject
     */
    abstract public function createFromData(array $data) : HeartbeatObject;

    public function get(int $identifier, bool $useFromCache = true) : ?HeartbeatObject
    {
        if (isset($this->unserializedObjectsList[$identifier]) && $useFromCache) {
            return $this->unserializedObjectsList[$identifier];
        } else if ($dataFound = Redis::hget($this->cacheId, $identifier)) {
            return $this->unserializedObjectsList[$identifier] = $this->createFromData(json_decode(
                $dataFound,
                true
            ));
        }
        return null;
    }

    public function saveUpdates(HeartbeatObject ...$objects) : void 
    {
        foreach ($objects as $object) {
            Redis::hset($this->cacheId, $object->id, json_encode($object));
        }
    }
    public function remove(int $id) : bool
    {
        unset($this->unserializedObjectsList[$id]);
        if (Redis::hexists($this->cacheId, $id)) {
            Redis::hdel($this->cacheId, $id);
            $this->onRemove($id);
            return true;
        }
        return false;
    }

    protected function onRemove(int $identifier) : void 
    {}

    protected function has(int $identifier) : bool 
    {
        return (bool) Redis::hexists($this->cacheId, $identifier);
    }

    protected function add(HeartbeatObject $object) : void
    {
        if (Redis::hexists($this->cacheId, $object->id)) {
            throw new RecordAlreadyRegisteredException("Record {$object->id} is already registered");
        }
        $this->unserializedObjectsList[$object->id] = $object;
        $this->saveUpdates($object);
        $this->onAdd($object);
    }

    protected function onAdd(HeartbeatObject $object) : void
    {}

    /**
     * @param int $index
     * @return void
     */
    protected function onExpire(int $index) : void
    {
        $this->remove($index);
    }

    public function clearExpiredWaitingList(int $timeout) : bool
    {
        $updated = false;
        foreach ($this->all() as $waitingUserId => $userWaiting) {
            if ($userWaiting->isExpired($timeout)) {
                $this->onExpire($waitingUserId);
                Log::info("User $waitingUserId removed from Queue:" . get_called_class());
                $updated = true;
            }
        }
        return $updated;
    }

    public function count() : int 
    {
        return Redis::hlen($this->cacheId);
    }
    
}