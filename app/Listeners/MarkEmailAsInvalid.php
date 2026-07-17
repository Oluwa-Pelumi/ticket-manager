<?php

namespace App\Listeners;

use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Support\Facades\Log;

class MarkEmailAsInvalid
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NotificationFailed $event): void
    {
        // Only handle mail channel failures
        if ($event->channel !== 'mail') {
            return;
        }

        $notifiable = $event->notifiable;

        // Only mark as invalid if the notifiable is a User model
        if (!method_exists($notifiable, 'update') || !property_exists($notifiable, 'email_invalid')) {
            return;
        }

        // Extract error message from exception
        $errorMessage = 'Email delivery failed';
        if ($event->exception) {
            $errorMessage = $event->exception->getMessage();
        }

        // Mark email as invalid
        $notifiable->email_invalid = true;
        $notifiable->email_invalid_reason = $errorMessage;
        $notifiable->save();

        Log::warning('Email marked as invalid', [
            'user_id' => $notifiable->id,
            'email' => $notifiable->email,
            'error' => $errorMessage,
        ]);
    }
}
