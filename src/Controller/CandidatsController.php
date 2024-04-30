<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CandidatsController extends AbstractController
{
    #[Route('/candidats', name: 'app_candidats')]
    public function index(): Response
    {
        return $this->render('candidats/index.html.twig', [
            'controller_name' => 'CandidatsController',
        ]);
    }
}
