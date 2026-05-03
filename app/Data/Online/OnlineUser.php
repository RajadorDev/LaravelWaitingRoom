<?php

namespace App\Data\Online;

use App\Data\Utils\HeartbeatObject;
use App\Models\User;

class OnlineUser extends HeartbeatObject
{

    const DATA_USER = 'user';

    public function __construct(
        int $id,
        float $lastHeartbeat,
        public readonly ?User $user = null
    )
    {
        parent::__construct($id, $lastHeartbeat);
    }

    public static function fromData(array $data): HeartbeatObject
    {
        $userData = $data[self::DATA_USER];

        if (is_array($userData)) {
            $user = new User($userData);
        }
        return new self(
            $data[self::DATA_ID],
            $data[self::DATA_HEARTBEAT],
            $user ?? null
        );
    }

    protected function serializeExtraData(): ?array
    {
        return [self::DATA_USER => $this->user?->toArray()];
    }

    public static function new(int $id, ?User $user = null) : OnlineUser
    {
        return new self($id, microtime(true), $user);   
    }
}