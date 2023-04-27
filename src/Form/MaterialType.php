<?php

namespace App\Form;

use App\Entity\Material;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class MaterialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
            ])
            ->add('badge', ChoiceType::class, [
                'label' => 'Badge',
                'choices' => [
                    'Basse fidélité' => 'Basse fidélité',
                    'Moyenne fidélité' => 'Moyenne fidélité',
                    'Haute fidélité' => 'Haute fidélité',
                    'Petit matériel' => 'Petit matériel',
                ],
            ])
            ->add('salle', ChoiceType::class, [
                'label' => 'Salle',
                'choices' => [
                    'Salle étage 0' => 'Salle étage 0',
                    'Salle étage 1' => 'Salle étage 1',
                    'Salle étage 2' => 'Salle étage 2',
                    'Salle étage -1' => 'Salle étage -1',
                    'Salle étage -2' => 'Salle étage -2',
                ],
            ])
            ->add('localite', ChoiceType::class, [
                'label' => 'Localité',
                'choices' => [
                    'Sion' => 'Sion',
                    'Visp' => 'Visp',
                    'Loèche-les-bains' => 'Loèche-les-bains',
                    'Monthey' => 'Monthey',
                ],
            ])
            ->add('nombre', IntegerType::class, [
                'label' => 'Nombre',
            ])
            ->add('possibilite', TextType::class, [
                'label' => 'Possibilité',
            ])
            ->add('modeEmploi', FileType::class, [
                'label' => 'Mode d\'emploi (PDF)',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'data-browse' => 'Télécharger',
                    'data-placeholder' => 'Aucun fichier télécharger',
                ],
            ])

            ->add('imageFile', FileType::class, [
                'label' => 'Image',
                'required' => false,
            ])
            ->add('liens', TextType::class, [
                'label' => 'Liens',
            ])
            ->add('idClass', ChoiceType::class, [
                'label' => "ID Classe",
                'choices' => [
                    'Basse fidélité' => 'course-pills-tab-1',
                    'Moyenne fidélité' => 'course-pills-tab-2',
                    'Haute fidélité' => 'course-pills-tab-3',
                    'Petit matériel' => 'course-pills-tab-4',
                ],
            ])
            ->add('caracteristique', TextType::class, [
                'label' => 'Caractéristique',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Material::class,
        ]);
    }
}