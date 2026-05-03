<?php

namespace App\Services;

use App\Data\Online\OnlineUsersManager;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use App\Exception\Online\UserAlreadyOnlineException;
use App\Exception\RecordAlreadyRegisteredException;

class OnlineUserService 
{

    const ONLINE_USERS_CACHE_ID = 'online_users';

    protected OnlineUsersManager $manager;

    public function __construct()
    {
        $this->manager = new OnlineUsersManager(self::ONLINE_USERS_CACHE_ID);
    }

    public function getOnlineUsersManager() : OnlineUsersManager
    {
        return $this->manager;
    }

    /**
     * NOTE: If the player is already online, this method will return true too
     * @param integer $userId
     * @param User|null $user
     * @return boolean
     */
    public function allowedToBeOnline(int $userId, ?User $user) : bool 
    {
        if ($this->manager->hasFreeSpace()) {
            try {
                $this->manager->addOnlineUser($userId, $user);
            } catch (RecordAlreadyRegisteredException) {}
        }
        return $this->manager->isOnline($userId);
    }

    public function heartbeat(int|User $user) : void 
    {
        if ($user = $this->manager->get($user instanceof User ? $user->id : $user)) {
            $user->heartBeat();
            $this->manager->saveUpdates($user);
        }
    }

    public function removeOnlineUser(int|User $user) : void 
    {
        $this->manager->remove($user instanceof User ? $user : $user);
    }
    
}