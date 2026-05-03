<?php

namespace App\Http\Controllers;

use App\Data\Queue\QueueManager;
use App\Services\OnlineUserService;
use App\Services\QueueService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class UserQueueController extends Controller
{
    
    public function heartbeat(Request $request, QueueService $queue) : JsonResponse
    {
        if ($queue->heartbeat($user = $request->user())) {
            return response()->json([
                'position' => $queue->getQueueManager()->getQueuePosition($user),
                'updateMileseconds' => config('waiting_room.heartbeat_mileseconds')
            ]);
        } else if (app(OnlineUserService::class)->allowedToBeOnline($user->id, $user)) {
            return response()->json([
                'position' => 0,
                'updateMileseconds' => 0
            ]);
        }
        return response()->json([
            'error' => 'You\'re not in queue anymore'
        ], 401);
    }

    public function loadQueuePage(Request $request, QueueService $queue) : InertiaResponse
    {
        $user = $request->user();
        $queue->tryAddToQueue($user);

        return Inertia::render(
            'queue/main',
            [
                'position' => max(1, $queue->getQueueManager()->getQueuePosition($user)),
                'updateMileseconds' => config('waiting_room.heartbeat_mileseconds')
            ]
        );
    }
}
