<?php

declare(strict_types=1);

namespace App\Form\Admin\Article;

use App\Core\Entity\Gallery;
use App\Core\Entity\Post;
use App\Core\Repository\GalleryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class AddArticleForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Tiêu đề không được để trống']),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Tiêu đề không được dài quá {{ limit }} ký tự',
                    ]),
                ],
            ])
            ->add('subTitle', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Phụ đề không được để trống']),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Phụ đề không được dài quá {{ limit }} ký tự',
                    ]),
                ],
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
