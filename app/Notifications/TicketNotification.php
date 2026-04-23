<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public string $subject, public string|object $message)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'whatsapp'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $content = is_string($this->message) ? $this->message : $this->message->content;

        return (new MailMessage)
            ->subject($this->subject)
            ->greeting('Hello ' . $notifiable->name, '!')
            ->line($content)
            ->line('Thank you for using our platform!');
    }

    public function toWhatsapp(object $notifiable): string
    {
        $content = is_string($this->message) ? $this->message : $this->message->content;
        return "Hello {$notifiable->name}!\n\n{$content}";
    }
}
