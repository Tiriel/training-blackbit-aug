<?php

namespace App\Book;

use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Bundle\SecurityBundle\Security;

class BookManager implements ManagerInterface
{
    public function __construct(
        private readonly BookRepository $repository,
        private readonly int $booksPerPage,
        private readonly Security $security
    ) {}

    public function findByTitle(string $title): Book
    {
        if ($this->security->isGranted('ROLE_BOOKWORM')) {
            dump('Well done!');
        }

        return $this->repository->findByApproxTitle($title);
    }

    public function getPaginated(int $offset): iterable
    {
        return $this->repository->findBy([], ['id' => 'DESC'], $this->booksPerPage, $offset);
    }
}
