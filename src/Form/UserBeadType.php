<?php

namespace App\Form;

use App\Entity\UserBead;
use App\Entity\bead;
use App\Entity\user;
use App\Form\DataTransformer\StockToHunderdTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserBeadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'stock',
                NumberType::class,
                array(
                    'scale' => 2,
                    'html5' => true,
                    'attr'     => array(
                        'min'  => 0,
                        'max'  => 9999.99,
                        'step' => 0.01,
                    ),
                )
            );
        $builder->get('stock')
            ->addModelTransformer(new StockToHunderdTransformer());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserBead::class,
        ]);
    }
}
