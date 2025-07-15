<?php

declare(strict_types=1);

namespace App\Form\Admin\Article;

use App\Core\Entity\Gallery;
use App\Core\Entity\Post;
use App\Core\Repository\GalleryRepository;
use App\DataType\PostTypeChoice;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddArticleForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
        ])
            ->add('subTitle', TextType::class, [
            ])
            ->add('postType', ChoiceType::class, [
                'choices' => array_flip(PostTypeChoice::TYPE_LABELS),
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('gallery', EntityType::class, [
                'class' => Gallery::class,
                'choice_label' => 'name',
                'query_builder' => function (GalleryRepository $repo) {
                    return $repo->createQueryBuilder('g')
                        ->leftJoin('App\Core\Entity\Post', 'p', 'WITH', 'p.gallery = g')
                        ->where('p.id IS NULL');
                },
                'placeholder' => 'Chọn gallery chưa có post',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'data_class' => Post::class,
        ));
    }
}
