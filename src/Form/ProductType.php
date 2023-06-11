<?php

namespace App\Form;

use App\Entity\Media;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('price')
            ->add('stock')
            ->add('available', CheckboxType::class, [
                'required' => false,
                'data' => $options['data']->isAvailable() ?? true,
            ])
            ->add('category')
            ->add('medias', EntityType::class, [
                'required' => false,
                'class' => Media::class,
                'multiple' => true,
                'data' => $options['data']->getMedias(),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
