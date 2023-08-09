<?php

namespace App\Movie\Search\Omdb\Provider;

use App\Entity\Movie;
use App\Movie\Search\Omdb\OmdbApiConsumer;
use App\Movie\Search\Omdb\Transformer\OmdbToMovieTransformer;
use App\Movie\Search\SearchTypes;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;

class MovieProvider
{
    public function __construct(
        private readonly OmdbApiConsumer $consumer,
        private readonly EntityManagerInterface $manager,
        private readonly OmdbToMovieTransformer $transformer,
        private readonly GenreProvider $genreProvider,
    ) {}

    public function getMovieByTitle(string $title): Movie
    {
        return $this->getMovie(SearchTypes::Title, $title);
    }

    public function getMovie(SearchTypes $type, string $value): Movie
    {
        $data = $this->consumer->fetchMovie($type, $value);

        if ($movie = $this->manager->getRepository(Movie::class)->findOneBy(['title' => $data['Title']])) {
            return $movie;
        }

        $movie = $this->transformer->transform($data);

        foreach ($this->genreProvider->getFromOmdbString($data['Genre']) as $genre) {
            $movie->addGenre($genre);
        }

        $this->manager->persist($movie);
        $this->manager->flush();

        return $movie;
    }
}
