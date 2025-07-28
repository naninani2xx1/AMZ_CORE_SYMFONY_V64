<?php

namespace App\Services\Admin;

use App\Core\Entity\Article;
use App\Core\Entity\Post;
use App\Form\Admin\Article\AddArticleForm;
use App\Form\Admin\Post\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostService extends AbstractController
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function add(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(AddArticleForm::class, $post);

        $user = $this->getUser();
        $article = new Article();
        $article->setAuthor($user);
        $article->setPost($post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($post);
            $this->em->persist($article);
            $this->em->flush();

            return $this->redirectToRoute('app_admin_post_edit', [
                'id' => $post->getId()
            ]);
        }

        return $this->render('Admin/views/article/form/form_add_article.html.twig', [
            'form' => $form,
        ]);
    }


    public function edit(Request $request,int $id):Response
    {
        $post = $this->em->getRepository(Post::class)->find($id);
        $form=$this->createForm(PostType::class,$post,[
            'action'=> $this->generateUrl('app_admin_post_edit',['id'=>$post->getId()]),
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            return $this->redirectToRoute('app_admin_article_index');
        }
        return $this->render('Admin/views/post/form/form_edit_post.html.twig',[
            'form'=>$form,
            'post'=>$post,
        ]);
    }



    public function delete(Request $request,int $id):Response
    {
        $post = $this->em->getRepository(Post::class)->find($id);
        $post->post->setArchived(1);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_post_index');
    }
}