<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
use App\Broadcasting\WhatsappChannel;
use Illuminate\Support\ServiceProvider;
use Illuminate\Notifications\ChannelManager;

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
        //
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

        app(ChannelManager::class)->extend('whatsapp', function() {
            return new WhatsappChannel();
        });

        view()->composer('*', function ($view) {
            $user = \Illuminate\Support\Facades\Auth::user();
            $dueTickets = [];
            if ($user && ($user->isAdmin() || $user->isSupport())) {
                $dueTickets = rescue(function () {
                    return \App\Models\Ticket::whereNotNull('order_type')
                        ->get()
                        ->filter(function($ticket) {
                            $activations = $ticket->order_activations;
                            if (empty($activations)) return false;
                            $last = \Illuminate\Support\Carbon::parse(collect($activations)->last());
                            $days = match(strtolower(trim($ticket->recurrence_period))) {
                                'daily'       => 1,
                                'one-week', 'weekly'     => 7,
                                'monthly'     => 30,
                                'two-weeks'   => 14,
                                'quarterly'   => 90,
                                'three-weeks' => 21,
                                'yearly'      => 365,
                                default       => 0,
                            };
                            if ($days === 0) return false;
                            $due = $last->addDays($days);
                            return \Illuminate\Support\Carbon::now()->diffInHours($due, false) <= 24;
                        })
                        ->map(function($ticket) {
                            $activations = $ticket->order_activations;
                            return (object)[
                                'id'              => $ticket->id,
                                'hashid'          => $ticket->hashid,
                                'subject'         => $ticket->subject,
                                'content'         => $ticket->content,
                                'last_activation' => collect($activations)->last(),
                                'period'          => $ticket->recurrence_period,
                            ];
                        })->values()->all();
                }, []);
            }
            $view->with('due_tickets', $dueTickets);
        });
    }
}
