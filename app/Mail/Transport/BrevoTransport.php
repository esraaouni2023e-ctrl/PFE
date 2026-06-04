<?php

namespace App\Mail\Transport;

use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BrevoTransport extends AbstractTransport
{
    protected string $apiKey;

    public function __construct(string $apiKey)
    {
        parent::__construct();
        $this->apiKey = $apiKey;
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());
        
        $to = [];
        foreach ($email->getTo() as $address) {
            $to[] = [
                'email' => $address->getAddress(),
                'name' => $address->getName() ?: null,
            ];
        }

        $fromAddress = $email->getFrom()[0] ?? null;
        $sender = [
            'email' => $fromAddress ? $fromAddress->getAddress() : config('mail.from.address'),
            'name' => $fromAddress ? ($fromAddress->getName() ?: config('mail.from.name')) : config('mail.from.name'),
        ];

        $payload = [
            'sender' => $sender,
            'to' => $to,
            'subject' => $email->getSubject(),
        ];

        if ($email->getHtmlBody()) {
            $payload['htmlContent'] = $email->getHtmlBody();
        }
        
        if ($email->getTextBody()) {
            $payload['textContent'] = $email->getTextBody();
        }

        // Send request to Brevo API with timeout of 5 seconds and force IPv4 to avoid IPv6 resolution delays on Render
        $response = Http::timeout(5)
            ->withOptions([
                'curl' => [
                    defined('CURLOPT_IPRESOLVE') ? CURLOPT_IPRESOLVE : 113 => defined('CURL_IPRESOLVE_V4') ? CURL_IPRESOLVE_V4 : 1
                ]
            ])
            ->withHeaders([
                'api-key' => $this->apiKey,
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ])->post('https://api.brevo.com/v3/smtp/email', $payload);

        if (!$response->successful()) {
            Log::error('Brevo API Mail Error: ' . $response->body());
            throw new \Exception('Failed to send email via Brevo API: ' . $response->body());
        }
    }

    public function __toString(): string
    {
        return 'brevo';
    }
}
