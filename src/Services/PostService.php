<?php

namespace App\Services;

use App\Core\Entity\Article;
use App\Core\Entity\Post;
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

    public function add(Request $request):Response
    {
     $post = new Post();
        $article = new Article();
        $article->setAuthor($this->getUser());
        $article->setPost($post);
        $post->setArticle($article);
        $form = $this->createForm(PostType::class, $post);
         $form->handleRequest($request);
      if($form->isSubmitted() && $form->isValid()){

         $this->em->persist($post);
         $this->em->flush();
         return $this->redirectToRoute('app_admin_post_index');
     }

     return $this->render('Admin/views/post/form/form_add_post.html.twig',[
         'form'=>$form,
     ]);
    }
    public function edit(Request $request,int $id):Response
    {
        $post = $this->em->getRepository(Post::class)->find($id);
        $form=$this->createForm(PostType::class,$post);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            return $this->redirectToRoute('app_admin_post_index');
        }
        return $this->render('Admin/views/post/form/form_edit_post.html.twig',[
            'form'=>$form,
            'post'=>$post,
        ]);
    }



    public function delete(Request $request,int $id):Response
    {
        $post = $this->em->getRepository(Post::class)->find($id);
        $post->isArchived()==0 ? $post->setArchived(1) : $post->setArchived(0);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_post_index');
    }
}