<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\{ TextType, CheckboxType, TextareaType };
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category')
            ->add('code', TextType::class, [
                'label' => 'Codigo',
                'attr' => [
                    'placeholder' => 'Codigo del producto',
                ]
            ])
            ->add('name', TextType::class, [
                'label' => 'Nombre',
                'attr' => [
                    'placeholder' => 'Nombre del producto',
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Descripción',
                'attr' => ['class' => 'tinymce'],
            ])
            ->add('brand', TextType::class, [
                'label' => 'Descripción',
            ])
            ->add('price')
            ->add('active', CheckboxType::class, [
                'label' => 'Activo',
                'attr' => [
                    ''
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
