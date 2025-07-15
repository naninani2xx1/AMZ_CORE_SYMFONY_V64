<?php

namespace App\Services;

use App\Core\Entity\Category;
use App\Core\Entity\Gallery;
use App\Core\Repository\CategoryRepository;
use App\Form\Admin\Category\CategoryAddForm;
use App\Form\Admin\Category\CategoryChoiceType;
use App\Form\Admin\Category\CategoryType;
use App\Form\Admin\Gallery\GalleryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryService extends AbstractController
{
    public function __construct(EntityManagerInterface $em,CategoryRepository $categoryRepository)
    {
        $this->em = $em;
        $this->categoryRepository = $categoryRepository;
    }

    public function add(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryAddForm::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($category);
            $this->em->flush();
            return $this->redirectToRoute('app_admin_category_index');
        }

        return $this->render('Admin/views/category/form/form_add_category.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    public function edit(Request $request, int $id): Response
    {
        $category = $this->em->getRepository(Category::class)->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Không tìm thấy danh mục.');
        }

        if ($category->getIcon()) {
            $iconPath = $this->getParameter('kernel.project_dir') . '/public/uploads/category/icon/' . $category->getIcon();
            if (file_exists($iconPath)) {
                $category->setIcon(new File($iconPath));
            } else {
                $category->setIcon(null);
            }
        }
        if ($category->getThumbnail()) {
            $thumbnailPath = $this->getParameter('kernel.project_dir') . '/public/uploads/category/thumbnail/' . $category->getThumbnail();
            if (file_exists($thumbnailPath)) {
                $category->setThumbnail(new File($thumbnailPath));
            } else {
                $category->setThumbnail(null);
            }
        }

        $form = $this->createForm(CategoryAddForm::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('app_admin_category_index');
        }

        return $this->render('Admin/views/category/form/form_edit_category.html.twig', [
            'form' => $form->createView(),
            'category' => $category,
        ]);
    }


    public function delete(Request $request,int $id):Response
    {
        $category = $this->em->getRepository(Category::class)->find($id);
        $category->setArchived(1);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_category_index');
    }


}