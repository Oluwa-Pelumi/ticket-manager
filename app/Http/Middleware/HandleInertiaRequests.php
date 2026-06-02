<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'role' => $request->user()?->role,
            ],
            'due_tickets' => ($request->user()?->isAdmin() || $request->user()?->isSupport())
                ? rescue(fn() => \App\Models\Ticket::whereNotNull('order_type')
                    ->get()
                    ->filter(function($ticket) {
                        $activations = $ticket->order_activations;
                        if (empty($activations)) return false;
                        $last = \Illuminate\Support\Carbon::parse(end($activations));
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
                        // a notification shows when its less than 24hours to when next and order for that ticket should be reactivated
                        //e.g. if a ticket has weekly order, by the time its the sixth day the last order was processed, a notfication
                        // will appear to remind the support to precess a new order
                    })
                    ->map(function($ticket) {
                        $activations = $ticket->order_activations;
                        return [
                            'id'              => $ticket->id,
                            'hashid'          => $ticket->hashid,
                            'subject'         => $ticket->subject,
                            'content'         => $ticket->content,
                            'last_activation' => end($activations),
                            'period'          => $ticket->recurrence_period,
                        ];
                    })->values()->all(), [])
                : [],
            'flash' => [
                'error'   => fn () => $request->session()->get('error'),
                'success' => fn () => $request->session()->get('success'),
            ],
        ];
    }
}
