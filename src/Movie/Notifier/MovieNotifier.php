<?php

namespace App\Movie\Notifier;

use App\Movie\Notifier\Factory\NotificationFactoryInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedLocator;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

class MovieNotifier
{
    /** @var ContainerInterface<NotificationFactoryInterface> */
    private ContainerInterface $locator;
    public function __construct(
        private readonly NotifierInterface $notifier,
        #[TaggedLocator('app.notification_factory', defaultIndexMethod: 'getIndex')]
        ContainerInterface $locator
    ) {
        $this->locator = $locator;
    }

    public function sendNewMovieNotification(string $title): void
    {
        $user = new class {
            public function getEmail(): string
            {
                return 'me@me.com';
            }

            public function getPreferredChannel(): string
            {
                return 'slack';
            }
        };

        $msg = sprintf("The movie \"%s\" has been added to the database!", $title);
        $notification = $this->locator->get($user->getPreferredChannel())->createNotification($msg);

        $this->notifier->send($notification, new Recipient($user->getEmail()));
    }
}
