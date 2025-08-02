<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Controller\CRUDActionInterface;
use App\Core\Services\ArticleService;
use App\Utils\ArticleUtils;
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

    public function index(Request $request): Response
    {
       throw new \Exception('Not implemented');
    }


    /**
     * @Route(path="/{type}", name="app_admin_article_index", methods={"GET"})
     * @param Request $request
     * @param string $type
     * @return Response
     */
    public function indexByType(Request $request, string $type): Response
    {
        return $this->render('Admin/views/article/index_'.$type.'.html.twig', compact('type'));
    }

    public function add(Request $request): Response
    {
        throw new \Exception('Not implemented');
    }

    /**
     * @Route(path="/{type}/add", name="app_admin_article_add")
     * @param Request $request
     * @param string $type
     * @return Response
     * @throws \Exception
     */
    public function addArticle(Request $request, string $type): Response
    {
        return $this->forward(ArticleUtils::getStrControllerByType($type, 'add'), ['request', $request,'type' => $type]);
    }

    public function edit(Request $request, int $id): Response
    {
        throw new \Exception('Not implemented');
    }


    /**
     * @Route(path="/{type}/edit/{id}", name="app_admin_article_edit", methods={"GET", "POST"})
     * @param Request $request
     * @param string $type
     * @param int $id
     * @return Response
     * @throws \Exception
     */
    public function editArticle(Request $request, string $type ,int $id): Response
    {
        $article = $this->articleService->findOneById($id);
        return $this->forward(ArticleUtils::getStrControllerByType($type, 'edit'), [
            'request' => $request,
            'type' => $type,
            'article' => $article,
        ]);
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
    /**
     * @Route(path="/{type}/{slug}", name="app_admin_article_detail", methods={"GET"})
     */
    public function showDetail(string $type, string $slug): Response
    {
        $article = $this->articleService->findOneBySlug($slug);

        if (!$article) {
            throw $this->createNotFoundException('Bài viết không tồn tại');
        }

        return $this->render('Admin/views/article/detail_article.html.twig', [
            'article' => $article,
            'type' => $type
        ]);
    }
}
