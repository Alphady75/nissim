<?php

namespace App\Form;

use App\Entity\Projet;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextareaType::class, [
                'label' => 'Titre',
                'attr'  =>  ['placeholder' => 'Tritre du projet...'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ])
                ],
            ])
            ->add('imageFile', VichImageType::class, [
                'attr'  =>  ['class' => 'text-secondary'],
                'label'     =>  'Fichier (png, jpg, jpeg)',
                'required'  =>  false,
                'allow_delete' =>  false,
                'download_label'     =>  false,
                'image_uri'     =>  false,
                'download_uri'     =>  false,
                'imagine_pattern'   =>  'large_size'
            ])
            ->add('fDescriptive', CKEditorType::class, [
                'label' => "Fiche descriptive"
            ])
            ->add('aFiabilite', CKEditorType::class, [
                'label' => 'Analyse de la fiabilité'
            ])
            ->add('dInfReglementaire', CKEditorType::class, [
                'label' => 'Documents d\'informations réglementaire'
            ])
            ->add('endDate', DateType::class, [
                'label'     =>  'Date de fin',
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ])
                ],
            ])
            /*->add('mCollecte', IntegerType::class, [
                'label' =>  'Montant Collecter'
            ])*/
            ->add('visible', CheckboxType::class, [
                'label' =>  'Visible sur le site'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,
        ]);
    }
}
