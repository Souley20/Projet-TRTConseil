<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecruteursController extends AbstractController
{
    #[Route('/recruteurs', name: 'app_recruteurs')]
    public function index(): Response
    {
        return $this->render('recruteurs/index.html.twig', [
            'controller_name' => 'RecruteursController',
        ]);
    }
}
