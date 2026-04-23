<?php

namespace App\Broadcasting;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use App\Notifications\TicketNotification;

class WhatsappChannel
{
    public function send(object $notifiable, TicketNotification $notification): void
    {
        $message      = $notification->toWhatsapp($notifiable);
        $cleanMessage = preg_replace('/\s+/', ' ', trim($message));
        $to           = preg_replace('/\D/', '', $notifiable->whatsapp_number);              // Ensure digits only
        $phoneId      = config('services.meta_whatsapp.phone_id');
        $token        = config('services.meta_whatsapp.token');
        $rawVersion   = config('services.meta_whatsapp.version');
        $version      = str_starts_with($rawVersion, 'v') ? $rawVersion : "v{$rawVersion}";

        $payload = [
            'messaging_product' => 'whatsapp',
            'to'                => $to,
            'type'              => 'template',
            'template'          => [
                'name'     => 'ticket_submitted',  // your template name
                'language' => ['code' => 'en_US'],
                'components' => [
                    [
                        'type'       => 'body',
                        'parameters' => [
                            [
                                'type' => 'text',
                                'text' => $notifiable->name,  // {{1}} patient name
                            ],
                            [
                                'type' => 'text',
                                'text' => $notification->toWhatsApp($notifiable), // {{2}} subject
                            ],
                            [
                                'type' => 'text',
                                'text' => $cleanMessage, // {{3}} content
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
                'status' => $response->status(),
                'body'   => $response->json(),
                'to'     => $to
            ]);
        } else {
            \Log::info('WhatsApp message sent successfully', ['to' => $to]);
        }
    }
}
