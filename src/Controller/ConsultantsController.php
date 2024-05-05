<?php

namespace App\Controller;

use App\Entity\Consultants;
use App\Form\ConsultantsType;
use App\Repository\ConsultantsRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/consultants')]
class ConsultantsController extends AbstractController
{
    #[Route('/', name: 'app_consultants_index', methods: ['GET'])]
    public function index(ConsultantsRepository $consultantsRepository, PaginatorInterface $paginator, Request $request, UserRepository $userRepository): Response
    {
        $consultants = $consultantsRepository->findAll();

        $consultants = $paginator ->paginate(
            $consultants,
            $request->query->getInt('page', 1),
            6
        );

        
        return $this->render('pages/consultants/index.html.twig', [
            'consultants' => $consultants ,
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/creation', name: 'app_consultants_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ConsultantsRepository $consultantsRepository): Response
    {
        $consultant = new Consultants();
        $form = $this->createForm(ConsultantsType::class, $consultants);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $consultantsRepository->add($consultants, true);

            return $this->redirectToRoute('app_consultants_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/consultants/new.html.twig', [
            'consultants' => $consultants,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_consultants_show', methods: ['GET'])]
    public function show(Consultants $consultants): Response
    {
        return $this->render('pages/consultants/show.html.twig', [
            'consultants' => $consultants,
        ]);
    }

    #[Route('/{id}/edition', name: 'app_consultants_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Consultants $consultants, ConsultantsRepository $consultantsRepository): Response
    {
        $form = $this->createForm(ConsultantsType::class, $consultants);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $consultantsRepository->add($consultants, true);

            return $this->redirectToRoute('app_consultants_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/consultants/edit.html.twig', [
            'consultant' => $consultants,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_consultants_delete', methods: ['POST'])]
    public function delete(Request $request, Consultants $consultants, ConsultantsRepository $consultantsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$consultants->getId(), $request->request->get('_token'))) {
            $consultantsRepository->remove($consultants, true);
        }

        return $this->redirectToRoute('app_consultants_index', [], Response::HTTP_SEE_OTHER);
    }
}