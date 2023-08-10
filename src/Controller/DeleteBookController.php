<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DeleteBookController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/book/{id<\d+>}/delete', name: 'app_book_delete')]
    public function __invoke(int $id): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'DeleteBookController - id : '.$id
        ]);
    }
}
