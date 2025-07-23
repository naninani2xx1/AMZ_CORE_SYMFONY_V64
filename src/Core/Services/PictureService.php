<?php

namespace App\Core\Services;

use App\Core\Entity\Gallery;
use App\Core\Entity\Picture;
use App\Core\Repository\PictureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

class PictureService
{
    private $pictureRepository;
    private $paginator;
    private $entityManager;

    public function __construct(PictureRepository $pictureRepository, PaginatorInterface $paginator, EntityManagerInterface $entityManager)
    {
        $this->paginator = $paginator;
        $this->pictureRepository = $pictureRepository;
        $this->entityManager = $entityManager;
    }

    public function findByGallery(Gallery $gallery)
    {
        return $this->pictureRepository->findByGallery($gallery);
    }

    public function addGlobalByGallery(Gallery $gallery, string $url, string $name): void
    {
        $picture = new Picture();
        $picture->setGallery($gallery);
        $picture->setImage($url);
        $picture->setTitle($name);
        $this->entityManager->persist($picture);
        $this->entityManager->flush();
    }

    public function findById(int $id): ?Picture
    {
        return $this->pictureRepository->find($id);
    }
}