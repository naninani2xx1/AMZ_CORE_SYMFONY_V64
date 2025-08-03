<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Controller\CRUDActionInterface;
use App\Core\DataType\ArchivedDataType;
use App\Entity\Distributor;
use App\Form\AddDistributorForm;
use App\Services\DistributorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @Route(path="/cms/distributors")
 */
class DistributorController extends AbstractController implements CRUDActionInterface
{
    private EntityManagerInterface $entityManager;
    private DistributorService $distributorService;
    public function __construct(EntityManagerInterface $entityManager, DistributorService $distributorService)
    {
        $this->entityManager = $entityManager;
        $this->distributorService = $distributorService;
    }

    /**
     * @param Request $request
     * @return Response
     * @Route(path="/add", name="app_admin_distributor_add", methods={"GET", "POST"})
     */
    public function add(Request $request): Response
    {
        $distributor = new Distributor();
        $form = $this->createForm(AddDistributorForm::class , $distributor, [
            'action' => $this->generateUrl('app_admin_distributor_add'),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($distributor);
            $this->entityManager->flush();

            return new JsonResponse([
                'message' => 'Distributor added successfully',
            ]);
        }

        return $this->render('Admin/views/distributor/add_modal.html.twig', compact('form'));
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @Route(path="/edit/{id}", name="app_admin_distributor_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, int $id): Response
    {
        $distributor = $this->distributorService->findOneById($id);
        $form = $this->createForm(AddDistributorForm::class , $distributor, [
            'action' => $this->generateUrl('app_admin_distributor_edit', ['id' => $id]),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return new JsonResponse([
                'message' => 'Distributor edited successfully',
            ]);
        }

        return $this->render('Admin/views/distributor/add_modal.html.twig', compact('form', 'distributor'));
    }
    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @Route(path="/delete/{id}", name="app_admin_distributor_delete", methods={"POST"})
     */
    public function delete(Request $request, int $id): Response
    {
        $csrfToken = $request->query->get('_csrf_token');

        if (!$this->isCsrfTokenValid('distributor-delete-'.$id, $csrfToken))
            throw new AccessDeniedHttpException();
        $distributor = $this->distributorService->findOneById($id);
        if(!$distributor instanceof Distributor) throw new NotFoundHttpException();

        $distributor->setArchived(ArchivedDataType::ARCHIVED);
        $this->entityManager->flush();

        return new JsonResponse([
            'message' => "Distributor deleted Successfully"
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route(path="/", name="app_admin_distributor_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        return $this->render('Admin/views/distributor/index.html.twig');
    }
}
