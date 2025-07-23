<?php

namespace App\Services;

use App\Core\DataType\ArchivedDataType;
use App\Core\Entity\Page;
use App\Core\Repository\MenuRepository;
use App\Core\Repository\PageRepository;
use App\Form\Admin\Article\AddArticleForm;
use App\Form\Admin\Page\PageType;
use App\Security\Voter\PageVoter;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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

        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($page);
            $this->entityManager->flush();

        }
        return $this->render('Admin/views/page/add.html.twig', compact('form', 'page'));
    }

    public function edit(Request $request, int $id): Response
    {

        $page = $this->pageRepository->find($id);
        if(!$page instanceof Page) throw new NotFoundHttpException();

        $form = $this->createForm(PageType::class , $page, [
            'action' => $this->generateUrl('app_admin_page_edit', ['id' => $page->getId()]),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this ->redirectToRoute('app_admin_page_index');
        }

        return $this ->render('Admin/views/page/edit.html.twig', [
            'form' => $form,
            'page' => $page,
        ]);
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
        if (!$this->isCsrfTokenValid($csrfToken, 'page-delete-'.$id))
            throw new AccessDeniedHttpException();

        $page = $this->pageRepository->find($id);
        if(!$page instanceof Page) throw new NotFoundHttpException();

        $page->setArchived(ArchivedDataType::ARCHIVED);
        $this->entityManager->flush();

        return new Response("Deleted Page Successfully");
    }
}