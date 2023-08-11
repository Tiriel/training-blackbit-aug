<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\User\InMemoryUser;

class MainControllerTest extends WebTestCase
{
    /**
     * @group e2e
     */
    public function testMainIndexIsDisplayedWithSixCards(): void
    {
        $client = static::createClient();
        $admin = static::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'john.doe@admin.com']);
        $client->loginUser($admin);
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'SensioTV+');
        $this->assertCount(6, $crawler->filter('.card'));
    }
}
