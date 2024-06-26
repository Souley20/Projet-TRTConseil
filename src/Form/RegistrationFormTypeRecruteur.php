<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Recruteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormTypeRecruteur extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
             ->add('email', EmailType::class, [
                 'attr' => [
                     'class' => 'form-control'
                 ],
                 'label' => 'E-mail'
             ])
              ->add('firstname', TextType::class, [
                 'mapped' => true, 
                            'label' => 'Prénom ',
                                                  
                          
                 'label_attr' => [
                     'class' => 'form-label  mt-4'
                 ],  
                 'attr' => [
                     'class' => 'form-control',
                     'minlenght' => '2',
                     'maxlenght' => '190',
                 ],      

              ])
              ->add('lastname', TextType::class, [
                 'mapped' => true, 
                 'label' => 'Nom de famille ',
                 'label_attr' => [
                     'class' => 'form-label  mt-4'
                ],  
                 'attr' => [
                     'class' => 'form-control',
                     'minlenght' => '2',
                     'maxlenght' => '190',
                 ],      

              ])
              ->add('addressFirm', TextType::class, [
                'mapped' => false, 
                           'label' => 'Rue de votre entreprise',
                                                  
                          
                'label_attr' => [
                    'class' => 'form-label  mt-4'
                ],  
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '190',
                ],      

             ])

              ->add('firmName', TextType::class, [
                'mapped' => false, 
                           'label' => 'Nom de l\'entreprise',
                                                  
                          
                'label_attr' => [
                    'class' => 'form-label  mt-4'
                ],  
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '190',
                ],      

             ])
           ->add('zipcode', TextType::class, [
                'mapped' => false, 
                           'label' => 'Code postale',
                                                  
                          
                'label_attr' => [
                    'class' => 'form-label  mt-4'
                ],  
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '190',
                ],      

             ])
              ->add('city', TextType::class, [
                'mapped' => false, 
                           'label' => 'Ville',
                                                  
                          
                'label_attr' => [
                    'class' => 'form-label  mt-4'
                ],  
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '190',
                ],      

             ])
            // ->add('isValided')
            ->add('isRGPD', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Etes-vous d\'accord avec notre RGPD ?',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez être d\'accord avec nos conditions.',
                    ]),
                ],
                'attr' => [
                    'class' => 'd-flex justify-content-around',
                ],
                'label_attr' => [
                    'class' => 'form-label  mt-4'
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
