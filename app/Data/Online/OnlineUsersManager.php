<?php

namespace App\Data\Online;

use App\Data\Utils\ExpirableObjectsManager;
use App\Data\Utils\HeartbeatObject;
use App\Events\UserSetOnlineEvent;
use App\Models\User;
use App\Exception\RecordAlreadyRegisteredException;

class OnlineUsersManager extends ExpirableObjectsManager
{

    public function createFromData(array $data): HeartbeatObject
    {
        return OnlineUser::fromData($data);
    }

    /**
     * @param integer $identifier
     * @param User|null $user
     * @return OnlineUser
     * @throws RecordAlreadyRegisteredException
     */
    public function addOnlineUser(int $identifier, ?User $user) : OnlineUser
    {
        $user = OnlineUser::new($identifier, $user);
        $this->add($user);
        return $user;
    }

    public function isOnline(int|User $user) : bool 
    {
        if ($user instanceof User) {
            return $this->has($user->id);
        }
        return $this->has($user);
    }

    public function getLimit() : int 
    {
        return config('waiting_room.online_limit', 1);
    }

    public function getFreeSpace() : int 
    {
        return $this->getLimit() - $this->count();
    }

    public function hasFreeSpace() : bool 
    {
        return $this->getFreeSpace() > 0;
    }

    protected function onAdd(HeartbeatObject $object): void
    {
        /** @var OnlineUser $object */
        event(new UserSetOnlineEvent($object->id, true, $object->user));
    }

    protected function onRemove(int $identifier): void
    {
        event(new UserSetOnlineEvent($identifier, false, null));
    }

}