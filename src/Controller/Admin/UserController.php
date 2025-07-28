<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Controller\CRUDActionInterface;
use App\Core\Entity\User;
use App\Core\Services\UserService;
use App\Form\Admin\User\AddUserForm;
use App\Security\Voter\UserVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @Route("/cms/admin/user")
 */
class UserController extends AbstractController implements CRUDActionInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly UserService $userService,
    )
    {
    }

    /**
     * @Route("/", name="app_admin_user_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::VIEW, null);
        return $this->render('Admin/views/user/index.html.twig');
    }

    /**
     * @Route("/add", name="app_admin_user_add", methods={"GET", "POST"})
     */
    public function add(Request $request): Response
    {
        $user = new User();
        $this->denyAccessUnlessGranted(UserVoter::EDIT, $user);
        return $this->processAddEditForm($user, $request);
    }
    /**
     * @Route("/edit/{id}", name="app_admin_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, int $id): Response
    {
        $user = $this->userService->findOneUser($id);
        $this->denyAccessUnlessGranted(UserVoter::EDIT, $user);
        return $this->processAddEditForm($user, $request, false);
    }

    private function processAddEditForm(User $user, Request $request, bool $isCreate = true): Response
    {
        $form = $this->createForm(AddUserForm::class, $user, [
            'action' => $isCreate ? $this->generateUrl('app_admin_user_add')
            : $this->generateUrl('app_admin_user_edit', ['id' => $user->getId()]),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if(!empty($form->get('password')->getData()))
                $user->setPassword($this->userPasswordHasher->hashPassword($user, $form->get('password')->getData()));
            if($isCreate)
                $this->entityManager->persist($user);
            $this->entityManager->flush();

            return new JsonResponse([
                'message' =>  $isCreate ? 'User created successfully' : "User updated successfully",
            ]);
        }elseif($form->isSubmitted() && !$form->isValid()){
            $messageError = "";
            $errors = $form->getErrors(true);
            /** @var FormError $error */
            foreach ($errors as $error) {
                if($error->getOrigin()->getConfig()->getName() == $form->getName())
                    break;
                $messageError = $error->getMessage();
                break;
            }
            if(!empty($messageError))
                return new JsonResponse(['message' => $messageError], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->render('Admin/views/user/add_modal.html.twig', compact('form', 'user'));
    }

    /**
     * @Route("/delete/{id}", name="app_admin_user_delete", methods={"POST"})
     */
    public function delete(Request $request, int $id): Response
    {
        $user = $this->userService->findOneUser($id);
        $this->denyAccessUnlessGranted(UserVoter::DELETE, $user);

        $csrfToken = $request->query->get('_csrf_token');
        if(!$this->isCsrfTokenValid('user-delete-'. $id, $csrfToken))
            throw new AccessDeniedHttpException();

        $user->setArchived(true);
        $this->entityManager->flush();
        return new JsonResponse([
            'message' => 'User deleted successfully',
        ]);
    }
}
