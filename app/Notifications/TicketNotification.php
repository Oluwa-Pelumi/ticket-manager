<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Queued notification for ticket lifecycle events via email.
 */
class TicketNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public string $subject,
        public string|object $message,
        public ?string $ticketUrl     = null,
        public ?string $recipientName = null,
        public string $templateName   = 'ticket_submitted',
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $content = is_string($this->message) ? $this->message : (is_object($this->message) && method_exists($this->message, '__toString') ? (string) $this->message : 'Ticket notification');
        $name    = $this->recipientName ?? $notifiable->name ?? 'there';

        return (new MailMessage)
            ->subject($this->subject)
            ->view('emails.ticket_notification', [
                'notificationMessage' => $content,
                'recipientName' => $name,
                'subject' => $this->subject,
                'ticketUrl' => $this->ticketUrl,
            ]);
    }
}
