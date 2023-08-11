<?php

namespace App\Tests\Movie\Search\Omdb;

use App\Movie\Search\Omdb\OmdbApiConsumer;
use App\Movie\Search\SearchTypes;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OmdbApiConsumerTest extends TestCase
{
    /**
     * @group unit
     */
    public function testConsumerReturnsDataWithExistingMovieTitle(): void
    {
        $response = new MockResponse(json_encode(['Title' => 'Star Wars']), ['http_code' => 200]);
        $client = new MockHttpClient($response, 'http://www.omdbapi.com');
        $consumer = new OmdbApiConsumer($client);

        $data = $consumer->fetchMovie(SearchTypes::Title, 'Star Wars');

        $this->assertArrayHasKey('Title', $data);
        $this->assertSame('Star Wars', $data['Title']);
        $requestUrl = $response->getRequestUrl();
        $this->assertSame('http://www.omdbapi.com/?t=Star%20Wars&plot=full', $requestUrl);
    }

    /**
     * @group unit
     */
    public function testConsumerThrowsOnErrorKey(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $response = new MockResponse(json_encode(['Error' => 'Movie not found!']), ['http_code' => 200]);
        $client = new MockHttpClient($response, 'http://www.omdbapi.com');
        $consumer = new OmdbApiConsumer($client);

        $data = $consumer->fetchMovie(SearchTypes::Title, 'dsflkjlkj');

        $requestUrl = $response->getRequestUrl();
        $this->assertSame('http://www.omdbapi.com/?t=dsflkjlkj&plot=full', $requestUrl);
    }
}
