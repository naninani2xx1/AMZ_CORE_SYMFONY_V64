<?php

namespace App\Core\Services;

use App\Core\Entity\Article;
use App\Core\Repository\ArticleRepository;
use App\Core\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleService extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private ArticleRepository $articleRepository;
    private PostRepository $postRepository;
    public function __construct(PostRepository $postRepository,EntityManagerInterface $entityManager, ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->entityManager = $entityManager;
        $this->postRepository = $postRepository;
    }

    public function findOneById(int $id): ?Article
    {
        return $this->articleRepository->find($id);
    }
    public function findOneBySlug(string $slug): ?Article
    {
        return $this->postRepository->findOneBy(['slug' => $slug]);
    }
}