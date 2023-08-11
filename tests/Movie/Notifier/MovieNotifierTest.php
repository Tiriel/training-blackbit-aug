<?php

namespace App\Tests\Movie\Notifier;

use App\Movie\Notifier\Factory\DiscordNotificationFactory;
use App\Movie\Notifier\Factory\SlackNotificationFactory;
use App\Movie\Notifier\MovieNotifier;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\Notifier\Notifier;

class MovieNotifierTest extends TestCase
{
    private static ContainerInterface $container;

    public static function setUpBeforeClass(): void
    {
        $factories = [
            'slack' => new SlackNotificationFactory(),
            'discord' => new DiscordNotificationFactory(),
        ];
        static::$container = new ServiceLocator($factories);
    }

    public function testNotifierThrowsAndExceptionOnNonExistentFactory(): void
    {
        $this->markTestSkipped();
        $this->expectException(\InvalidArgumentException::class);
    }
}
