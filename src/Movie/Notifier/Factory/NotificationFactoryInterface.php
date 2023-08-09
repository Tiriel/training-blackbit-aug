<?php

namespace App\Movie\Notifier\Factory;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Notifier\Notification\Notification;

#[AutoconfigureTag('app.notificationFactory')]
interface NotificationFactoryInterface
{
    public function createNotification(string $subject): Notification;

    public static function getIndex(): string;
}
