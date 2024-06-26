<?php

namespace App\Controller;

use App\Entity\Job;
use App\Form\JobType;
use App\Repository\CandidatsRepository;
use App\Repository\CandidatureRepository;
use App\Repository\JobRepository;
use App\Repository\RecruteurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/annonces')]
class JobController extends AbstractController
{
    #[Route('/', name: 'app_job_index', methods: ['GET'])]
    public function index(JobRepository $jobRepository, CandidatsRepository $candidatsRepository): Response
    {
        return $this->render('pages/job/index.html.twig', [
            'jobs' => $jobRepository->findAll(),
            'candidates' => $candidatsRepository->findAll(),
        ]);
    }

    /**
     *This method displays the list of the jobs for a logged recruteur
     */
    #[Route('/recruteur', name: 'app_jobrecruteur_index', methods: ['GET'])]
    public function indexrecruteur(JobRepository $jobRepository, CandidatsRepository $candidatsRepository, RecruteurRepository $recruteurRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $recruteur = $user->getRecruteur();         

        return $this->render('pages/job/indexrecruteur.html.twig', [
            'jobs' => $jobRepository->findByUser($recruteur),
            'candidates' => $candidatsRepository->findAll(),
             'recruteurs' => $recruteurRepository->findAll(),
        ]);
    }
    /**
     * This method displays the jobs to apply
     */
    #[Route('/candidats', name: 'app_jobcandidats_index', methods: ['GET'])]
    public function indexcandidats(JobRepository $jobRepository, CandidatsRepository $candidatsRepository, CandidatureRepository $candidatureRepository): Response
    {
        return $this->render('pages/job/indexcandidats.html.twig', [
            'jobs' => $jobRepository->findAll(),
            'candidates' => $candidatsRepository->findAll(),
            'candidatures' => $candidatureRepository->findAll(),
        ]);
    }

    #[Route('/creation', name: 'app_job_new', methods: ['GET', 'POST'])]
    public function new(Request $request, JobRepository $jobRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $recruiter = $user->getRecruiter();
        
        $job = new Job();
        $job->setRecruteur($recruiter);
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $jobRepository->add($job, true);
             $this->addFlash('success', 'Votre demande a été enregistrée avec succès');

            return $this->redirectToRoute('home.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/job/new.html.twig', [
            'job' => $job,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_job_show', methods: ['GET'])]
    public function show(Job $job): Response
    {
        return $this->render('pages/job/show.html.twig', [
            'job' => $job,
        ]);
    }

    #[Route('/{id}/edition', name: 'app_job_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Job $job, JobRepository $jobRepository): Response
    {
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $jobRepository->add($job, true);
             $this->addFlash('success', 'Votre demande a été enregistrée avec succès');

            return $this->redirectToRoute('app_job_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/job/edit.html.twig', [
            'job' => $job,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_job_delete', methods: ['POST'])]
    public function delete(Request $request, Job $job, JobRepository $jobRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$job->getId(), $request->request->get('_token'))) {
            $jobRepository->remove($job, true);
        }

        return $this->redirectToRoute('app_job_index', [], Response::HTTP_SEE_OTHER);
    }
}