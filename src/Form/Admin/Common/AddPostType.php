<?php

declare(strict_types=1);

namespace App\Form\Admin\Common;

use App\Core\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('title');
        $builder->add('subTitle');
        $builder->add('content');
        $builder->add('description');
        $builder->add('thumbnail');
        $builder->add('isHot');
        $builder->add('isNew');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
