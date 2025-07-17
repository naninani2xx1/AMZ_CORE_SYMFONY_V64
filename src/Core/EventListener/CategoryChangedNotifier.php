<?php

namespace App\Core\EventListener;

use App\Core\Entity\Category;
use App\Services\CategoryService;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;

class CategoryChangedNotifier
{
    private $categoryService;
    public function __construct(
        CategoryService $categoryService
    )
    {
        $this->categoryService = $categoryService;
    }

    public function prePersist(Category $category, PrePersistEventArgs $eventArgs): void
    {
       $levelNumber = $this->calculateLevelNumber($category);
       $category->setLevelNumber($levelNumber);
    }

    public function preFlush(Category $category, PreFlushEventArgs $eventArgs): void
    {
        if($category->isArchived()){
//            $this->categoryService->deleteThisAndChildren($category, $category->getChildren()->toArray());
//            $this->categoryService->autoUpdateLevelNumberByCategory($category);
            return;
        }
    }

    private function calculateLevelNumber(Category $category): string
    {
        if (!$category->getParent()) {
            $maxLevel = $this->categoryService->findMaxRootLevelNumber();
            if (!$maxLevel)
                return '1';

            return (string) ((int) $maxLevel + 1);
        }

        $parentLevel = $category->getParent()->getLevelNumber();

        if (!$parentLevel)
            throw new \RuntimeException('Parent category has no levelNumber.');

        $siblingCount = $category->getParent()->getChildren()->count();

        $position = $siblingCount + 1;
        return $parentLevel . '.' . $position;
    }
}