<?php

namespace App\Controller\Admin;

use App\Core\Controller\CRUDActionInterface;
use App\Core\Repository\UserRepository;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @Route("/cms/admin/user")
 */

final class UserController extends AbstractController implements CRUDActionInterface
{
    public function __construct(private readonly UserRepository $userRepository,
                                private readonly UserService $userService,
    )
    {

    }
    /**
     * @Route("/", name="app_admin_user_index")
     */
    public function index(Request $request): Response
    {
        $users=$this->userRepository->findAllUsers(1,10);
        return $this->render('Admin/views/user/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/add", name="app_admin_user_add", methods={"GET","POST"})
     */
    public function add(Request $request): Response
    {

        return $this->userService->add($request);
    }
    /**
     * @Route("/edit/{id}", name="app_admin_user_edit")
     */
    public function edit(Request $request, int $id): Response
    {
        return $this->userService->edit($request, $id);
    }

    /**
     * @Route("/delete/{id}", name="app_admin_user_delete")
     */
    public function delete(Request $request, int $id): Response
    {
        return $this->userService->delete($request ,$id);
    }
}
