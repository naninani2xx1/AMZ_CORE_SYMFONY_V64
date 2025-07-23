<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Controller\CRUDActionInterface;
use App\Core\Repository\ArticleRepository;
use App\Services\ArticleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/cms/admin/article")
 */
class ArticleController extends AbstractController implements CRUDActionInterface
{
    private ArticleService $articleService;

    public function __construct(ArticleService $articleService,
        private ArticleRepository $articleRepository
    )
    {
        $this->articleService = $articleService;
    }

    /**
     * @Route(path="/", name="app_admin_article_index")
     */
    public function index(Request $request): Response
    {
      $data=$this->articleRepository->findAllArticle();
      return  $this->render('Admin/views/article/index.html.twig', [
          'articles' => $data,
      ]);
    }

    /**
     * @Route(path="/add", name="app_admin_article_add")
     */
    public function add(Request $request): Response
    {
        return $this->articleService->add($request);
    }

    /**
     * @Route(path="/edit/{id}", name="app_admin_article_edit")
     */
    public function edit(Request $request, int $id): Response
    {
        return $this->articleService->edit($request, $id);
    }

    /**
     * @Route(path="/delete/{id}", name="app_admin_article_delete")
     */
    public function delete(Request $request, int $id): Response
    {
        return $this->articleService->delete($request, $id);
    }
}
