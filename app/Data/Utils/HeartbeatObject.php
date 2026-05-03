<?php

namespace App\Data\Utils;

use JsonSerializable;

abstract class HeartbeatObject implements JsonSerializable
{

    const DATA_HEARTBEAT = 'lastHeartBeat';

    const DATA_ID = 'id';

    public function __construct(
        public readonly int $id,
        protected float $lastHeartbeat
    )
    {}

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

    public function jsonSerialize(): mixed
    {
        $data = [self::DATA_ID => $this->id, self::DATA_HEARTBEAT => $this->lastHeartbeat];
        if ($extraData = $this->serializeExtraData()) {
            return array_merge($data, $extraData);
        }
        return $data;
    }

    /**
     * @param array $data
     * @return HeartbeatObject
     */
    abstract public static function fromData(array $data) : HeartbeatObject;

    /**
     * @return array|null
     */
    abstract protected function serializeExtraData() : ?array;

}