<?php

namespace App\Tests\Movie\Search\Omdb\Transformer;

use App\Entity\Genre;
use App\Movie\Search\Omdb\Transformer\OmdbToGenreTransformer;
use PHPUnit\Framework\TestCase;

class OmdbToGenreTransformerTest extends TestCase
{
    /**
     * @group unit
     */
    public function testTransformerReturnsGenreObject(): void
    {
        $transformer = new OmdbToGenreTransformer();
        $genre = $transformer->transform('Action');

        $this->assertIsObject($genre);
        $this->assertInstanceOf(Genre::class, $genre, 'Transformer should return a Genre object');
    }

    /**
     * @group unit
     */
    public function testNamePropertyIsSet(): void
    {

        $transformer = new OmdbToGenreTransformer();
        $genre = $transformer->transform('Action');

        $this->assertSame('Action', $genre->getName());
    }

    /**
     * @group unit
     */
    public function testExceptionIsThrownWhenValueIsNotAString(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $transformer = new OmdbToGenreTransformer();
        $genre = $transformer->transform(10);
    }
}
