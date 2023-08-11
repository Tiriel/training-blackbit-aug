<?php

namespace App\Movie\Search\Omdb\Transformer;

use App\Entity\Movie;
use Symfony\Component\Form\DataTransformerInterface;

class OmdbToMovieTransformer implements DataTransformerInterface
{
    private const KEYS = [
        'Title',
        'Poster',
        'Country',
        'Plot',
        'Released',
        'Year',
        'imdbID',
        'Rated',
    ];

    public function transform(mixed $value): Movie
    {
        if (!is_array($value) || \count(array_diff(self::KEYS, array_keys($value))) > 0) {
            throw new \InvalidArgumentException();
        }

        $date = 'N/A' === $value['Released'] ? '01-01-'.$value['Year'] : $value['Released'];

        return (new Movie())
            ->setTitle($value['Title'])
            ->setPoster($value['Poster'])
            ->setCountry($value['Country'])
            ->setPlot($value['Plot'])
            ->setImdbId($value['imdbID'])
            ->setRated($value['Rated'])
            ->setReleasedAt(new \DateTimeImmutable($date))
            ;
    }

    public function reverseTransform(mixed $value): mixed
    {
        throw new \LogicException('Not implemented.');
    }
}
