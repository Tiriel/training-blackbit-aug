<?php

namespace App\Book;

use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class BookManager
{
    public function __construct(
        private readonly BookRepository $repository,
        private readonly int $booksPerPage
    ) {}

    public function findByTitle(string $title): Book
    {
        return $this->repository->findByApproxTitle($title);
    }

    public function getPaginated(int $offset): iterable
    {
        return $this->repository->findBy([], ['id' => 'DESC'], $this->booksPerPage, $offset);
    }
}
