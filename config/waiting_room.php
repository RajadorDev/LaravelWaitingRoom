<?php

return [
    'online_limit' => env('WAITING_ROOM_MAX_ONLINE_USERS', 1),
    'online_timeout' => max(1, env('WAITING_ROOM_HEARTBEAT_TIMEOUT', 5)),
    'heartbeat_mileseconds' => 1500
];
