<?php

namespace App\Twig\Runtime;

use App\Core\Services\ArticleService;
use Knp\Component\Pager\PaginatorInterface;
use Twig\Extension\RuntimeExtensionInterface;

class ArticleExtensionRuntime implements RuntimeExtensionInterface
{
    private ArticleService $articleService;
    private PaginatorInterface $paginator;
    public function __construct(ArticleService $articleService, PaginatorInterface $paginator)
    {
        $this->articleService = $articleService;
        // Inject dependencies if needed
    }
}
