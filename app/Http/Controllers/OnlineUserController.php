<?php

namespace App\Http\Controllers;

use App\Services\OnlineUserService;
use Illuminate\Http\Request;

class OnlineUserController extends Controller
{

    public function keepAlive(Request $request, OnlineUserService $service) : void
    {
        $service->heartbeat($request->user());
    }
}