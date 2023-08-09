<?php

namespace App\Book;

use App\Entity\Book;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;

class TestBookManager implements ManagerInterface
{
    public function __construct(
        private readonly ManagerInterface $inner
    ) {}

    public function findByTitle(string $title): Book
    {
        return $this->inner->findByTitle($title);
    }

    public function getPaginated(int $offset): iterable
    {
        return $this->getPaginated($offset);
    }
}
