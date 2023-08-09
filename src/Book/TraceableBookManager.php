<?php

namespace App\Book;

use App\Entity\Book;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\When;

#[When('dev')]
#[AsDecorator(BookManager::class)]
class TraceableBookManager implements ManagerInterface
{
    public function __construct(
        private readonly BookManager $inner,
        private readonly LoggerInterface $logger,
    ) {}

    public function findByTitle(string $title): Book
    {
        $this->logger->log('info', 'searching book by title '.$title);

        return $this->inner->findByTitle($title);
    }

    public function getPaginated(int $offset): iterable
    {
        $this->logger->log('info', 'Getting paginated book list');

        return $this->inner->getPaginated($offset);
    }
}
