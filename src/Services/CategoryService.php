<?php

namespace App\Services;

use App\Core\Entity\Category;
use App\Core\Exception\ValidationFailed;
use App\Core\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use http\Exception\RuntimeException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

class CategoryService extends AbstractController
{
    private  CategoryRepository $categoryRepository;
    public function __construct(
        CategoryRepository $categoryRepository
    )
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function add(Request $request): Response
    {
        return new Response("Added Category Successfully");
    }

    public function edit(Request $request, int $id): Response
    {
        return new Response("Edited Category Successfully");
    }

    public function delete(Request $request, int $id): Response
    {
        return new Response("Deleted Category Successfully");
    }

    public function findMaxRootLevelNumber(): ?string
    {
         try{
            return $this->categoryRepository->findMaxRootLevelNumber();
         }catch (NonUniqueResultException $exception){
             return '';
         }
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->categoryRepository->getEntityManager();
    }

    public function findById(int $id): ?Category
    {
        return $this->categoryRepository->find($id);
    }

    public function findAllPaginated(): PaginationInterface
    {
        return $this->categoryRepository->findAllPaginated();
    }

    public function autoUpdateLevelNumberByCategory(Category $category): void
    {
        if(empty($category->getParent())) return;
        $siblings = $this->categoryRepository->findSiblingsWithParent($category->getParent());
        /** @var Category $cateSibling */
        $indexStart = 1;
        dd($category);
        if($category->getId() == 5){
            dd($siblings);
        }
        foreach ($siblings as $cateSibling){
            $arrNumber = explode('.', $cateSibling->getLevelNumber());
            $lastNumber = end($arrNumber);

            $index = array_search($lastNumber, $arrNumber);
            // remove last index and push start index
            unset($arrNumber[$index]);
            $arrNumber[] = $indexStart;


            $levelNumber = implode('.', $arrNumber);
            $cateSibling->setLevelNumber($levelNumber);
            $indexStart++;

            if(!$cateSibling->getChildren()->isEmpty())
                $this->autoUpdateLevelNumberByCategory($cateSibling->getChildren()->first());
        }
    }

    public function deleteThisAndChildren(Category $category, array $children): void
    {
        $category->setArchived(true);
        $category->setLevelNumber(null);

        if(empty($children)) return;
        /** @var Category $child */
//        foreach ($children as $child){
//            $this->deleteThisAndChildren($child, $child->getChildren()->toArray());
//        }

        $this->getEntityManager()->flush();
    }
}   