<?php

declare(strict_types=1);

namespace App\Controller\EndUser;

use App\Core\Services\PageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

class PageController extends AbstractController
{
    private $pageService;
    private $twig;
    public function __construct(PageService $pageService, Environment $twig)
    {
        $this->twig = $twig;
        $this->pageService = $pageService;
    }

    /**
     * @Route("/{slug}.html", name="app_enduser_page_index", methods={"GET"})
     */
    public function index(string $slug): Response
    {
        $page = $this->pageService->findOneBySlug($slug);

        $post = $page->getPost();
        if (empty($post)){
            throw new NotFoundHttpException('Page not found',null, Response::HTTP_NOT_FOUND);
        }
        $themes = $this->getParameter('themes');
        $template = $themes ."/page/index.html.twig";
        $pageTemplate = $themes ."/post/page/".$post->getSlug().".html.twig";
        if($this->twig->getLoader()->exists($pageTemplate))
            $template = $pageTemplate;

        return $this->render($template, array(
            'page' => $pa-+ge,
            'post' => $post
        ));
    }
}
