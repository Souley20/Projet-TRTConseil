<?php

namespace App\Controller;

use App\Entity\Recruteur;
use App\Form\RecruteurType;
use App\Repository\RecruteurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/recruteur')]
class RecruteurController extends AbstractController
{
    #[Route('/', name: 'app_recruteur_index', methods: ['GET'])]
    public function index(RecruteurRepository $recruteurRepository): Response
    {
        return $this->render('pages/recruteur/index.html.twig', [
            'recruiters' => $recruteurRepository->findAll(),
        ]);
    }

    #[Route('/creation', name: 'app_recruteur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RecruteurRepository $recruteurRepository): Response
    {
        $recruteur = new Recruteur();
        $form = $this->createForm(RecruteurType::class, $recruteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recruteurRepository->add($recruteur, true);

            return $this->redirectToRoute('home.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/recruteur/new.html.twig', [
            'recruiter' => $recruteur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recruteur_show', methods: ['GET'])]
    public function show(Recruteur $recruteur): Response
    {
        return $this->render('pages/recruteur/show.html.twig', [
            'recruteur' => $recruteur,
        ]);
    }

    #[Route('/{id}/edition', name: 'app_recruteur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recruteur $recruteur, RecruteurRepository $recruteurRepository): Response
    {
        $form = $this->createForm(RecruteurType::class, $recruteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recruteurRepository->add($recruteur, true);

            return $this->redirectToRoute('app_recruteurtovalid_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/recruteur/edit.html.twig', [
            'recruteur' => $recruteur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recruteur_delete', methods: ['POST'])]
    public function delete(Request $request, Recruteur $recruteur, RecruteurRepository $recruteurRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recruteur->getId(), $request->request->get('_token'))) {
            $recruteurRepository->remove($recruteur, true);
        }

        return $this->redirectToRoute('app_recruteur_index', [], Response::HTTP_SEE_OTHER);
    }
}