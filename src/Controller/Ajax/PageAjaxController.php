<?php

namespace App\Controller\Ajax;

use App\Core\DTO\RequestDataTableDTO;
use App\Core\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @Route(path="/page/ajax")
 */
class PageAjaxController extends AbstractController
{
    private $pageRepository;

    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    /**
     * @Route(path="/update-table", name="app_admin_page_ajax_update_table")
     */
    public function updateTable(Request $request): JsonResponse
    {
        $requestDTO = new RequestDataTableDTO($request);
        // paginator
        $pagination = $this->pageRepository->findAllPaginated($requestDTO->getPage(), $requestDTO->getLimit());
        return $this->json([
            'html' => $this->renderView('Admin/views/page/update.html.twig', compact('pagination')),
        ]);
    }
}