<?php

namespace App\Tests\Book;

use App\Book\BookManager;
use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\SecurityBundle\Security;

class BookManagerTest extends KernelTestCase
{
    /**
     * @group unit
     */
    public function testFindByTitleFindsBookByPartialMatch(): void
    {
        $repository = static::getContainer()->get(BookRepository::class);
        $security = static::getContainer()->get('security.helper');
        $manager = new BookManager($repository, 20, $security);

        $lotrBook = $manager->findByTitle('Fraternité');

        $this->assertNotNull($lotrBook->getId());
        $this->assertSame("Le Seigneur des anneaux T1: La Fraternité de l’anneau", $lotrBook->getTitle());
    }
}
