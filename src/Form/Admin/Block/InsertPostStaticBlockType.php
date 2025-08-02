<?php

declare(strict_types=1);

namespace App\Form\Admin\Block;

use App\Core\DataType\ArchivedDataType;
use App\Core\DataType\BlockDataType;
use App\Core\Entity\Block;
use App\Core\Entity\Post;
use App\Form\Common\EntitySelect2Type;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InsertPostStaticBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('blocks', EntitySelect2Type::class, [
            'class' => Block::class,
            'multiple' => true,
            'mapped' => true,
            'data-select2-dropdown-parent-value' => 'form',
            'choice_label' => 'title',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('b')
                    ->where('b.isArchived = :archived')->setParameter('archived', ArchivedDataType::UN_ARCHIVED)
                    ->andWhere('b.kind = :kind')->setParameter('kind', BlockDataType::KIND_STATIC);
            }
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
