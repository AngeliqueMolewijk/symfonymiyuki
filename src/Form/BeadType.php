<?php

namespace App\Form;

use App\Entity\Bead;
use App\Entity\Color;
use App\Entity\UserBead;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\DataTransformer\StockToHunderdTransformer;
use Doctrine\DBAL\Types\IntegerType;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class BeadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // dd($options);
        $userBead = $options['user_bead'];
        $builder
            ->add('number')
            ->add('name')

            ->add('colors', EntityType::class, [
                'class' => Color::class,
                'choice_label' => 'color',
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
                'attr' => ['class' => 'm-2'],
            ]);
        if ($options['show_stock']) {

            $builder->add('userBead', UserBeadType::class, [
                'data' => $options['user_bead'],
                'mapped' => false, // Important!
                'label' => false,
            ]);
        }
        $builder->add('imageFile', FileType::class, ['required' => false, 'mapped' => false]);
        $data = $options['data'];
        $nameValue = $data?->getName();

        if ($nameValue && stripos($nameValue, 'mix') !== false) {
            $builder->add('components', EntityType::class, [
                'class' => Bead::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => false,
                'by_reference' => false,
                'required' => false,
                'attr' => ['class' => 'select2'],
            ]);
        }
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bead::class,
            'user_bead' => null, // We inject this manually
            'show_stock' => false,
        ]);
    }
}
