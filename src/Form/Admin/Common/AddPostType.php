<?php

declare(strict_types=1);

namespace App\Form\Admin\Common;

use App\Core\Entity\Post;
use App\Form\Common\PublishedChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('title');
        $builder->add('subTitle');
        $builder->add('description', TextareaType::class);
        $builder->add('thumbnail', FileType::class);
//        $builder->add('isHot', HiddenType::class);
//        $builder->add('isNew', HiddenType::class);
        $builder->add('published', PublishedChoiceType::class, [
            'data-select2-dropdown-parent-value' => '#amz_post_add',
            'data-select2-hidden-search-value' => 'true'
        ]);
        $builder->add('arrTags', TextType::class, [
            'label' => 'Tags',
            'attr' => [
                'placeholder' => 'Enter tags',
                'class' => 'form-control form-control-sm form-control-solid mb-2',
                'data-controller' => 'tagify',
                'data-tagify-default-value' => 'abc,def,ghi',
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
