<?php

namespace App\Services;

use App\Core\Entity\Block;
use App\Form\Admin\Block\BlockFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockService extends AbstractController
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function add(Request $request):Response
    {
     $block = new Block();
     $form=$this->createForm(BlockFormType::class,$block);
     $form->handleRequest($request);
     if($form->isSubmitted() && $form->isValid()){
         $this->em->persist($block);
         $this->em->flush();
         return $this->redirectToRoute('app_admin_block_index');
     }

     return $this->render('Admin/views/block/form/form_add_block.html.twig',[
         'form'=>$form,
     ]);
    }
    public function edit(Request $request,int $id):Response
    {
        $block = $this->em->getRepository(Block::class)->find($id);
        $form=$this->createForm(BlockFormType::class,$block);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            return $this->redirectToRoute('app_admin_block_index');
        }
        return $this->render('Admin/views/block/form/form_edit_block.html.twig',[
            'form'=>$form,
            'block'=>$block,
        ]);
    }



    public function delete(Request $request,int $id):Response
    {
        $block = $this->em->getRepository(Block::class)->find($id);
        $block->setArchived(1);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_block_index');
    }
}