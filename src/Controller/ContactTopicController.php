<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Controller\CRUDActionInterface;
use App\Core\DataType\ArchivedDataType;
use App\Core\DataType\CategoryDataType;
use App\Core\Entity\Category;
use App\Core\Services\CategoryService;
use App\Form\Admin\ContactTopic\AddContactTopicForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
/**
 * @Route(path="/cms/topic")
 */
class ContactTopicController extends AbstractController implements CRUDActionInterface
{
    private readonly CategoryService $categoryService;
    private readonly EntityManagerInterface $entityManager;
    public function __construct(
        CategoryService $categoryService,
        EntityManagerInterface $entityManager
    )
    {
        $this->categoryService = $categoryService;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @return Response
     * @Route(path="/", name="app_admin_contact_topic_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        return $this->render('Admin/views/contactTopic/index.html.twig');
    }

    /**
     * @param Request $request
     * @return Response
     * @Route(path="/add", name="app_admin_contact_topic_add", methods={"GET", "POST"}))
     */
    public function add(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(AddContactTopicForm::class, $category, [
            'action' => $this->generateUrl('app_admin_contact_topic_add'),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category->setType(CategoryDataType::TYPE_TOPIC_CONTACT);
            $this->entityManager->persist($category);
            $this->entityManager->flush();
            return new JsonResponse([
                'message' => 'Contact Topic created successfully!'
            ], Response::HTTP_CREATED);
        }
        return $this->render('Admin/views/contactTopic/add_modal.html.twig', compact('form'));
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @Route(path="/edit/{id}", name="app_admin_contact_topic_edit", methods={"GET","POST"}))
     */
    public function edit(Request $request, int $id): Response
    {
        $category = $this->categoryService->findById($id);
        $form = $this->createForm(AddContactTopicForm::class, $category, [
            'action' => $this->generateUrl('app_admin_contact_topic_edit', ['id' => $id]),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return new JsonResponse([
                'message' => 'Contact Topic edited successfully!'
            ], Response::HTTP_CREATED);
        }
        return $this->render('Admin/views/contactTopic/add_modal.html.twig', compact('form', 'category'));
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @Route(path="/delete/{id}", name="app_admin_contact_topic_delete", methods={"POST"}))
     */
    public function delete(Request $request, int $id): Response
    {
        $csrfToken = $request->query->get('_csrf_token');

        if (!$this->isCsrfTokenValid('contact-topic-delete-'.$id, $csrfToken))
            throw new AccessDeniedHttpException();
        $category = $this->categoryService->findById($id);
        if(!$category instanceof Category) throw new NotFoundHttpException();

        $category->setArchived(ArchivedDataType::ARCHIVED);
        $this->entityManager->flush();

        return new JsonResponse([
            'message' => "Contact topic deleted Successfully"
        ]);
    }
}
