<?php

namespace App\Http\Middleware;

use App\Services\OnlineUserService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OnlineLimitCheckerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $onlineService = app(OnlineUserService::class);
        $user = $request->user();
        if ($onlineService->allowedToBeOnline($user->id, $user)) {
            return $next($request);
        }
        return redirect('/queue?target=' . $request->fullUrl());
    }
}
