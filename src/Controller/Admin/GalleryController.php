<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @Route(path="/cms")
 */
class GalleryController extends AbstractController
{
    /**
     * @Route(path="/gallery/core/open-list", name="app_admin_gallery_core_open_list")
     */
    public function openList(): Response
    {
        return $this->render('Admin/partials/gallery/open_list_management_modal.html.twig');
    }
}
