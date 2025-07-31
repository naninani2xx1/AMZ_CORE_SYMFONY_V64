<?php

namespace App\Core\Services;

use App\Core\Entity\Article;
use App\Core\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleService extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private ArticleRepository $articleRepository;
    public function __construct(EntityManagerInterface $entityManager, ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->entityManager = $entityManager;
    }

    public function findOneById(int $id): ?Article
    {
        return $this->articleRepository->find($id);
    }
}