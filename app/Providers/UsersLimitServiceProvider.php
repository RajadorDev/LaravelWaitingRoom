<?php

namespace App\Providers;

use App\Events\UserSetOnlineEvent;
use App\Http\Middleware\OnlineLimitCheckerMiddleware;
use App\Listeners\UsersLimitListener;
use App\Services\OnlineUserService;
use App\Services\QueueService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class UsersLimitServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(OnlineUserService::class);
        $this->app->singleton(QueueService::class);

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Event::listen([
            UserSetOnlineEvent::class
        ], UsersLimitListener::class);
        $this->app->alias(OnlineLimitCheckerMiddleware::class, 'limited_users');
    }
}
