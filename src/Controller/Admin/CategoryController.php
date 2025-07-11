<?php

namespace App\Controller\Admin;

use App\Core\Controller\CRUDActionInterface;

use App\Core\Repository\CategoryRepository;
use App\Services\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @Route("/cms/category")
 */
class CategoryController extends AbstractController implements CRUDActionInterface
{

    public function __construct( CategoryRepository $categoryRepository ,
                                private readonly CategoryService $categoryService,
    )
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
    @Route("/", name="app_admin_category_index")
     */
    public function index(Request $request): Response
    {
        $categories=$this->categoryRepository->getCategies();
        return $this->render('Admin/views/category/index.html.twig', [
            'categories' => $categories,
        ]);
    }
    /**
     * @Route("/add", name="app_admin_category_add", methods={"GET","POST"})
     */
    public function add(Request $request): Response
    {
        return $this->categoryService->add($request);
    }
    /**
     * @Route("/edit/{id}", name="app_admin_category_edit")
     * */
    public function edit(Request $request, int $id): Response
    {
        return $this->categoryService->edit($request, $id);
    }
    /**
    @Route("/delete/{id}", name="app_admin_category_delete")
     */
    public function delete(Request $request, int $id): Response
    {
        return $this->categoryService->delete($request, $id);
    }

}