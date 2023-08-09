<?php

namespace App\Movie\Search\Omdb\Transformer;

use App\Entity\Genre;
use Symfony\Component\Form\DataTransformerInterface;

class OmdbToGenreTransformer implements DataTransformerInterface
{
    public function transform(mixed $value)
    {
        if (!is_string($value)) {
            throw new \InvalidArgumentException();
        }

        return (new Genre())->setName($value);
    }

    public function reverseTransform(mixed $value)
    {
        throw new \LogicException('Not implemented.');
    }
}
