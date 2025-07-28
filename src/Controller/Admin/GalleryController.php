<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Controller\CRUDActionInterface;
use App\Core\Repository\GalleryRepository;
use App\Services\Admin\GalleryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @Route("/cms/gallery")
 */
class GalleryController extends AbstractController implements CRUDActionInterface
{

    public function __construct(GalleryRepository $galleryRepository,
                                private readonly GalleryService $galleryService,
    )
    {
        $this->galleryRepository = $galleryRepository;
    }

    /**
    @Route("/", name="app_admin_gallery_index")
     */
    public function index(Request $request): Response
    {
        $galleries=$this->galleryRepository->findGallery();
        return $this->render('Admin/views/gallery/index.html.twig', [
            'galleries' => $galleries,
        ]);
    }
    /**
     * @Route("/add", name="app_admin_gallery_add", methods={"GET","POST"})
     */
    public function add(Request $request): Response
    {
        return $this->galleryService->add($request);
    }
    /**
     * @Route("/edit/{id}", name="app_admin_gallery_edit")
     * */
    public function edit(Request $request, int $id): Response
    {
        return $this->galleryService->edit($request, $id);
    }
    /**
    @Route("/delete/{id}", name="app_admin_gallery_delete")
     */
    public function delete(Request $request, int $id): Response
    {
        return $this->galleryService->delete($request, $id);
    }

    /**
     * @Route("/show_picture/{id}", name="app_admin_gallery_show")
     */
    public function showPicture(Request $request, int $id, GalleryRepository $gallery): Response
    {
        $gallery = $gallery->find($id);
        if (!$gallery) {
            throw $this->createNotFoundException('Không tìm thấy gallery');
        }
        $galleryPictures = $gallery->getGalleryPictures();
        $pictures = [];
        foreach ($galleryPictures as $galleryPicture) {
            $picture = $galleryPicture->getPicture();
            if ($picture !== null) {
                $pictures[] = $picture;
            }
        }
        return $this->render('Admin/views/gallery/pictures.html.twig', [
            'gallery' => $gallery,
            'pictures' => $pictures,
        ]);

    }
    /**
     * @Route(path="/core/open-list", name="app_admin_gallery_core_open_list")
     */
    public function openList(): Response
    {
        return $this->render('Admin/partials/gallery/open_list_management_modal.html.twig');
    }

}