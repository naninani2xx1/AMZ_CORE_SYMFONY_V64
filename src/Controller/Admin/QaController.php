<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Controller\CRUDActionInterface;
use App\Core\Entity\Qa;
use App\Core\Services\QaService;
use App\Form\Admin\Qa\AddQaForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;
/**
 * @Route("/cms/qa")
 */
class QaController extends AbstractController implements CRUDActionInterface
{
    private EntityManagerInterface $entityManager;
    private QaService $qaService;
    public function __construct(
        EntityManagerInterface $entityManager,
        QaService $qaService
    )
    {
        $this->qaService = $qaService;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @return Response
     * @Route(path="/", name="app_admin_qa_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        return $this->render('Admin/views/qa/index.html.twig');
    }

    /**
     * @param Request $request
     * @return Response
     * @Route(path="/add", name="app_admin_qa_add", methods={"GET", "POST"})
     */
    public function add(Request $request): Response
    {
        $qa = new Qa();
        $form = $this->createForm(AddQaForm::class, $qa, [
            'action' => $this->generateUrl('app_admin_qa_add'),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($qa);
            $this->entityManager->flush();
            return new JsonResponse(['message' => 'Qa created successfully'], Response::HTTP_CREATED);
        }

        return $this->render('Admin/views/qa/add_modal.html.twig', compact('form'));
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @Route(path="/edit/{id}", name="app_admin_qa_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, int $id): Response
    {
        $qa = $this->qaService->findOneById($id);
        $form = $this->createForm(AddQaForm::class, $qa, [
            'action' => $this->generateUrl('app_admin_qa_edit',['id'=>$id]),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return new JsonResponse(['message' => 'Qa edited successfully'], Response::HTTP_CREATED);
        }

        return $this->render('Admin/views/qa/add_modal.html.twig', compact('form', 'qa'));
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @Route(path="/delete/{id}", name="app_admin_qa_delete", methods={"POST"})
     */
    public function delete(Request $request, int $id): Response
    {
        $qa = $this->qaService->findOneById($id);
//        $this->denyAccessUnlessGranted(UserVoter::DELETE, $user);

        $csrfToken = $request->query->get('_csrf_token');
        if(!$this->isCsrfTokenValid('qa-delete-'. $id, $csrfToken))
            throw new AccessDeniedHttpException();

        $qa->setArchived(true);
        $this->entityManager->flush();
        return new JsonResponse([
            'message' => 'Qa deleted successfully',
        ]);
    }
}
