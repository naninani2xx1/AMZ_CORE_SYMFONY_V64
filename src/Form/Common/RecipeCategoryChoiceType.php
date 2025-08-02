<?php

declare(strict_types=1);

namespace App\Form\Common;

use App\Core\DataType\ArchivedDataType;
use App\Core\DataType\CategoryDataType;
use App\Core\Entity\Category;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeCategoryChoiceType extends AbstractType
{
    public function getParent(): string
    {
        return EntitySelect2Type::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => Category::class,
            'choice_label' => 'title',
            'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('category')
                        ->where('category.isArchived = :archived')
                        ->andWhere('category.type = :type')
                        ->setParameter('type', CategoryDataType::TYPE_RECIPE_ARTICLE)
                        ->setParameter('archived', ArchivedDataType::UN_ARCHIVED)
                        ->orderBy('category.levelNumber', 'ASC');
            },
        ]);
    }
}
