<?php

namespace App\Controller;

use App\Entity\Candidats;
use App\Form\CandidatsType;
use App\Form\CandidatsSelfType;
use App\Form\CandidatsValidType;
use App\Repository\JobRepository;
use App\Repository\UserRepository;
use App\Repository\CandidatsRepository;
use App\Repository\RecruteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CandidatureRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/candidats')]
class CandidatsController extends AbstractController
{
    #[Route('/', name: 'app_candidats_index', methods: ['GET'])]
    public function index(CandidatsRepository $candidatsRepository, CandidatureRepository $candidatureRepository, JobRepository $jobRepository, RecruteurRepository $recruteurRepository): Response
    {
        return $this->render('pages/candidats/index.html.twig', [
            'candidates' => $candidatsRepository->findAll(),
            'candidatures' =>
            $candidatureRepository->findAll(),
            'jobs'=>
            $jobRepository->findAll(),
            'recruteurs'=> $recruteurRepository->findAll(),

        ]);
    }

    #[Route('/consult', name: 'app_candidats_consult_index', methods: ['GET'])]
    public function indexconsult(CandidatsRepository $candidatsRepository): Response
    {
        return $this->render('pages/candidats/indexconsult.html.twig', [
            'candidates' => $candidatsRepository->findAll(),
        ]);
    }

    #[Route('/creation', name: 'app_candidats_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CandidatsRepository $candidatsRepository): Response
    {
        $candidate = new Candidats();
        $form = $this->createForm(CandidatsType::class, $candidats);
        $form->handleRequest($request);
        $image= 'test.png';

        if ($form->isSubmitted() && $form->isValid()) {
            
            $file = $request->files->get('candidats')['my_file'];
            $uploads_directory = $this->getParameter('uploads_directory');

            $filename = md5(uniqid()) . '.' . $file->guessExtension();

            

            $file->move(
                $uploads_directory,
                $filename
            );
        // Comment sauvegarder en BD, champ cvName?
        $candidate->setCvName($filename);
        $candidatsRepository->add($candidats, true);

            return $this->redirectToRoute('home.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/candidats/new.html.twig', [
            'candidats' => $candidate,            
            'form' => $form,
            'image' => $image,
        ]);
    }

    #[Route('/{id}', name: 'app_candidats_show', methods: ['GET'])]
    public function show(Candidats $candidats): Response
    {
        return $this->render('pages/candidats/show.html.twig', [
            'candidats' => $candidats,
        ]);
    }
 #[Route('/{id}/edition', name: 'app_candidats_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Candidats $candidats, CandidatsRepository $candidatsRepository): Response
    {
        $form = $this->createForm(CandidatsValidType::class, $candidats);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $candidatsRepository->add($candidats, true);

            $this->addFlash('success', 'Le candidat a été validé avec succès');

            return $this->redirectToRoute('app_candidaturevalid_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->renderForm('pages/candidats/edit.html.twig', [
            'candidats' => $candidats,
            'form' => $form,
        ]);
    }

// #[Route('/add', name: 'app_candidats_add', methods: ['GET', 'POST'])]
// public function add(EntityManagerInterface $manager, Request $request, Candidats $candidats)
// {
//     $form = $this->createForm(CandidateType::class);
//      $form->handleRequest($request);

//         if ($form->isSubmitted() && $form->isValid()) {
//             $candidate = $form->getData();

//             $cv = $candidate->getCv();

//             $file = $cv->getFile();

//             $name = md5(uniqid()).  '.'.$file->guessExtension();

//             $file->move('../', $name);

//             $cv->setName($name);


//             $manager->persist($candidats);
//             $manager->flush();

//             $this->addFlash(
//                 'notice',
//                 'Votre profil a été mis à jour'

//             );
//             return $this->redirectToRoute('home.index');
//         }
//         return $this->render('pages/candidats/add.html.twig', [
//             'form' => $form->createView(),
//         ]);
// }


   

    #[Route('/{id}/edition_candidats', name: 'app_candidatsself_edit', methods: ['GET', 'POST'])]
    public function editself(Request $request, Candidats $candidate, CandidatsRepository $candidatsRepository): Response
    {
        $form = $this->createForm(CandidatsSelfType::class, $candidats);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $candidatsRepository->add($candidate, true);

            return $this->redirectToRoute('home.index', [], Response::HTTP_SEE_OTHER);
        }
        

        return $this->renderForm('pages/candidats/editself.html.twig', [
            'candidats' => $candidats,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_candidats_delete', methods: ['POST'])]
    public function delete(Request $request, Candidats $candidats, CandidatsRepository $candidatsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$candidats->getId(), $request->request->get('_token'))) {
            $candidatsRepository->remove($candidats, true);
        }

        return $this->redirectToRoute('app_candidats_index', [], Response::HTTP_SEE_OTHER);
    }
}