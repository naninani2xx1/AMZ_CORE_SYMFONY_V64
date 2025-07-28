<?php

namespace App\Services;

use App\Entity\TopicContact;
use App\Form\Admin\TopicContact\TopicContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TopicService extends AbstractController
{

    private EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function add(Request $request): Response{
        $topic = new TopicContact();
        $form = $this->createForm(TopicContactType::class, $topic,['action' => $this->generateUrl('app_admin_topic_add')]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($topic);
            $this->em->flush();

            return new JsonResponse(['message' => 'Topic added successfully!']);
        }

        return $this->render('Admin/views/topic/form/form_add_topic.html.twig', [
            'form' => $form,
        ]);
    }

    public function edit(Request $request,int $id): Response{
        $topic = $this->em->getRepository(TopicContact::class)->find($id);
        $form = $this->createForm(TopicContactType::class, $topic,['action' => $this->generateUrl('app_admin_topic_edit',['id' => $id])]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            return new JsonResponse(['message' => 'Topic updated successfully!']);
        }
        return $this->render('Admin/views/topic/form/form_edit_topic.html.twig', [
            'form' => $form,
            'topic' => $topic,
        ]);
    }
    public function delete(Request $request,int $id): Response{
        $topic = $this->em->getRepository(TopicContact::class)->find($id);
        $topic->setArchived(1);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_topic_index');
    }
}