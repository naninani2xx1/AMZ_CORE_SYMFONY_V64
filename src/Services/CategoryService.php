<?php

namespace App\Services;

use App\Core\Entity\Category;
use App\Core\Entity\Gallery;
use App\Core\Repository\CategoryRepository;
use App\Form\Admin\Category\CategoryType;
use App\Form\Admin\Gallery\GalleryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryService extends AbstractController
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function add(Request $request): Response
    {
        $category = new Category();
        $level_cartegies= $this->em(CategoryRepository::class)->findAll(

        );
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($category);
            $this->em->flush();
            return $this->redirectToRoute('app_admin_category_index');
        }

        return $this->render('Admin/views/category/form/form_add_category.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function edit(Request $request,int $id):Response
    {
        $gallery = $this->em->getRepository(Gallery::class)->find($id);
        $form=$this->createForm(GalleryType::class,$gallery);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            return $this->redirectToRoute('app_admin_gallery_index');
        }
        return $this->render('Admin/views/gallery/modals/form_edit_gallery.html.twig',[
            'form'=>$form,
            'gallery'=>$gallery,
        ]);
    }

    public function delete(Request $request,int $id):Response
    {
        $gallery = $this->em->getRepository(Gallery::class)->find($id);
        $gallery->isArchived()==0 ? $gallery->setArchived(1) : $gallery->setArchived(0);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_gallery_index');
    }
}