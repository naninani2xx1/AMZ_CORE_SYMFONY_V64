<?php

namespace App\Form\Admin\Gallery;

use App\Core\Entity\Gallery;
use App\Core\Entity\Post;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GalleryFolderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image',TextType::class)
            ->add('image_mobile',TextType::class)
            ->add('link',TextType::class)
            ->add('sort_',IntegerType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Gallery::class,
            'attr'=>['novalidate'=>'novalidate','data-controller'=>'Admin--gallery'],
        ]);
    }
}
