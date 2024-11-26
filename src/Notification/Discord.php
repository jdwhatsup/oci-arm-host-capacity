<?php

namespace Hitrov\Notification;

use Hitrov\Exception\ApiCallException;
use Hitrov\Exception\CurlException;
use Hitrov\Exception\NotificationException;
use Hitrov\HttpClient;
use Hitrov\Interfaces\NotifierInterface;
use JsonException;

class Discord implements NotifierInterface
{
    /**
     * @param string $message
     * @return array
     * @throws ApiCallException|CurlException|JsonException|NotificationException
     */
    public function notify(string $message): array
    {
        $webhookUrl = getenv('DISCORD_WEBHOOK_URL');

        $body = json_encode([
            'content' => $message,
        ], JSON_THROW_ON_ERROR);

        $curlOptions = [
            CURLOPT_URL => $webhookUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 1,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
            ],
        ];

        return HttpClient::getResponse($curlOptions);
    }

    public function isSupported(): bool
    {
        return !empty(getenv('DISCORD_WEBHOOK_URL'));
    }
}
