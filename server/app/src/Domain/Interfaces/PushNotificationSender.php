<?php

namespace App\Domain\Interfaces;

interface PushNotificationSender
{
    public function sendPushNotification(string $token, array $payload): bool;
}