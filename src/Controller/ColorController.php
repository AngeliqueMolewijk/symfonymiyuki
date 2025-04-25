<?php

namespace App\Controller;

use App\Entity\Color;
use App\Repository\ColorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ColorController extends AbstractController
{

    #[Route('/color', name: 'app_color')]
    public function index(ColorRepository $colorRepository): Response
    {
        $colors = $colorRepository->findAll();
        return $this->render('color/index.html.twig', [
            'colors' => $colors,
        ]);
    }


    #[route('/beadscolor/{id}', name: 'beadscolor', methods: ['GET'])]
    public function showBeadColor(Color $color)
    {
        return $this->render('color/show.html.twig', [
            'color' => $color,
        ]);
    }
}
