<?php

namespace App\Broadcasting;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use App\Notifications\TicketNotification;

class WhatsappChannel
{
    public function send(object $notifiable, TicketNotification $notification): void
    {
        $message = $notification->toWhatsapp($notifiable);
        $to      = $notifiable->whatsapp_number;
        $phoneId = config('services.meta_whatsapp.phone_id');
        $token   = config('services.meta_whatsapp.token');
        $version = config('services.meta_whatsapp.version');

        Http::withToken($token)
            ->post("https://graph.facebook.com/{$version}/{$phoneId}/messages", [
                'messaging_product' => 'whatsapp',
                'to'                => $to,
                'type'              => 'template',
                'template'          => [
                    'name'     => 'your_approved_template_name',
                    'language' => ['code' => 'en_US'],
                    'components' => [
                        [
                            'type'       => 'body',
                            'parameters' => [
                                ['type' => 'text', 'text' => $notifiable->name],
                                ['type' => 'text', 'text' => $message],
                            ],
                        ],
                    ],
                ],
            ]);
    }
}
