<?php

namespace App\Core\Services;

use App\Core\DataType\ArchivedDataType;
use App\Core\Entity\Page;
use App\Core\Repository\PageRepository;
use App\Form\Admin\Page\AddPageForm;
use App\Security\Voter\PageVoter;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageService extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private PageRepository $pageRepository;
    private FileUploadService $fileUploadService;

    public function __construct(EntityManagerInterface $entityManager, PageRepository $pageRepository, FileUploadService $fileUploadService)
    {
        $this->entityManager = $entityManager;
        $this->fileUploadService = $fileUploadService;
        $this->pageRepository = $pageRepository;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    public function findAllPaginated(): PaginationInterface
    {
        return $this->pageRepository->findAllPaginated();
    }


    public function add(Request $request): Response
    {
        $page = new Page();
        //check permission
        $this->denyAccessUnlessGranted(PageVoter::ADD, $page);

        $form = $this->createForm(AddPageForm::class, $page);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //logic here
            $this->entityManager->persist($page);
            $this->entityManager->persist($page->getPost());
            $this->entityManager->flush();

            return new JsonResponse([
                'message' => 'Page added successfully',
                'redirect' => $this->generateUrl('app_admin_page_edit', ['id' => $page->getId()])
            ]);
        }
        return $this->render('Admin/views/page/add.html.twig', compact('form', 'page'));
    }

    public function edit(Request $request, int $id): Response
    {

        $page = $this->pageRepository->find($id);
        if(!$page instanceof Page) throw new NotFoundHttpException();
        $form = $this->createForm(AddPageForm::class , $page, [
            'action' => $this->generateUrl('app_admin_page_edit', ['id' => $id]),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return new JsonResponse([
                'message' => 'Page edit successfully',
                'redirect' => $this->generateUrl('app_admin_page_edit', ['id' => $page->getId()])
            ]);
        }

        return $this->render('Admin/views/page/edit.html.twig', compact('form', 'page'));
    }

    /**
     * Archived Page
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function delete(Request $request, int $id): Response
    {
        $csrfToken = $request->request->get('_csrf_token');
        if (!$this->isCsrfTokenValid('page-delete-'.$id, $csrfToken))
            throw new AccessDeniedHttpException();

        $page = $this->pageRepository->find($id);
        if(!$page instanceof Page) throw new NotFoundHttpException();

        $page->setArchived(ArchivedDataType::ARCHIVED);
        $this->entityManager->flush();

        return new Response("Deleted Page Successfully");
    }

    public function findOneById($id): Page
    {
        return $this->pageRepository->find($id);
    }

    public function findOneBySlug(string $slug): Page
    {
        return $this->pageRepository->findOneBySlug($slug);
    }
}