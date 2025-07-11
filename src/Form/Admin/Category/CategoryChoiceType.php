<?php

declare(strict_types=1);

namespace App\Form\Admin\Category;

use App\Core\Entity\Category;
use App\Core\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryChoiceType extends AbstractType
{

    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    public function getParent(): string
    {
        return EntityType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => Category::class,
            'label' => "Category Parent",
            'row_attr' => ['class' => 'mb-5'],
            'choices' => $this->categoryRepository->findAllCategories(),
            'choice_label' => function (Category $category) {
                return $category->getCategoryNameWithLevel();
            },
            'choice_value' => 'id',
            'placeholder' => "---Select Category parent---",
        ]);
    }
}