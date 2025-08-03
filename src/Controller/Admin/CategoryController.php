<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Controller\CRUDActionInterface;
use App\Core\DataType\ArchivedDataType;
use App\Core\Entity\Category;
use App\Core\Services\CategoryService;
use App\Utils\CategoryUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

/**
 * @Route(path="/cms/category")
 */
class CategoryController extends AbstractController implements CRUDActionInterface
{
    private $categoryService;
    private $entityManager;

    public function __construct(CategoryService $categoryService, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->categoryService = $categoryService;
    }


    public function index(Request $request): Response
    {
        throw new \Exception('Not implemented');
    }

    /**
     * @Route(path="/{type}", name="app_admin_category_index", methods={"GET"})
     * @param Request $request
     * @param string $type
     * @return Response
     */
    public function indexAction(Request $request, string $type): Response
    {
        return $this->render('Admin/views/category/index.html.twig', compact('type'));
    }

    public function add(Request $request): Response
    {
        throw new \Exception('Not implemented');
    }

    /**
     * @Route(path="/{type}/add", name="app_admin_category_add", methods={"GET", "POST"})
     * @param Request $request
     * @param string $type
     * @return Response
     */
    public function addAction(Request $request, string $type): Response
    {
        return $this->forward(CategoryUtils::getStrControllerByType($type, 'add'), ['request' => $request, 'type' => $type]);
    }

    public function edit(Request $request, int $id): Response
    {
       throw new \Exception('Not implemented');
    }


    /**
     * @Route(path="/{type}/edit/{id}", name="app_admin_category_edit", methods={"GET", "POST"})
     * @param Request $request
     * @param int $id
     * @param string $type
     * @return Response
     */
    public function editAction(Request $request, int $id, string $type): Response
    {
        $category = $this->categoryService->findById($id);
        return $this->forward(CategoryUtils::getStrControllerByType($type, 'edit'), [
            'request' => $request,
            'type' => $type,
            'category' => $category,
        ]);
    }

    public function delete(Request $request, int $id): Response
    {
        throw new \Exception('Not implemented');
    }

    /**
     * @Route(path="/{type}/delete/{id}", name="app_admin_category_delete")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function deleteAction(Request $request, int $id): Response
    {
        $csrfToken = $request->query->get('_csrf_token');

        if (!$this->isCsrfTokenValid('category-delete-'.$id, $csrfToken))
            throw new AccessDeniedHttpException();
        $category = $this->categoryService->findById($id);
        if(!$category instanceof Category)
            throw new NotFoundHttpException();

        $category->setArchived(ArchivedDataType::ARCHIVED);

        $slug = $category->getSlug() .'-removed-' .Uuid::v4()->toBase32();
        $category->setSlug($slug);
        $this->entityManager->flush();

        return new JsonResponse([
            'message' => "Category deleted Successfully"
        ]);
    }

}
