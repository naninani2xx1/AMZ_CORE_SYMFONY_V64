<?php

namespace App\Services;

use App\Core\Controller\BaseController;
use App\Form\Admin\Article\AddArticleForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleService extends AbstractController
{
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function add(Request $request, int $id): Response
    {
        $form = $this->createForm(AddArticleForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //logic here
            $this->entityManager->persist($form->getData());
            $this->entityManager->flush();
        }
        return new Response("Added Article Successfully");
    }

    public function edit(Request $request, int $id): Response
    {
        return new Response("Edited Article Successfully");
    }

    public function delete(Request $request, int $id): Response
    {
        return new Response("Deleted Article Successfully");
    }
}