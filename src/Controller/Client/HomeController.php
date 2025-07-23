<?php
namespace App\Controller\Client;

use App\Core\Controller\CRUDActionInterface;
use App\Core\Repository\ArticleRepository;
use App\Services\ArticleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="client/home")
 */
Class HomeController extends AbstractController
{
    /**
     * @Route(path="/", name="app_client_home_index")
     */
    public function index(): Response
    {
        return $this->render('Client/views/home.html.twig', []);
    }

}