<?php

namespace App\Services;

use App\Core\Entity\Page;
use App\Form\Admin\Article\AddArticleForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageService extends AbstractController
{
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function add(Request $request): Response
    {
        $page = new Page();
        $form = $this->createForm(AddArticleForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //logic here
            $this->entityManager->persist($form->getData());
            $this->entityManager->flush();
        }
        return new Response("Added Page Successfully");
    }

    public function edit(Request $request, int $id): Response
    {
        return new Response("Edited Page Successfully");
    }

    public function delete(Request $request, int $id): Response
    {
        return new Response("Deleted Page Successfully");
    }
}