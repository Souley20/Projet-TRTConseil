<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class JobtovalidController extends AbstractController
{
    #[Route('/jobtovalid', name: 'app_jobtovalid')]
    public function index(): Response
    {
        return $this->render('jobtovalid/index.html.twig', [
            'controller_name' => 'JobtovalidController',
        ]);
    }
}
