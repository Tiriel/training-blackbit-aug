<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/movie')]
class MovieController extends AbstractController
{
    #[Route('', name: 'app_movie_index')]
    public function index(): Response
    {
        return $this->render('movie/index.html.twig', [
            'controller_name' => 'MovieController',
        ]);
    }

    #[Route('/{id}', name: 'app_movie_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(int $id): Response
    {
        $movie = [
            'id' => $id,
            'title' => 'Star Wars Episode IV - A New Hope',
            'releasedAt' => new \DateTimeImmutable('1977-05-25'),
            'country' => 'United States',
            'genres' => [
                'Action',
                'Adventure',
                'Fantasy',
            ]
        ];

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    public function decadesMenu(): Response
    {
        $decades = [
            '1980',
            '1990',
            '2010',
        ];

        return $this->render('includes/_decades.html.twig', [
            'decades' => $decades,
        ])->setMaxAge(3600);
    }
}
