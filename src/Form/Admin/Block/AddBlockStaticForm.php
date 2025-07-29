<?php

namespace App\Form\Admin\Block;

use App\Core\Entity\Block;
use App\Form\Common\BlockType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddBlockStaticForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('title', TextType::class);
        $builder->add('type', BlockType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Block::class,
            'attr' => [
                'data-controller' => 'Admin--block-add'
            ]
        ]);
    }
}