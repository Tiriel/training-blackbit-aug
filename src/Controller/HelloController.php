<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    #[Route('/hello/{name}', name: 'app_hello_index', requirements: ['name' => '[a-zA-Z- ]+'], defaults: ['name' => 'World'])]
    public function index(string $name): Response
    {
        return $this->render('hello/subtitle.html.twig', [
            'controller_name' => $name,
        ]);
    }
}
