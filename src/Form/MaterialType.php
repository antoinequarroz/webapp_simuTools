<?php

namespace App\Form;

use App\Entity\Material;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('badge', TextType::class, [
                'label' => 'Badge',
            ])
            ->add('salle', TextType::class, [
                'label' => 'Salle',
            ])
            ->add('localite', TextType::class, [
                'label' => 'Localité',
            ])
            ->add('nombre', IntegerType::class, [
                'label' => 'Nombre',
            ])
            ->add('identifiant', TextType::class, [
                'label' => "ID d'identifiant",
            ])
            ->add('image', FileType::class, [
                'label' => 'Image',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'data-browse' => 'Télécharger',
                    'data-placeholder' => 'Aucun fichier télécharger',
                ],
            ])
            ->add('idClass', TextType::class, [
                'label' => "ID Classe",
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Material::class,
        ]);
    }
}
