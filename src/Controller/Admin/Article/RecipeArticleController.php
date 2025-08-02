<?php

namespace App\Controller\Admin\Article;

use App\Core\DataType\ArticleDataType;
use App\Core\Entity\Article;
use App\Core\Services\ArticleService;
use App\Form\Admin\Article\AddNewsArticleForm;
use App\Form\Admin\Article\AddRecipeArticleForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RecipeArticleController extends AbstractController
{
    private ArticleService $articleService;
    private EntityManagerInterface $entityManager;

    public function __construct(ArticleService $articleService, EntityManagerInterface $entityManager)
    {
        $this->articleService = $articleService;
        $this->entityManager = $entityManager;
    }

    public function add(Request $request, string $type): Response
    {
        $article = new Article();
        $article->setAuthor($this->getUser());
        $form = $this->createForm(AddRecipeArticleForm::class , $article, [
            'action' => $this->generateUrl('app_admin_article_add', ['type' => $type]),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article->getPost()->setPostType(ArticleDataType::TYPE_RECIPE_ARTICLE);
            $this->entityManager->persist($article);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_admin_article_edit', ['id' => $article->getId(), 'type' => $type]);
        }
        return $this->render('Admin/views/article/add_recipe.html.twig', compact(['form', 'article', 'type']));
    }

    public function edit(Request $request, string $type, Article $article): Response
    {
        $form = $this->createForm(AddRecipeArticleForm::class , $article, [
            'action' => $this->generateUrl('app_admin_article_edit', ['type' => $type, 'id' => $article->getId()]),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return new JsonResponse(['message' => 'Edited Article successfully'], Response::HTTP_OK);
        }
        return $this->render('Admin/views/article/add_recipe.html.twig', compact(['form', 'article', 'type']));
    }
}