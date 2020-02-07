<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('slug')
            ->add('description')
            ->add('price')
            ->add('createdAt')
            ->add('isHeartStroke')
            ->add('colors', ChoiceType::class, [
                'choices' => [
                    'Bleu' => 'Bleu',
                    'Rouge' => 'Rouge',
                    'Vert' => 'Vert',
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('image')
            ->add('discount')
            ->add('category')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
