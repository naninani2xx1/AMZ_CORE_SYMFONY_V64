<?php

declare(strict_types=1);

namespace App\Controller\Admin\Block;

use App\Core\Entity\Block;
use App\Form\Admin\Block\EditBlockCommonForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class BlockCommon extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public function edit(Request $request, Block $block): Response
    {
        $page = $block->getPost()->getPage();
        $form = $this->createForm(EditBlockCommonForm::class, $block, [
            'action' => $this->generateUrl('app_admin_block_edit', ['id' => $block->getId()]),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Edited successfully');
        }
        return $this->render('Admin/views/block/edit_block_common.html.twig', compact('form', 'block', 'page'));
    }
}
