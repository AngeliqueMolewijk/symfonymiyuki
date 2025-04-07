<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BeadController extends AbstractController
{
    #[Route('')]
    public function homepage(): Response
    {
        return $this->render('bead/homepage.html.twig');
    }
}
