<?php

namespace App\AdminBundle\EventListener\Entity;

use App\Core\Entity\Category;
use App\Core\Repository\CategoryRepository;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', lazy: true, entity: Category::class)]
class CategoryEventListener
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function prePersist(Category $category): void
    {
        $levelNumber = $this->calculateLevelNumber($category);
        $category->setLevel($levelNumber);
    }
    private function getCategories(){
        return $this->categoryRepository->findAll();
    }
    private function calculateLevelNumber(Category $category): string
    {
        if (!$category->getParent()) {
            $maxLevel = $this->categoryRepository->findMaxRootLevelNumber();
            if (!$maxLevel)
                return '1';

            return (string) ((int) $maxLevel + 1);
        }

        $parentLevel = $category->getParent()->getLevel();

        if (!$parentLevel)
            throw new \RuntimeException('Parent category has no levelNumber.');

        $siblingCount = $category->getParent()->getChildren()->count();

        $position = $siblingCount + 1;
        return $parentLevel . '.' . $position;
    }
}