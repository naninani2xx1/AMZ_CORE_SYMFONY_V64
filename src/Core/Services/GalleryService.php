<?php

namespace App\Core\Services;

use App\Core\Repository\GalleryRepository;
use Knp\Component\Pager\PaginatorInterface;

class GalleryService
{
    private $galleryRepository;
    private $paginator;
    public function __construct(GalleryRepository $galleryRepository, PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
        $this->galleryRepository = $galleryRepository;
    }

    public function findAllFolderGalleries()
    {
        return $this->galleryRepository->findAllFolderGalleries();
    }
}