<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Controller\CRUDActionInterface;
use App\Core\Services\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @Route(path="/cms/category")
 */
class CategoryController extends AbstractController implements CRUDActionInterface
{
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }


    public function index(Request $request): Response
    {
        throw new \Exception('Not implemented');
    }

    /**
     * @Route(path="/{type}", name="app_admin_category_index", methods={"GET"})
     * @param Request $request
     * @param string $type
     * @return Response
     */
    public function indexAction(Request $request, string $type): Response
    {
        return $this->render('Admin/views/category/index.html.twig', compact('type'));
    }

    /**
     * @Route(path="/add", name="app_admin_page_add")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
       return $this->categoryService->add($request);
    }
    /**
     * @Route(path="/edit/{id}", name="app_admin_page_edit")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, int $id): Response
    {
        return $this->categoryService->edit($request, $id);
    }
    /**
     * @Route(path="/delete/{id}", name="app_admin_page_delete")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function delete(Request $request, int $id): Response
    {
        return $this->categoryService->delete($request, $id);
    }

}
