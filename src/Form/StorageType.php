<?php

namespace App\Form;

use App\Entity\Storage;
use App\Entity\StorageCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StorageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom'

            ])
            ->add('location', TextType::class, [
                'label' => 'Emplacement'
            ])
            ->add('storageCategory', EntityType::class, [
                'class' => StorageCategory::class,
                'choice_label' => 'type',
                'label' => 'Catégorie du stockage'

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Storage::class,
        ]);
    }
}
