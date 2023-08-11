<?php

namespace App\Tests\Movie\Search\Omdb\Provider;

use App\Entity\Genre;
use App\Movie\Search\Omdb\Provider\GenreProvider;
use App\Movie\Search\Omdb\Transformer\OmdbToGenreTransformer;
use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GenreProviderTest extends KernelTestCase
{
    /**
     * @group unit
     */
    public function testGenreIsTakenFromDatabaseWhenItExists(): void
    {
        $kernel = self::bootKernel();
        $repository = $kernel->getContainer()->get('doctrine.orm.default_entity_manager')->getRepository(Genre::class);
        $provider = new GenreProvider($repository, new OmdbToGenreTransformer());
        $genre = $provider->getGenre('Action');

        $this->assertNotNull($genre->getId());
    }

    /**
     * @group unit
     */
    public function testGenreIsCreatedWhenItDoesntExists(): void
    {
        $kernel = self::bootKernel();
        $repository = $kernel->getContainer()->get('doctrine.orm.default_entity_manager')->getRepository(Genre::class);
        $provider = new GenreProvider($repository, new OmdbToGenreTransformer());
        $genre = $provider->getGenre('Foo');

        $this->assertNull($genre->getId());
    }
}
