<?php
namespace App\Controller\Client;


use App\Core\Entity\Article;
use App\Entity\Contact;
use App\Form\Client\Contact\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="client/article")
 */
Class ArticleController extends AbstractController
{
    private EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    /**
     * @Route(path="/", name="app_client_article_index",methods={"GET","POST"})
     */
    public function index(Request $request): Response
    {
        $page = max(1, $request->query->getInt('page', 1));

        $pagination = $this->em->getRepository(Article::class)->findAllArticle($page, 6);

        return $this->render('Client/views/article/article.html.twig', [
            'articles' => $pagination,

        ]);
    }


    /**
     * @Route(path="/detail/{id}", name="app_client_article_detail",methods={"GET","POST"})
     */
    public function detail(Request $request, int $id): Response
    {
        $relatedArticles = $this->em->getRepository(Article::class)->findRelateArticle($id, $this->em);
        $data = $this->em->getRepository(Article::class)->findOneBy(['id'=>$id]);
        return $this->render('Client/views/article/article_detail.html.twig',  [
            'article' => $data,
            'relatedArticles' => $relatedArticles,
        ]);
    }
}