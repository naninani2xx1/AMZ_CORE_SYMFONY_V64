<?php

declare(strict_types=1);

namespace App\Controller\Admin\Category;

use App\Core\Entity\Category;
use App\Form\Admin\Category\AddRecipeCategoryForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RecipeCategoryController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        $this->entityManager = $entityManager;
    }

    public function add(Request $request, string $type): Response
    {
        $category = new Category();
        $form = $this->createForm(AddRecipeCategoryForm::class, $category, [
            'action' => $this->generateUrl('app_admin_category_add', ['type' => $type]),
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $category->setType($type);

            $this->entityManager->persist($category);
            $this->entityManager->flush();

            return new JsonResponse([
                'message' => 'Category added successfully',
            ]);
        }
        return $this->render('Admin/views/category/add_modal.html.twig', compact('form'));
    }

    public function edit(Request $request, string $type, Category $category): Response
    {
        $form = $this->createForm(AddRecipeCategoryForm::class, $category, [
            'action' => $this->generateUrl('app_admin_category_edit', ['type' => $type, 'id' => $category->getId()]),
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->flush();
            return new JsonResponse([
                'message' => 'Category edited successfully',
            ]);
        }
        return $this->render('Admin/views/category/add_modal.html.twig', compact('form', 'category'));
    }
}
