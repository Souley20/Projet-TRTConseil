<?php

namespace App\Controller;

use App\Entity\Candidats;
use App\Form\CandidatsType;
use App\Repository\CandidatsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * This controller diplays the index to valid a recruteur for the consultant
 */
#[Route('/candidats_a_valider')]
class CandidaturetovalidController extends AbstractController
{
    #[Route('/', name: 'app_candidaturetovalid_index', methods: ['GET'])]
    public function index(CandidatsRepository $candidatsRepository): Response
    {
        return $this->render('pages/candidats/indextovalid.html.twig', [
            'candidates' => $candidatsRepository->findAll(),
        ]);
    }

    
}