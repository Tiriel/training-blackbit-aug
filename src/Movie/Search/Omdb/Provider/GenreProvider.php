<?php

namespace App\Movie\Search\Omdb\Provider;

use App\Entity\Genre;
use App\Movie\Search\Omdb\Transformer\OmdbToGenreTransformer;
use App\Repository\GenreRepository;

class GenreProvider
{
    public function __construct(
        private readonly GenreRepository $repository,
        private readonly OmdbToGenreTransformer $transformer,
    ) {}

    public function getGenre(string $name): Genre
    {
        return $this->repository->findOneBy(['name' => $name])
            ?? $this->transformer->transform($name);
    }

    public function getFromOmdbString(string $genreNames): iterable
    {
        foreach (explode(', ', $genreNames) as $name) {
            yield $this->getGenre($name);
        }
    }
}
