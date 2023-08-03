<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Comment;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('', name: 'app_book_index', methods: ['GET'])]
    public function index(BookRepository $repository): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $repository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_book_new', methods: ['GET', 'POST'])]
    public function new(EntityManagerInterface $manager): Response
    {
        //$book = (new Book())
        //    ->setTitle('1984')
        //    ->setAuthor('G.Orwell')
        //    ->setReleasedAt(new \DateTimeImmutable('1946-01-01'))
        //    ->setPlot('...')
        //    ->setCover('http://...')
        //    ->setEditor('...')
        //    ->addComment((new Comment())
        //        ->setName('Ben')
        //        ->setMessage('This book is too real')
        //        ->setCreatedAt(new \DateTimeImmutable())
        //    )
        //    ;
        //
        //$manager->persist($book);
        //$manager->flush();

        return $this->render('book/new.html.twig', []);
    }

    #[Route('/{id}',
        name: 'app_book_show',
        requirements: ['id' => '\d+'],
        defaults: ['id' => 2],
        methods: ['GET'],
        //condition: "request.headers.get('X-Custom-Header') == 'foo'"
    )]
    public function show(int $id = 1): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController::show - id : '.$id,
        ]);
    }

    #[Route('/{title}',
        name: 'app_book_title',
        methods: ['GET'],
    )]
    public function title(string $title, BookRepository $repository): Response
    {
        $book = $repository->findByApproxTitle($title);

        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController::title : '.$book->getTitle(),
        ]);
    }
}
