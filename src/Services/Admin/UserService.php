<?php

namespace App\Services\Admin;

use App\Core\Entity\User;
use App\Form\Admin\User\UserEditType;
use App\Form\Admin\User\UserType;
use App\Security\Voter\UserVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserService extends AbstractController
{
    private EntityManagerInterface $em;
    private UrlGeneratorInterface $urlGenerator;

    private UserPasswordHasherInterface $userPasswordHasher;
    public function __construct(EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->em = $em;
        $this->urlGenerator = $urlGenerator;
    }

    public function add(Request $request): Response
    {

        $user = new User();
        $this->denyAccessUnlessGranted(UserVoter::CREATE,$user);
        $form = $this->createForm(UserType::class, $user, [
            'action' => $this->urlGenerator->generate('app_admin_user_add'),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plainPassword = $form->get('password')->getData();
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $plainPassword ));
            $this->em->persist($user);
            $this->em->flush();

            return $this->redirectToRoute('app_admin_user_index');
        }


        return $this->render('Admin/views/user/modals/form_add_user.html.twig', [
            'form' => $form->createView(),
           ]);
    }
    public function edit(Request $request): Response{
        $user = $this->em->getRepository(User::class)->find($request->get('id'));
        $this->denyAccessUnlessGranted(UserVoter::EDIT,$user);

        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plainPassword = $form->get('password')->getData();
            if (!empty($plainPassword)) {
                $hashedPassword = $this->userPasswordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);

            }

            $this->em->flush();
            return $this->redirectToRoute('app_admin_user_index');
        }


        return $this->render('Admin/views/user/modals/form_edit_user.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
    public function delete(Request $request): Response
    {
        $user = $this->em->getRepository(User::class)->find($request->get('id'));
        $this->denyAccessUnlessGranted(UserVoter::DELETE,$user);
        if(!$user){
            $this->addFlash('error', 'User not found');
            return $this->redirectToRoute('app_admin_user_index');
        }
            $user->setArchived(1);
        $this->em->flush();
        $this->addFlash('success', 'User deleted');
        return $this->redirectToRoute('app_admin_user_index');

    }
}