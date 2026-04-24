<?php

namespace App\Broadcasting;

use Illuminate\Support\Facades\Http;
use App\Notifications\TicketNotification;

class WhatsappChannel
{
    public function send(object $notifiable, TicketNotification $notification): void
    {
        $whatsappNumber = method_exists($notifiable, 'routeNotificationFor')
            ? $notifiable->routeNotificationFor('whatsapp')
            : $notifiable->whatsapp_number;

        $to           = preg_replace('/\D/', '', $whatsappNumber);              // Ensure digits only
        $name         = $notifiable->name ?? 'Guest';

        $phoneId      = config('services.meta_whatsapp.phone_id');
        $token        = config('services.meta_whatsapp.token');
        $rawVersion   = config('services.meta_whatsapp.version');

        $version      = str_starts_with($rawVersion, 'v') ? $rawVersion : "v{$rawVersion}";

        $payload = [
            'messaging_product' => 'whatsapp',
            'to'                => $to,
            'type'              => 'template',
            'template'          => [
                'name'       => 'ticket_submitted',    // your template name
                'language'   => ['code' => 'en'],
                'components' => [
                    [
                        'type'       => 'body',
                        'parameters' => [
                            [
                                'type' => 'text',
                                'text' => $name,  // {{1}} patient name
                            ],
                            [
                                'type' => 'text',
                                'text' => preg_replace('/\s+/', ' ', trim($notification->subject)), // {{2}} subject
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $response = Http::withToken($token)
            ->post("https://graph.facebook.com/{$version}/{$phoneId}/messages", $payload);

        if ($response->failed()) {
            \Log::error('WhatsApp API Error', [
                'to'     => $to,
                'body'   => $response->json(),
                'status' => $response->status(),
            ]);
        } else {
            \Log::info('WhatsApp message sent successfully', ['to' => $to]);
        }
    }
}
