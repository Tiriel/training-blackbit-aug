<?php

namespace App\Tests\Controller;

use App\Repository\MovieRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MovieControllerTest extends WebTestCase
{
    /**
     * @group e2e
     */
    public function testMovieIndexDisplaysTitleAndRightNumberOfCards(): void
    {
        $client = static::createClient();
        $count = static::getContainer()->get(MovieRepository::class)->count([]);
        $user = static::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'john.doe@admin.com']);
        $client->loginUser($user);
        $crawler = $client->request('GET', '/movie');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Available movies');
        $this->assertCount($count, $crawler->filter('.card'));
    }

    /**
     * @group e2e
     */
    public function testMovieCardsAreDisplayedInTheRightOrder(): void
    {

        $client = static::createClient();
        $user = static::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'john.doe@admin.com']);
        $client->loginUser($user);
        $crawler = $client->request('GET', '/movie');
        $title = $crawler->filter('.card')->eq(4)->filter('.card-title')->innerText();

        $this->assertResponseIsSuccessful();
        $this->assertSame('The Matrix', $title);
    }
}
