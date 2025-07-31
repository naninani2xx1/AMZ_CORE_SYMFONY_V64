<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @Route(path="/cms/ckeditor")
 */
class CkeditorController extends AbstractController
{
    /**
     * @Route(path="/open-popup", name="app_admin_ckeditor_open_popup", methods={"GET"})
     * @return Response
     */
    public function core(): Response
    {
        return $this->render('Admin/partials/core_ckeditor_pop_up.html.twig');
    }
}
