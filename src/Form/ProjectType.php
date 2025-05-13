<?php

namespace App\Form;

use App\Entity\Bead;
use App\Entity\Category;
use App\Entity\Project;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description', TextareaType::class, ['required' => false])
            ->add('imageFile', FileType::class, ['required' => false, 'mapped' => false])

            ->add('bead', EntityType::class, [
                'class' => Bead::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
            'attr' => ['class' => 'select2'],

            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
            'attr' => ['class' => 'select2'],
            ])
            // ->add('newCategories', CollectionType::class, [
            //     'entry_type' => CategoryType::class,
            //     'allow_add' => true,
            //     'allow_delete' => true,
            //     'by_reference' => false,
            //     'mapped' => false,
            //     'label' => 'Add New Categories',
            //     'prototype' => true,
            // ])
            // ->add('newCategories', CollectionType::class, [
            //     'entry_type' => CategoryType::class,
            //     'allow_add' => true,
            //     'allow_delete' => true,
            //     'by_reference' => false,
            //     'mapped' => false,
            //     'label' =>  'Add New Categories',
            //     'prototype' => true,
            //     'attr' => ['id' => 'new-categories-collection']
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
