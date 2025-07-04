<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Controller\CRUDActionInterface;
use App\Services\ArticleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @Route(path="/cms/article")
 */
class ArticleController extends AbstractController implements CRUDActionInterface
{
    private ArticleService $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    /**
     * @Route(path="/", name="app_admin_article_index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
       return new Response();
    }

    /**
     * @Route(path="/add/{id}", name="app_admin_article_add")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function add(Request $request, int $id): Response
    {
        return $this->articleService->add($request, $id);
    }

    /**
     * @Route(path="/edit/{id}", name="app_admin_article_edit")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, int $id): Response
    {
        return $this->articleService->edit($request, $id);
    }

    /**
     * @Route(path="/delete/{id}", name="app_admin_article_delete")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function delete(Request $request, int $id): Response
    {
        return $this->articleService->delete($request, $id);
    }
}
