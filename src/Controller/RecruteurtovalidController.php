<?php

namespace App\Controller;

use App\Entity\Recruteur;
use App\Form\RecruteurType;
use App\Repository\RecruteurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * This controller diplays the index to valid a recruteur for the consultant
 */
#[Route('/recruteur_a_valider')]
class RecruteurtovalidController extends AbstractController
{
    #[Route('/', name: 'app_recruteurtovalid_index', methods: ['GET'])]
    public function index(RecruteurRepository $recruteurRepository): Response
    {
        return $this->render('pages/recruteur/indextovalid.html.twig', [
            'recruteurs' => $recruteurRepository->findAll(),
        ]);
    }

    
}
