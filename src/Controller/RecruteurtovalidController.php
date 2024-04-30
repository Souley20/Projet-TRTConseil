<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecruteurtovalidController extends AbstractController
{
    #[Route('/recruteurtovalid', name: 'app_recruteurtovalid')]
    public function index(): Response
    {
        return $this->render('recruteurtovalid/index.html.twig', [
            'controller_name' => 'RecruteurtovalidController',
        ]);
    }
}
