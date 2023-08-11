<?php

namespace App\Tests\Movie\Search\Omdb\Provider;

use App\Entity\Movie;
use App\Movie\Search\Omdb\OmdbApiConsumer;
use App\Movie\Search\Omdb\Provider\GenreProvider;
use App\Movie\Search\Omdb\Provider\MovieProvider;
use App\Movie\Search\Omdb\Transformer\OmdbToMovieTransformer;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;

class MovieProviderTest extends TestCase
{
    /**
     * @group unit
     */
    public function testProviderReturnsMovieFromDatabaseWhenExists(): void
    {
        $movieRepo = $this->getMockWithMethods(MovieRepository::class, ['findOneBy' => ['return' => (new Movie())->setTitle('Star Wars')]]);
        $movieProvider = new MovieProvider(
            $this->getMockWithMethods(OmdbApiConsumer::class, ['fetchMovie' => ['return' => ['Title' => 'Star Wars']]]),
            $this->getMockWithMethods(EntityManager::class, ['getRepository' => ['return' => $movieRepo]]),
            $this->createMock(OmdbToMovieTransformer::class),
            $this->createMock(GenreProvider::class),
            $this->createMock(Security::class),
        );

        $movie = $movieProvider->getMovieByTitle('Star Wars');

        $this->assertInstanceOf(Movie::class, $movie);
        $this->assertSame('Star Wars', $movie->getTitle());
    }

    /**
     * @group unit
     */
    public function testProviderCreatesMovieWhenNotExistsInDatabase(): void
    {
        $movieRepo = $this->getMockWithMethods(MovieRepository::class, ['findOneBy' => ['return' => null]]);
        /** @var MockObject|EntityManagerInterface $manager */
        $manager = $this->getMockWithMethods(
            EntityManager::class,
            [
                'getRepository' => ['return' => $movieRepo],
                'persist' => ['return' => null],
                'flush' => ['return' => null]
            ]
        );
        $movieProvider = new MovieProvider(
            $this->getMockWithMethods(OmdbApiConsumer::class, ['fetchMovie' => ['return' => ['Title' => 'Star Wars', 'Genre' => '']]]),
            $manager,
            $this->getMockWithMethods(OmdbToMovieTransformer::class, ['transform' => ['return' => (new Movie())->setTitle('Star Wars')]]),
            $this->getMockWithMethods(GenreProvider::class, ['getFromOmdbString' => ['return' => []]]),
            $this->createMock(Security::class),
        );

        $movie = $movieProvider->getMovieByTitle('Star Wars');

        $this->assertInstanceOf(Movie::class, $movie);
        $this->assertSame('Star Wars', $movie->getTitle());
    }

    /**
     * @param string $classname
     * @param array<string, array>|null $methods
     * @return MockObject
     */
    public function getMockWithMethods(string $classname, ?array $methods = null): MockObject
    {
        $builder = $this->getMockBuilder($classname)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes();

        if ($methods) {
            $builder->onlyMethods(array_keys($methods));
        }
        $mock = $builder->getMock();

        foreach ($methods as $method => $attributes) {
            $expects = array_key_exists('expects', $attributes) ? $attributes['expects'] : 'once';
            $mock
                ->expects($this->$expects())
                ->method($method)
                ->willReturn($attributes['return']);
        }

        return $mock;
    }
}
