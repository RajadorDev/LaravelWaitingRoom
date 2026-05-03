<?php

declare (strict_types=1);

namespace App\Data\Queue;

use App\Data\Utils\HeartbeatObject;

class WaitingUser extends HeartbeatObject
{

    public static function fromData(array $data): HeartbeatObject
    {
        return new self(
            $data[self::DATA_ID],
            $data[self::DATA_HEARTBEAT]
        );
    }

    protected function serializeExtraData(): ?array
    {
        return null;
    }

    public static function new(
        int $userId
    ) : WaitingUser
    {
        return new self($userId, microtime(true));
    }

}