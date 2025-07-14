<?php

namespace App\Controller\Admin;

use App\Core\Controller\CRUDActionInterface;
use App\Core\Repository\PostRepository;
use App\Services\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cms/post")
 */
class   PostController extends AbstractController implements CRUDActionInterface
{

    public function __construct(private readonly PostService $postService
                            ,   private readonly PostRepository $postRepository
    )
    {

    }
    /**
     * @Route("/", name="app_admin_post_index")
     */
    public function index(Request $request): Response
    {
        $posts = $this->postRepository->findAllPost();
        return $this->render('Admin/views/post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/add", name="app_admin_post_add", methods={"GET","POST"})
     */
    public function add(Request $request): Response
    {

        return $this->postService->add($request);
    }

    /**
     * @Route("/edit/{id}", name="app_admin_post_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, int $id): Response
    {
        return $this->postService->edit($request, $id);
    }

    /**
     * @Route("/destroy/{id}", name="app_admin_post_delete")
     */
    public function delete(Request $request,int $id): Response
    {
        return $this->postService->delete($request,$id);
    }
}
