<?php

namespace App\Infrastructure\Mobile;

use App\Domain\Interfaces\PushNotificationSender;

class PushNotificator implements PushNotificationSender
{
    /**
     * @var string
     */
    private $apiKey = '';

    public function sendPushNotification(string $token, array $payload): bool
    {
        $this->send($token, $payload);

        return true;
    }

    private function send(string $token, array $payload)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: key=' . $this->apiKey,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'to' => $token,
            'content_available' => true,
            'data' => $payload,
        ]));
        $result = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $code === 200;
    }
}
