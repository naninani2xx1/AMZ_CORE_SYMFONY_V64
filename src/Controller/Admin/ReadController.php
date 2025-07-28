<?php

namespace App\Controller\Admin;

use App\Core\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route(path="/cms/read")
 */
class ReadController extends AbstractController
{
    private $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @Route(path="/article/read/{id}", name="app_admin_article_read")
     */
    public function readArticle(Request $request, int $id): Response
    {
        $data=$this->articleRepository->findOneBy(['id'=>$id]);

        return $this->render('Admin/views/article/detail_article.html.twig', [
            'article' => $data,
        ]);
    }
}