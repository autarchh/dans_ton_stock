<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Storage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Désignation'

            ])
            ->add('description', TextareaType::class, [

            ])
            ->add('bbDate', DateType::class, [
                'label' => 'Date de péremption',
                'widget' => 'single_text'
            ])
            ->add('qtt', NumberType::class, [
                'label' => 'Quantité'
            ])
            ->add('unit', ChoiceType::class, [
                'label' => 'Unité',
                'choices' => [
                    'Gramme' => 'Gramme',
                    'Litre' => 'Litre',
                    'Unité' => 'Unité',
                ]
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'class' => Category::class,
                'choice_label' => 'name'
            ])
            ->add('storage', EntityType::class, [
                'label' => 'Lieu de stockage',
                'class' => Storage::class,
                'choice_label' => 'name'
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
