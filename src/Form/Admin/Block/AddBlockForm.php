<?php

namespace App\Form\Admin\Block;

use App\Core\Entity\Block;
use App\Form\Common\BlockType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddBlockForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('title', TextType::class);
        $builder->add('type', BlockType::class);
        $builder->add('parent', EntityType::class, [
            'class' => Block::class,
            'required' => false,
            'placeholder' => 'Không có block cha',
            'choice_label' => 'title',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('b')
                    ->where('b.parent IS NULL')
                    ->orderBy('b.title', 'ASC');
            },
            'label' => 'Block cha'
        ]);
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