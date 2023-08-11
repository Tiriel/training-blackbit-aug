<?php

namespace App\Tests\Movie\Search\Omdb\Provider;

use App\Entity\Genre;
use App\Movie\Search\Omdb\Provider\GenreProvider;
use App\Movie\Search\Omdb\Transformer\OmdbToGenreTransformer;
use App\Repository\GenreRepository;
use App\Tests\Traits\EntityIdSetterTrait;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

class GenreProviderTest extends KernelTestCase
{
    use EntityIdSetterTrait;

    /**
     * @group unit
     */
    public function testGenreIsTakenFromDatabaseWhenItExists(): void
    {
        $repository = $this->getMockRepository();
        $provider = new GenreProvider($repository, new OmdbToGenreTransformer());
        $genre = $provider->getGenre('Action');

        $this->assertNotNull($genre->getId());
        $this->assertInstanceOf(UuidV4::class, $genre->getId());
    }

    /**
     * @group unit
     */
    public function testGenreIsCreatedWhenItDoesntExists(): void
    {
        $repository = $this->getMockRepository(false);
        $provider = new GenreProvider($repository, new OmdbToGenreTransformer());
        $genre = $provider->getGenre('Foo');

        $this->assertNull($genre->getId());
    }

    public function getMockRepository(bool $return = true): MockObject|GenreRepository
    {
        $repository = $this->getMockBuilder(GenreRepository::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->onlyMethods(['findOneBy'])
            ->getMock()
            ;

        if ($return) {
            $genre = (new Genre())->setName('Action');
            $repository->method('findOneBy')
                ->willReturn($this->setId($genre, Uuid::v4()))
                ;
        }

        return $repository;
    }
}
