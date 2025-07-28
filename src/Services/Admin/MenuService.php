<?php

namespace App\Services\Admin;

use App\Core\Entity\Menu;
use App\Form\Admin\Menu\MenuType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MenuService extends AbstractController
{
    private EntityManagerInterface $em;
public function __construct(EntityManagerInterface $em)
{
    $this->em=$em;
}

    public function add(Request $request): Response
    {
    $menu = new Menu();
    $form=$this->createForm(MenuType::class,$menu);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $this->em->persist($menu);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_menu_index');
    }
        return $this->render('Admin/views/menu/form/form_add_menu.html.twig',['form'=>$form->createView()]);
    }

    public function edit(Request $request, int $id): Response{
        $menu = $this->em->getRepository(Menu::class)->find($id);
        $form=$this->createForm(MenuType::class,$menu);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('app_admin_menu_index');
        }
        return $this->render('Admin/views/menu/form/form_edit_menu.html.twig',['form'=>$form->createView(),'menu'=>$menu]);
    }

    public function delete(Request $request,int $id): Response
    {
        $menu = $this->em->getRepository(Menu::class)->find($id);
        $menu->setIsActive(false);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_menu_index');
    }

}