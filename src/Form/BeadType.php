<?php

namespace App\Form;

use App\Entity\Bead;
use App\Entity\Color;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\DataTransformer\StockToHunderdTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BeadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number')
            ->add('name')
            ->add(
                'stock',
                NumberType::class,
                array(
                    // 'divisor' => 100,
                    'scale' => 2,
                    'html5' => true,
                    'attr'     => array(
                        'min'  => 0,
                        'max'  => 9999.99,
                        'step' => 0.01,
                    ),
                )
            )
            ->add('colors', EntityType::class, [
                'class' => Color::class,
                'choice_label' => 'color',
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
                'attr' => ['class' => 'm-2'],
            ])
            // ->add('components', EntityType::class, [
            //     'class' => Bead::class,
            //     'choice_label' => 'name',
            //     'multiple' => true,
            //     'expanded' => false, // change to false for a select box
            //     'required' => false,
            //     'by_reference' => false, // Important for correct add/remove behavior
            //     'label' => 'Components ',
            //     'attr' => ['class' => 'select2'], // this is the key part

            // ])
            // ->add('image')
            ->add('imageFile', FileType::class, ['required' => false, 'mapped' => false])
            // ->add('save', SubmitType::class, ['label' => 'Create Bead'])

        ;
        $data = $options['data'];
        // dd($data);
        $nameValue = $data?->getName(); // or $data['name'] if it's an array

        if ($nameValue && stripos($nameValue, 'mix') !== false) {
            $builder->add('components', EntityType::class, [
                'class' => Bead::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => false, // change to false for a select box
                'by_reference' => false, // Important for correct add/remove behavior
                'required' => false,
                'attr' => ['class' => 'select2'], // this is the key part
            ]);
        }
        $builder->get('stock')
            ->addModelTransformer(new StockToHunderdTransformer());
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bead::class,
        ]);
    }
}
