<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Storage;
use App\Repository\StorageRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $id = $options['id'];
        $builder
            ->add('name', TextType::class, [
                'label' => 'Désignation'

            ])
            ->add('nutriscore', ChoiceType::class, [
                'choices' => [
                    'A' => 'a',
                    'B' => 'b',
                    'C' => 'c',
                    'D' => 'd',
                    'E' => 'e',
                ]
            ])
            ->add('qtt', NumberType::class, [
                'label' => 'Quantité',
            ])
            ->add('unit', ChoiceType::class, [
                'label' => 'Unité',
                'choices' => [
                    'Kilogramme' => 'Kilogramme',
                    'Gramme' => 'Gramme',
                    'Litre' => 'Litre',
                    'Centilitre' => 'Centilitre',
                    'Mililitre' => 'Mililitre',
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
                'query_builder' => function (StorageRepository $repo) use ($id) {
                    return $repo->createQueryBuilder('s')
                    ->andWhere('s.user = :val')
                    ->setParameter('val', $id);
                },
                'class' => Storage::class,
                'choice_label' => 'name'
            ])
            ->add('bbDate', DateType::class, [
                'label' => 'Date de péremption',
                'widget' => 'single_text'
            ])
            ->add('img', TextType::class, [
                'row_attr' => ['style' => 'display:none']
            ])
            ->add('imgThumb', TextType::class, [
                'row_attr' => ['style' => 'display:none']
            ])
        ;
    }
                            
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'id' => null
        ]);
    }
}
