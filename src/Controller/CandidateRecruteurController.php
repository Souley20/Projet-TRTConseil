<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CandidateRecruteurController extends AbstractController
{
    #[Route('/candidate/recruteur', name: 'app_candidate_recruteur')]
    public function index(): Response
    {
        return $this->render('candidate_recruteur/index.html.twig', [
            'controller_name' => 'CandidateRecruteurController',
        ]);
    }
}
