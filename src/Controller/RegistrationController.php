<<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Candidats;
use App\Entity\Recruteur;
use App\Entity\Consultants;
use App\Service\FileUploader;
use App\Form\RegistrationFormType;
use App\Security\UserAuthenticator;
use App\Form\RegistrationFormTypeRecruteur;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\RegistrationFormTypeCandidats;
use App\Form\RegistrationFormTypeConsultants;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/inscription_candidats', name: 'app_register_candidats')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormTypeCandidats::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // incrémentation de sa clé primaire du consultant
            $candidats = new Candidats();
            // On récupère la saisie des champs nom, prénom et téléphone    
            $candidats
            //->setCvName($form->get('my_file')->getData())
            ->setIsValided(0);

             //vient chercher la clé étrangère  ne pas oublier de persister   
            $user->setCandidats($candidats);
            $user->setRoles(["ROLE_CANDIDATS"])
            // encode the plain password
                ->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $imageFile = $form->get('cvName')->getData();
            if ($imageFile) {
            $imageFileName = $fileUploader->upload($imageFile);
            $candidats->setCvName($imageFileName);
        }
            //Important pour la relation OneToOne - Héritage
            $entityManager->persist($candidats);
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $this->addFlash(
                'success',
                'Votre demande a été enregistrée avec succès'
            );
            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register_candidats.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/inscription_recruteur', name: 'app_register_recruteur')]
    public function register2(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();        
        $form = $this->createForm(RegistrationFormTypeRecruteur::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             // incrémentation de sa clé primaire du consultant
            $recruteur = new Recruteur();
            // On récupère la saisie des champs nom, prénom et téléphone    
            $recruteur
            ->setAddressFirm($form->get('addressFirm')->getData())
            ->setFirmName($form->get('firmName')->getData())
            ->setZipcode($form->get('zipcode')->getData())
            ->setCity($form->get('city')->getData())
            ->setIsValided(0)
            
            ;

             //vient chercher la clé étrangère  ne pas oublier de persister   
            $user->setRecruteur($recruteur);
            $user->setRoles(["ROLE_RECRUTEUR"])
            // encode the plain password
                ->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
//Important pour la relation OneToOne - Héritage
            $entityManager->persist($recruteur);
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $this->addFlash(
                'success',
                'Votre demande a été enregistrée avec succès'
            );
            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register_recruiter.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    /**
    * Create the user as consultant
    */

    #[Route('/inscription_consultant', name: 'app_register_consultants')]
    public function register3(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();        
        $form = $this->createForm(RegistrationFormTypeConsultants::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        // incrémentation de sa clé primaire du consultant
            $consultants = new Consultants();
            // On récupère la saisie des champs nom, prénom et téléphone    
            $consultants
            ->setTel($form->get('tel')->getData());

             //vient chercher la clé étrangère  ne pas oublier de persister   
            $user->setConsultants($consultants);

            $user->setRoles(["ROLE_CONSULTANTS"])
            // encode the plain password
                ->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            
            //Important pour la relation OneToOne - Héritage
            $entityManager->persist($consultants);
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $this->addFlash(
                'success',
                'Votre demande a été enregistrée avec succès'
            );

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register_consultants.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}