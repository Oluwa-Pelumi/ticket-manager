<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Event;
use Illuminate\Notifications\Events\NotificationFailed;
use App\Listeners\MarkEmailAsInvalid;

/**
 * Core application service provider for gates, Vite, and custom notification channels.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Event::listen(NotificationFailed::class, MarkEmailAsInvalid::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        Gate::define('admin-access', function (User $user) {
            return $user->role === 'admin';
        });
    }
}
