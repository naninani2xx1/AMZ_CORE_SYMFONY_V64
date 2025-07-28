<?php

namespace App\Services\Admin;

use App\Core\Entity\Block;
use App\Form\Admin\Block\BlockAddType;
use App\Form\Admin\Block\BlockFormType;
use App\Utils\BlockFieldMap;
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

    public function add(Request $request): Response
    {
        $block = new Block();
        $form = $this->createForm(BlockAddType::class, $block);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($block);
            $this->em->flush();

            return $this->redirectToRoute('app_admin_block_index');
        }
        return $this->render('Admin/views/block/form/form_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    public function edit(Request $request, int $id): Response
    {
        $block = $this->em->getRepository(Block::class)->find($id);

        if (!$block) {
            throw $this->createNotFoundException('Block not found');
        }
        $kindData = [];
        if ($block->getKind() === 'contact_info') {
            $kindData = json_decode($block->getContent() ?? '{}', true);
        }
        $form = $this->createForm(BlockFormType::class, $block, [
            'kind_data' => $kindData,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($block->getKind() === 'contact_info' && $form->has('kindData')) {
                $data = $form->get('kindData')->getData();
                $block->setContent(json_encode($data,JSON_UNESCAPED_UNICODE));
            }

            $this->em->flush();
            return $this->redirectToRoute('app_admin_block_index');
        }

        return $this->render('Admin/views/block/form/form_edit_block.html.twig', [
            'form' => $form->createView(),
            'block' => $block,
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