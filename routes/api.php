<?php

use App\Http\Controllers\OnlineUserController;
use App\Http\Controllers\UserQueueController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->get(
    '/queue/heartbeat', [UserQueueController::class, 'heartbeat']
);

Route::middleware(['web', 'auth', 'limited_users'])->get('/keepalive', [OnlineUserController::class, 'keepAlive']);