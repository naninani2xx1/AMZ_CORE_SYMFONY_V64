<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Controller\CRUDActionInterface;
use App\Core\Entity\Category;
use App\Core\Repository\MenuRepository;
use App\Services\CategoryService;
use App\Services\PageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @Route(path="/cms/admin/page")
 */
class PageController extends AbstractController implements CRUDActionInterface
{
    private $pageService;

    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }

    /**
     * @Route(path="/", name="app_admin_page_index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $pagination = $this->pageService->findAllPaginated();
        return $this->render('Admin/views/page/index.html.twig', compact('pagination'));
    }

    /**
     * @Route(path="/add", name="app_admin_page_add")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
       return $this->pageService->add($request);
    }
    /**
     * @Route(path="/edit/{id}", name="app_admin_page_edit")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, int $id): Response
    {
        return $this->pageService->edit($request, $id);
    }
    /**
     * @Route(path="/delete/{id}", name="app_admin_page_delete")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function delete(Request $request, int $id): Response
    {
        return $this->pageService->delete($request, $id);
    }
}
