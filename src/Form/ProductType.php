<?php

namespace App\Form;

use App\Entity\{ Product, Category };
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\{ TextType, ChoiceType, NumberType, TextareaType };
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', EntityType::class, [
                'label' => 'Categoria',
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => '-- Selecciona',
            ])
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
                'label' => 'Marca',
                'attr' => [
                    'placeholder' => 'Marca del producto',
                ]
            ])
            ->add('price', NumberType::class, [
                'label' => 'Precio',
                'attr' => [
                    'placeholder' => '0.00',
                ]
            ])
            ->add('active', ChoiceType::class, [
                'label' => 'Status',
                'choices'  => [
                    'Activo' => 1,
                    'Inactivo' => 0,
                ],
                'attr' => [
                    'class' => 'form-control'
                ],
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
