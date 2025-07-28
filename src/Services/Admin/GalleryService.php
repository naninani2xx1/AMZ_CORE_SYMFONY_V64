<?php

namespace App\Services\Admin;

use App\Core\Entity\Gallery;
use App\Form\Admin\Gallery\AddGalleryFolderForm;
use App\Form\Admin\Gallery\GalleryFolderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GalleryService extends AbstractController
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function add(Request $request): Response
    {
        $gallery = new Gallery();
        $form = $this->createForm(AddGalleryFolderForm::class, $gallery,['action' => $this->generateUrl('app_admin_gallery_add')]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($gallery);
            $this->em->flush();

            return new JsonResponse(['message'=>'Gallery Added Successfully']);
        }

        return $this->render('Admin/views/gallery/modals/form_add_gallery.html.twig', [
            'form' => $form,
        ]);
    }
    public function edit(Request $request,int $id):Response
    {
        $gallery = $this->em->getRepository(Gallery::class)->find($id);
        $form=$this->createForm(GalleryFolderType::class,$gallery,['action' => $this->generateUrl('app_admin_gallery_edit', ['id' => $id])]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            return new JsonResponse(['message'=>'Gallery Updated Successfully']);
        }
        return $this->render('Admin/views/gallery/modals/form_edit_gallery.html.twig',[
            'form'=>$form,
            'gallery'=>$gallery,
        ]);
    }



    public function delete(Request $request,int $id):Response
    {
        $gallery = $this->em->getRepository(Gallery::class)->find($id);
        $gallery->setArchived(1);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_gallery_index');
    }
}