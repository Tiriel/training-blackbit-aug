<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteBookController extends AbstractController
{
    #[Route('/book/{id<\d+>}/delete', name: 'app_book_delete')]
    public function __invoke(int $id): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'DeleteBookController - id : '.$id
        ]);
    }
}
