<?php

namespace App\Tests\Movie\Search\Omdb\Transformer;

use App\Entity\Movie;
use App\Movie\Search\Omdb\Transformer\OmdbToMovieTransformer;
use PHPUnit\Framework\TestCase;

class OmdbToMovieTransformerTest extends TestCase
{
    /**
     * @group unit
     */
    public function testTransformerReturnsMovieObject(): void
    {
        $data = $this->getMovieData();
        $transformer = new OmdbToMovieTransformer();
        $movie = $transformer->transform($data);

        $this->assertIsObject($movie);
        $this->assertInstanceOf(Movie::class, $movie, 'Transformer should return a Movie object');
    }

    /**
     * @group unit
     */
    public function testPropertiesAreSet(): void
    {
        $data = $this->getMovieData();
        $transformer = new OmdbToMovieTransformer();
        $movie = $transformer->transform($data);

        $this->assertSame('Star Wars', $movie->getTitle());
        $this->assertSame('http://...', $movie->getPoster());
        $this->assertSame('United States', $movie->getCountry());
        $this->assertSame('A long time ago, in a galaxy far, far away...', $movie->getPlot());
        $this->assertEquals(new \DateTimeImmutable('25-05-1977'), $movie->getReleasedAt());
    }

    /**
     * @group unit
     */
    public function testYearIsUsedWhenThereIsNoReleased(): void
    {
        $data = $this->getMovieData(false);
        $transformer = new OmdbToMovieTransformer();
        $movie = $transformer->transform($data);

        $this->assertSame('Star Wars', $movie->getTitle());
        $this->assertSame('http://...', $movie->getPoster());
        $this->assertSame('United States', $movie->getCountry());
        $this->assertSame('A long time ago, in a galaxy far, far away...', $movie->getPlot());
        $this->assertEquals(new \DateTimeImmutable('01-01-1977'), $movie->getReleasedAt());
    }

    /**
     * @group unit
     */
    public function testExceptionIsThrownWhenValueIsNotAnArray(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $transformer = new OmdbToMovieTransformer();
        $movie = $transformer->transform('foo');
    }

    /**
     * @group unit
     */
    public function testExceptionIsThrownWhenAnArrayKeyIsMissing(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $data = $this->getMovieData(true, true);
        $transformer = new OmdbToMovieTransformer();
        $movie = $transformer->transform($data);
    }

    public function getMovieData(bool $released = true, bool $missing = false): array
    {
        $data = [
            'Title' => 'Star Wars',
            'Poster' => 'http://...',
            'Country' => 'United States',
            'Plot' => 'A long time ago, in a galaxy far, far away...',
            'Released' => $released ? '25-05-1977' : 'N/A',
            'Year' => '1977',
            'imdbID' => 'tt2222222',
        ];

        if (!$missing) {
            $data['Rated'] = 'PG-13';
        }

        return $data;
    }
}
