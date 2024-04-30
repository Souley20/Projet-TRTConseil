<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CandidaturevalidController extends AbstractController
{
    #[Route('/candidaturevalid', name: 'app_candidaturevalid')]
    public function index(): Response
    {
        return $this->render('candidaturevalid/index.html.twig', [
            'controller_name' => 'CandidaturevalidController',
        ]);
    }
}
