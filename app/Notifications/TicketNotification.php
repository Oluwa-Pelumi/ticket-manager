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
        $channels = ['mail'];
        
        $whatsappNumber = method_exists($notifiable, 'routeNotificationFor') 
            ? $notifiable->routeNotificationFor('whatsapp') 
            : ($notifiable->whatsapp_number ?? null);
            
        if (!empty($whatsappNumber)) {
            $channels[] = 'whatsapp';
        }

        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $content = is_string($this->message) ? $this->message : $this->message->content;
        $name = $notifiable->name ?? 'Guest';

        return (new MailMessage)
            ->subject($this->subject)
            ->greeting('Hello ' . $name . '!')
            ->line($content)
            ->line('Thank you for using our platform!');
    }

    public function toWhatsapp(object $notifiable): string
    {
        $content = is_string($this->message) ? $this->message : $this->message->content;
        $name = $notifiable->name ?? 'Guest';
        
        return "Hello {$name}!\n\n{$content}";
    }
}
