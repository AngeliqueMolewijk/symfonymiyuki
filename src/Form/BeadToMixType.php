<?php

namespace App\Form;

use App\Entity\Bead;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class BeadToMixType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $data = $options['data'];
        $nameValue = $data?->getName(); // or $data['name'] if it's an array

        if ($nameValue && stripos($nameValue, 'mix') !== false) {
            $builder->add('components', EntityType::class, [
                'class' => Bead::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => false,
                'by_reference' => false, // Important for correct add/remove behavior
                'required' => false,
                'attr' => ['class' => 'select2'],
                'label' => false,
            ]);
        }
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bead::class,
        ]);
    }
}
