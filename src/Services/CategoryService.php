<?php

namespace App\Services;

use App\Core\Entity\Category;
use App\Core\Exception\ValidationFailed;
use App\Core\Repository\CategoryRepository;
use App\Form\Admin\Category\CategoryAddForm;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

class CategoryService extends AbstractController
{
    private  CategoryRepository $categoryRepository;
    public function __construct(
        CategoryRepository $categoryRepository
        ,EntityManagerInterface $em
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->em = $em;
    }

    public function add(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryAddForm::class, $category);

        if ($this->handleCategoryForm($category, $request, $form)) {
            return $this->redirectToRoute('app_admin_category_index');
        }

        return $this->render('Admin/views/category/form/form_add_category.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function edit(Request $request, int $id): Response
    {
        $category = $this->findById($id);

        $oldIcon = $category->getIcon();
        $oldThumbnail = $category->getThumbnail();

        $form = $this->createForm(CategoryAddForm::class, $category);
        if ($this->handleCategoryForm($category, $request, $form)) {
            if ($form->get('icon')->getData()===null) {
                $category->setIcon($oldIcon);
            }

            if ($form->get('thumbnail')->getData()===null) {
                $category->setThumbnail($oldThumbnail);
            }
            return $this->redirectToRoute('app_admin_category_index');
        }

        return $this->render('Admin/views/category/form/form_edit_category.html.twig', [
            'form' => $form->createView(),
            'category' => $category
        ]);
    }

    public function delete(Request $request, int $id): Response
    {
        return new Response("Deleted Category Successfully");
    }



    private function handleCategoryForm(Category $category, Request $request): bool
    {
        $form=$this->createForm(CategoryAddForm::class,$category);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }

        $parentId = $request->request->get('parent_id');
        if ($parentId) {
            $parent = $this->findById((int) $parentId);
            if ($parent) {
                $category->setParent($parent);
            }
        }

        $em = $this->getEntityManager();
        $em->persist($category);
        $this->autoUpdateLevelNumberByCategory($category);
        $em->flush();

        return true;
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