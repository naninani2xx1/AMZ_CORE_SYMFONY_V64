<?php

namespace App\Form\Admin\Page;

use App\Core\Entity\Page;
use App\Core\Entity\Post;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class)
            ->add('type',IntegerType::class)
            ->add('seoUrl',TextType::class)
            ->add('css',TextType::class)
            ->add('customCss',TextType::class)
            ->add('post', EntityType::class, [
                'class' => Post::class,
                'choice_label' => 'id',
                'placeholder'=>'',
                'required'=>false
            ])
            ->add('parent', EntityType::class, [
                'class' => Page::class,
                'choice_label' => 'id',
                'placeholder'=>'',
                'required'=>false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
        ]);
    }
}
