<?php

namespace App\Movie\Search\Omdb\Provider;

use App\Entity\Movie;
use App\Entity\User;
use App\Movie\Search\Omdb\OmdbApiConsumer;
use App\Movie\Search\Omdb\Transformer\OmdbToMovieTransformer;
use App\Movie\Search\SearchTypes;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Console\Style\SymfonyStyle;

class MovieProvider
{
    private ?SymfonyStyle $io = null;

    public function __construct(
        private readonly OmdbApiConsumer $consumer,
        private readonly EntityManagerInterface $manager,
        private readonly OmdbToMovieTransformer $transformer,
        private readonly GenreProvider $genreProvider,
        private readonly Security $security,
    ) {}

    public function getMovieByTitle(string $title): Movie
    {
        return $this->getMovie(SearchTypes::Title, $title);
    }

    public function getMovie(SearchTypes $type, string $value): Movie
    {
        $this->io?->text('Fetching from OMDb API...');
        $data = $this->consumer->fetchMovie($type, $value);

        if ($movie = $this->manager->getRepository(Movie::class)->findOneBy(['title' => $data['Title']])) {
            $this->io?->note('Movie already in database!');

            return $movie;
        }

        $this->io?->text('Creating the Movie object...');
        $movie = $this->transformer->transform($data);

        foreach ($this->genreProvider->getFromOmdbString($data['Genre']) as $genre) {
            $movie->addGenre($genre);
        }

        if (($user = $this->security->getUser()) instanceof User) {
            $movie->setCreatedBy($user);
        }

        $this->io?->text('Saving in database...');
        $this->manager->persist($movie);
        $this->manager->flush();
        $this->io?->info('Movie saved!');

        return $movie;
    }

    public function setIo(?SymfonyStyle $io): void
    {
        $this->io = $io;
    }
}
