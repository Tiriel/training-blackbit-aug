<?php

namespace App\Tests\Book;

use App\Book\BookManager;
use App\Entity\Book;
use App\Repository\BookRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\SecurityBundle\Security;

class BookManagerTest extends TestCase
{
    /**
     * @group unit
     */
    public function testFindByTitleFindsBookByPartialMatch(): void
    {
        $book = (new Book())
            ->setTitle("Le Seigneur des anneaux T1: La Fraternité de l’anneau");

        $repository = $this->getMockBuilder(BookRepository::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->onlyMethods(['findByApproxTitle'])
            ->getMock();
        $repository->method('findByApproxTitle')->willReturn($book);

        $security = $this->createMock(Security::class);
        $manager = new BookManager($repository, 20, $security);

        $lotrBook = $manager->findByTitle('Fraternité');

        $this->assertSame($lotrBook, $book);
        $this->assertSame("Le Seigneur des anneaux T1: La Fraternité de l’anneau", $lotrBook->getTitle());
    }
}
