<?php

namespace App\Controller\Admin;
use App\Core\Controller\CRUDActionInterface;
use App\Core\Repository\MenuRepository;
use App\Services\MenuService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @Route("/cms/menu")
 */
class MenuController extends AbstractController implements CRUDActionInterface
{

    public function __construct(private readonly MenuService $menuService
        ,   private readonly MenuRepository $menuRepository
    )
    {

    }
    /**
     * @Route("/", name="app_admin_menu_index")
     */
    public function index(Request $request): Response
    {
      $menu = $this->menuRepository->findAllPaginated();
      return $this->render('Admin/views/menu/index.html.twig', [
          'menu' => $menu,
      ]);
    }

    /**
     * @Route("/add", name="app_admin_menu_add", methods={"GET","POST"})
     */
    public function add(Request $request): Response
    {

        return $this->menuService->add($request);
    }

    /**
     * @Route("/edit/{id}", name="app_admin_menu_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, int $id): Response
    {
        return $this->menuService->edit($request, $id);
    }

    /**
     * @Route("/delete/{id}", name="app_admin_menu_delete")
     */
    public function delete(Request $request,int $id): Response
    {
        return $this->menuService->delete($id);
    }
}
