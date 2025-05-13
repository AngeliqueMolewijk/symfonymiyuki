<?php

namespace App\Controller;

use App\Entity\Color;
use App\Repository\ColorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/color', name: 'app_color_')]

final class ColorController extends AbstractController
{

    #[Route('', name: 'index')]
    public function index(ColorRepository $colorRepository): Response
    {
        $colors = $colorRepository->findAll();
        return $this->render('color/index.html.twig', [
            'colors' => $colors,
        ]);
    }


    #[route('/{id}/show', name: 'show', methods: ['GET'])]
    public function showBeadColor(Color $color): Response
    {
        return $this->render('color/show.html.twig', [
            'color' => $color,
        ]);
    }
}
