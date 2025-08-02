<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Controller\CRUDActionInterface;
use App\Core\DataType\ArchivedDataType;
use App\Core\Services\ArticleService;
use App\Utils\ArticleUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

/**
 * @Route(path="/cms/article")
 */
class ArticleController extends AbstractController implements CRUDActionInterface
{
    private ArticleService $articleService;
    private EntityManagerInterface $entityManager;

    public function __construct(ArticleService $articleService, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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
     * @Route(path="/delete/{id}", name="app_admin_article_delete", methods={"POST"})
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function delete(Request $request, int $id): JsonResponse
    {
        $csrfToken = $request->query->get('_csrf_token');

        if (!$this->isCsrfTokenValid('article-delete-'.$id, $csrfToken))
            throw new AccessDeniedHttpException();
        $article = $this->articleService->findOneById($id);

        $article->setArchived(ArchivedDataType::ARCHIVED);
        $post = $article->getPost();
        $slug = $post->getSlug() .'-removed-' .Uuid::v4()->toBase32();
        $post->setSlug($slug);
        $this->entityManager->flush();

        return new JsonResponse([
            'message' => "Category deleted Successfully"
        ]);
    }
}
