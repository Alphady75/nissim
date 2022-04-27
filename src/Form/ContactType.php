<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' =>  'Nom',
                'attr'  =>  ['placeholder'   =>  'Votre nom...'],
                'constraints'   =>  [
                    new NotBlank([
                        'message'   =>  'Quelle est votre nom?'
                    ]),
                ]
            ])
            ->add('email', EmailType::class, [
                'label' =>  'Email',
                'attr'  =>  ['placeholder'   =>  'Votre adresse Email...'],
                'constraints'   =>  [
                    new NotBlank([
                        'message'   =>  'Quelle est votre adresse email?'
                    ]),
                    new Email([
                        'message'   =>  'Veuillez saisir une adresse Email valide'
                    ])
                ]
            ])
            ->add('sujet', TextType::class, [
                'label' =>  'Sujet',
                'attr'  =>  ['placeholder'   =>  'Pour quelle raison souhaitez-vous nous contacter?'],
                'constraints'   =>  [
                    new NotBlank([
                        'message'   =>  'Pour quelle raison souhaitez-vous nous contacter?'
                    ])
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' =>  'Message',
                'attr'  =>  ['placeholder'   =>  'Ecrire le message...'],
                'constraints'   =>  [
                    new NotBlank([
                        'message'   =>  'Veuillez écrire un message!'
                    ]),
                    new Length([
                        'min'   =>  10,
                        'minMessage'    =>  'Votre message doit contenir en minimum 10 caractères',
                        'max'   =>  500,
                        'maxMessage'    =>  'Votre message doit contenir en maximum 500caractères'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
