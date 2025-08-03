<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Controller\CRUDActionInterface;
use App\Core\DataType\ArchivedDataType;
use App\Core\DataType\PostStatusType;
use App\Core\Entity\Page;
use App\Core\Services\PageService;
use App\Form\Admin\Page\AddPageForm;
use App\Security\Voter\PageVoter;
use Cocur\Slugify\Slugify;
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
        $pagination = $this->pageService->findAllPaginated();
        return $this->render('Admin/views/page/index.html.twig', compact('pagination'));
    }

    /**
     * @Route(path="/add", name="app_admin_page_add")
     * @param Request $request
     * @return Response
     */
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
    /**
     * @Route(path="/edit/{id}", name="app_admin_page_edit")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, int $id): Response
    {
        $page = $this->pageService->findOneById($id);
        if(!$page instanceof Page) throw new NotFoundHttpException();

        $form = $this->createForm(AddPageForm::class , $page, [
            'action' => $this->generateUrl('app_admin_page_edit', ['id' => $page->getId()]),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $isHot = $form->get('post')->get('isHot')->getData();
            $isNew = $form->get('post')->get('isNew')->getData();
            $isKeepSlug = $request->request->get('keep_slug');

            if(is_string($isHot) && $isHot == 'on')
                $page->getPost()->setIsHot(PostStatusType::HOT_TYPE_HOT);
            if(is_string($isNew) && $isNew == 'on')
                $page->getPost()->setIsNew(PostStatusType::NEW_TYPE_NEW);
            if(is_null($isKeepSlug)){
                $slugify = new Slugify();
                $slug = $slugify->slugify($page->getPost()->getTitle());
                $page->getPost()->setSlug($slug);
            }

            $this->entityManager->flush();
            return new JsonResponse([
                'message' => 'Page edited successfully',
                'redirect' => $this->generateUrl('app_admin_page_edit', ['id' => $page->getId()])
            ]);
        }

        return $this->render('Admin/views/page/add.html.twig', compact('form', 'page'));
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
