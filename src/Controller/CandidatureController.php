<?php

namespace App\Controller;

use App\Entity\Job;
use App\Entity\Candidature;
use App\Form\JobContactType;
use App\Form\CandidatureType;
use App\Service\MailerService;
use App\Repository\JobRepository;
use Symfony\Component\Mime\Email;
use App\Form\CandidatureApplyType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CandidatureRepository;
use App\Repository\RecruteurRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[Route('/candidature')]
class CandidatureController extends AbstractController
{
    

    /**This method displays the list to valid the candidate, the job, the recruteur */    
    #[Route('/', name: 'app_candidature_index', methods: ['GET'])]
    public function index(CandidatureRepository $candidatureRepository): Response
    {
        return $this->render('pages/candidature/index.html.twig', [
            'candidatures' => $candidatureRepository->findAll(),
            
        ]);
    }
     /**This method displays the list to valid the candidate, the job, for the recruteur */    
    #[Route('/vos_annonces', name: 'app_candidaturerecruteur_index', methods: ['GET'])]
    public function indexrecruter(CandidatureRepository $candidatureRepository, RecruteurRepository $recruteurRepository): Response
    {
        return $this->render('pages/candidature/indexrecruiter.html.twig', [
            'candidatures' => $candidatureRepository->findAll(),
            'recruteurs' => $recruteurRepository->findAll(),
            
        ]);
    }
    //This method displays the candidatures the logged candidate
    #[Route('/candidats', name: 'app_candidature')]
    public function indexCandidat(JobRepository $jobRepository, CandidatureRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {

        /** @var User $user */
        $user = $this->getUser();
        $candidats=$user->getCandidats();
        $candidatures = $paginator->paginate(

            $repository->findByUser($candidats),
            $request->query->getInt('page', 1), /*page number*/
            170 /*limit per page*/
        );

        return $this->render('pages/candidature/candidats.html.twig', [
            'candidatures' => $candidatures,
            'jobs' => $jobRepository,
        ]);
    }


//This method displays the candidatures the logged recruteur
    #[Route('/candidate_recruteur', name: 'app_candidaturerecruteur')]
    public function indexCandidatrecruteur(JobRepository $jobRepository, CandidatureRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {

        /** @var User $user */
        $user = $this->getUser();
        $recruteur=$user->getRecruteur();
        $candidatures = $paginator->paginate(

            $repository->findByUser($recruteur),
            $request->query->getInt('page', 1), /*page number*/
            170 /*limit per page*/
        );

        return $this->render('pages/candidature/candidaterecruteur.html.twig', [
            'candidatures' => $candidatures,
            'jobs' => $jobRepository,
        ]);
    }    

    
    #[Route('/creation', name: 'app_candidature_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CandidatureRepository $candidatureRepository): Response
    {
        $candidature = new Candidature();
        $form = $this->createForm(CandidatureType::class, $candidature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $candidatureRepository->add($candidature, true);
            
        $this->addFlash('success', 'Votre candidature a été envoyée avec succès');
        
            return $this->redirectToRoute('app_candidature_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/candidature/new.html.twig', [
            'candidature' => $candidature,
            'form' => $form,
        ]);
    }
    /**
     * This method allows to create a candidature between a job and a candidate
     * ManyToOne -  
     */
    #[Route('/postuler/creer/{job}', name: 'app_candidatureapplied_new', methods: ['GET', 'POST'])]
    public function newapplied(Job $job, CandidatureRepository $candidatureRepository, EntityManagerInterface $entityManager): Response
    {

        /**
         * I want to get the logged User 
         */

         /**@var User $user */
         $user = $this->getUser();
         $candidats=$user->getCandidats();

         // Check if the candidate applied <=> the candidature is or isn't present

         $candidaturePresent = $candidatureRepository->findOneByUserAndJob($candidats, $job);
         if ($candidaturePresent) {
            return $this->redirectToRoute('app_jobcandidats_index', [], 
         Response::HTTP_SEE_OTHER);
         }


        $candidature = new Candidature();
        $candidature->setCandidats($candidats)
        ->setJob($job)
        ->setIsApplied(1)
        ->setIsValided(0);
        $entityManager->persist($candidature);
        $entityManager->flush();

        //dd($candidature);

        
            
        $this->addFlash('success', 'Votre candidature a été envoyée avec succès');
        
            return $this->redirectToRoute('app_jobcandidats_index', [], Response::HTTP_SEE_OTHER);
        

        
    }

    #[Route('/{id}', name: 'app_candidature_show', methods: ['GET'])]
    public function show(Candidature $candidature): Response
    {
        return $this->render('pages/candidature/show.html.twig', [
            'candidature' => $candidature,
        ]);
    }

    #[Route('/{id}/edition', name: 'app_candidature_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Candidature $candidature, CandidatureRepository $candidatureRepository, MailerInterface $mailer, $repository): Response
    {
        $form = $this->createForm(CandidatureType::class, $candidature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $candidatureRepository->add($candidature, true);
           // $mailMessage = $candidature->getUser;
           $mailMessage='coucou';
          //  $mailer->sendEmail(content: $mailMessage);

         // $repository = $this->getDoctrine()->getRepository(Candidature::class);
        //  $cvName = $repository->find($id);
          $email = (new TemplatedEmail())
         
        ->from('sanogosoul009@gmail.com')
        ->to('sanogo.test@gmail.com')
        // code pour avoir accès à l'adresse test:    Studi2024!
        // 
        //Pour le recruteur il faut envoyer
        //->to($candidature->getUser()->getRecruteur()->getEmail())// à mettre en place pour la production 
       // ->attach(fopen($candidature->getUser()->getCandidats()->getCvName()))
        ///
        //addTo('ajouterunenvelleadresse@gmail.com)
        //->test1('test1@example.com')
        //->bnf('bnf@example.com')
        //->replyTo('johndo@example.com') si on veut une autre adresse de réception des réponse
        //->priority(Email::PRIORITY_HIGH)
        
        ->subject('Modification du statut de candidature')
       //->text('Votre candidature a été validée');
       ->htmlTemplate('emails/candidatureanswer.html.twig')
        ->context([
            'candidature'=>$candidature
        ]);


        $mailer->send($email);

        $this->addFlash('success', 'Votre message a été envoyé');


            return $this->redirectToRoute('app_candidature_index', [], Response::HTTP_SEE_OTHER);
        }   

        return $this->renderForm('pages/candidature/edit.html.twig', [
            'candidature' => $candidature,
            'form' => $form ,
            
        ]);
    }

    #[Route('/{id}/postuler', name: 'app_candidatureapply_edit', methods: ['GET', 'POST'])]
    public function editapply(Request $request, Candidature $candidature, CandidatureRepository $candidatureRepository, MailerInterface $mailer, $repository): Response
    {
        $form = $this->createForm(CandidatureApplyType::class, $candidature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $candidatureRepository->add($candidature, true);
           // $mailMessage = $candidature->getUser;
           $mailMessage='coucou';
          //  $mailer->sendEmail(content: $mailMessage);

         // $repository = $this->getDoctrine()->getRepository(Candidature::class);
        //  $cvName = $repository->find($id);
          $email = (new TemplatedEmail())
         
        ->from('sanogosoul009@gmail.com')
        ->to('sanogo.test@gmail.com')
        // code pour avoir accès à l'adresse test:    Studi2024!
        // 
        //Pour le recruteur il faut envoyer
        //->to($candidature->getUser()->getRecruiter()->getEmail())// à mettre en place pour la production 
       // ->attach(fopen($candidature->getUser()->getCandidate()->getCvName()))



        ///
        //addTo('ajouterunenvelleadresse@gmail.com)
        //->test1('test1@example.com')
        //->bnf('bnf@example.com')
        //->replyTo('johndo@example.com') si on veut une autre adresse de réception des réponse
        //->priority(Email::PRIORITY_HIGH)
        
        ->subject('Modification du statut de candidature')
       //->text('Votre candidature a été validée');
       ->htmlTemplate('emails/candidatureanswer.html.twig')
        ->context([
            'candidature'=>$candidature
        ]);


        $mailer->send($email);

        $this->addFlash('success', 'Votre message a été envoyé');


            return $this->redirectToRoute('app_candidature_index', [], Response::HTTP_SEE_OTHER);
        }
    

        return $this->renderForm('pages/candidature/editapply.html.twig', [
            'candidature' => $candidature,
            'form' => $form ,
            
        ]);
    }

    #[Route('/details/{id}', name: 'details', methods: ['GET','POST'])]
    public function details($id, CandidatureRepository $candidatureRepository, Request $request)
    {
        $candidature = $candidatureRepository->findOneBy(['id'=>$id]);

        if(!$candidature){
            throw new NotFoundHttpException('Pas d\'annonce trouvée');
        }

        $form = $this->createForm(JobContactType::class);

        $contact = $form->handleRequest($request);
        
        return $this->renderForm('pages/candidature/details.html.twig', [
            'candidature'=>$candidature,
            'form'=>$form->createView()
        ]);
    }

    #[Route('/{id}', name: 'app_candidature_delete', methods: ['POST'])]
    public function delete(Request $request, Candidature $candidature, CandidatureRepository $candidatureRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$candidature->getId(), $request->request->get('_token'))) {
            $candidatureRepository->remove($candidature, true);
        }

        return $this->redirectToRoute('app_candidature_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/candidature/makeItValide/{page}/{id}', name: 'app_candidature_valide', methods: ['GET', 'POST'])]
    public function makeItValide($page, $id, CandidatureRepository $candidatureRepository, MailerInterface $mailer ): Response
    {
        $candidature = $candidatureRepository->find($id);
        if ($candidature->isIsValided()) {
            $candidature->setIsValided(false);
        } else {
            $candidature->setIsValided(true);
        }

        $candidatureRepository->add($candidature, true);
    $email = (new TemplatedEmail())
         
        ->from('sanogosoul009@gmail.com')
        ->to('sanogo.test@gmail.com')
        // code pour avoir accès à l'adresse test:    Studi2024!
        // 
        //Pour le recruteur il faut envoyer
        //->to($candidature->getUser()->getRecruteur()->getEmail())// à mettre en place pour la production 
       // ->attach(fopen($candidature->getUser()->getCandidats()->getCvName()))
        ->subject('Modification du statut de candidature')
        ->htmlTemplate('emails/candidatureanswer.html.twig')
        ->context([
            'candidature'=>$candidature
        ]);


        $mailer->send($email);
        $this->addFlash(
            'success',
            'Le statut de la  candidature vient d\'être modifiée'
        );


        return $this->redirectToRoute('app_candidature_index', [
            'page' => $page,
        ], Response::HTTP_SEE_OTHER);
    }


}