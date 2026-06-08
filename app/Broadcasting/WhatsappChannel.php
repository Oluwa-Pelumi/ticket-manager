<?php

namespace App\Broadcasting;

use Illuminate\Support\Facades\Http;
use App\Notifications\TicketNotification;

/**
 * Custom notification channel that sends ticket alerts via the Meta WhatsApp Cloud API.
 */
class WhatsappChannel
{
    /**
     * Send a WhatsApp template message for the given notification.
     */
    public function send(object $notifiable, TicketNotification $notification): void
    {
        // --- Resolve recipient ---
        $whatsappNumber = method_exists($notifiable, 'routeNotificationFor')
            ? $notifiable->routeNotificationFor('whatsapp')
            : $notifiable->whatsapp_number;

        $to   = preg_replace('/\D/', '', $whatsappNumber);              // Ensure digits only
        $name = $notification->recipientName ?? $notifiable->name ?? 'there';

        // --- Load Meta API credentials ---
        $phoneId      = config('services.meta_whatsapp.phone_id');
        $token        = config('services.meta_whatsapp.token');
        $rawVersion   = config('services.meta_whatsapp.version');

        $version      = str_starts_with($rawVersion, 'v') ? $rawVersion : "v{$rawVersion}";

        // --- Build template body parameters ---
        $components = [
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
        ];

        // Append button component only for templates that have URL buttons configured
        if (!empty($notification->ticketUrl) &&
        ($notification->templateName === 'ticket_submitted'
        || $notification->templateName === 'ticket_is_replied')) {
            $components[] = [
                'type'       => 'button',
                'sub_type'   => 'url',
                'index'      => '0',
                'parameters' => [
                    [
                        'type' => 'text',
                        'text' => $notification->ticketUrl,
                    ],
                ],
            ];
        }

        // --- Assemble and send API request ---
        $payload = [
            'to'                => $to,
            'messaging_product' => 'whatsapp',
            'type'              => 'template',
            'template'          => [
                'components'    => $components,
                'language'      => ['code' => 'en'],
                'name'          => $notification->templateName,   // Dynamic template name
            ],
        ];

        $response = Http::withToken($token)
            ->post("https://graph.facebook.com/{$version}/{$phoneId}/messages", $payload);

        // --- Log outcome ---
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
