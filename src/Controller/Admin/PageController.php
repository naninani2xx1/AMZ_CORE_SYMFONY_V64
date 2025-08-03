<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Controller\CRUDActionInterface;
use App\Core\DataType\ArchivedDataType;
use App\Core\Entity\Page;
use App\Core\Services\PageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

/**
 * @Route(path="/cms/page")
 */
class PageController extends AbstractController implements CRUDActionInterface
{
    private $pageService;
    private EntityManagerInterface $entityManager;

    public function __construct(PageService $pageService, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->pageService = $pageService;
    }

    /**
     * @Route(path="/", name="app_admin_page_index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        return $this->render('Admin/views/page/index.html.twig');
    }

    /**
     * @Route(path="/add", name="app_admin_page_add")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
        return $this->pageService->add($request);
    }
    /**
     * @Route(path="/edit/{id}", name="app_admin_page_edit", requirements={"id"="\d+"})
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, int $id): Response
    {

        return $this->pageService->edit($request, $id);
    }
    /**
     * @Route(path="/delete/{id}", name="app_admin_page_delete")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function delete(Request $request, int $id): Response
    {
        $csrfToken = $request->query->get('_csrf_token');
        if (!$this->isCsrfTokenValid('page-delete-'.$id, $csrfToken))
            throw new AccessDeniedHttpException();

        $page = $this->pageService->findOneById($id);
        $page->setArchived(ArchivedDataType::ARCHIVED);

        $post = $page->getPost();
        $slug = $post->getSlug() .'-removed-' .Uuid::v4()->toBase32();
        $post->setSlug($slug);
        $this->entityManager->flush();

        return new Response("Deleted Page Successfully");
    }
}