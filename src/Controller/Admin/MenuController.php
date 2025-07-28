<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Controller\CRUDActionInterface;
use App\Core\DataType\ArchivedDataType;
use App\Core\Entity\Menu;
use App\Core\Services\MenuService;
use App\Form\Admin\Menu\AddMenuForm;
use App\Form\Admin\Menu\EditMenuForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @Route("/cms/menu")
 */
class MenuController extends AbstractController implements CRUDActionInterface
{
    private EntityManagerInterface $entityManager;
    private MenuService $menuService;
    public function __construct(EntityManagerInterface $entityManager, MenuService $menuService)
    {
        $this->entityManager = $entityManager;
        $this->menuService = $menuService;
    }

    /**
     * @param Request $request
     * @return Response
     * @Route(path="/", name="app_admin_menu_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        return $this->render('Admin/views/menu/index.html.twig');
    }

    /**
     * @param Request $request
     * @return Response
     * @Route(path="/add", name="app_admin_menu_add", methods={"GET", "POST"})
     */
    public function add(Request $request): Response
    {
        $menu = new Menu();
        $menu->setAuthor($this->getUser());
        $form = $this->createForm(AddMenuForm::class, $menu, [
            'action' => $this->generateUrl('app_admin_menu_add'),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($menu);
            $this->entityManager->flush();
            return new JsonResponse([
                'message' => 'Menu added successfully',
            ]);
        }

        return $this->render('Admin/views/menu/add_modal.html.twig', compact('form'));
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @Route(path="/edit/{id}", name="app_admin_menu_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, int $id): Response
    {
        $menu = $this->menuService->findOneById($id);
        $form = $this->createForm(EditMenuForm::class, $menu, [
            'action' => $this->generateUrl('app_admin_menu_edit', ['id' => $id]),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return new JsonResponse([
                'message' => 'Menu edited successfully',
            ]);
        }

        return $this->render('Admin/views/menu/edit_modal.html.twig', compact('form', 'menu'));
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @Route(path="/delete/{id}", name="app_admin_menu_delete", methods={"POST"})
     */
    public function delete(Request $request, int $id): Response
    {
        $csrfToken = $request->query->get('_csrf_token');
        if (!$this->isCsrfTokenValid('menu-delete-'. $id, $csrfToken)) throw $this->createAccessDeniedException();
        $menu = $this->menuService->findOneById($id);
        if(!$menu instanceof Menu) throw $this->createNotFoundException();

        $menu->setArchived(ArchivedDataType::ARCHIVED);
        $this->entityManager->flush();

        return new JsonResponse([
            'message' => 'Menu deleted successfully',
        ]);
    }
}
