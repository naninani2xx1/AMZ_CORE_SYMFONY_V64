<?php

namespace App\Services\Admin;

use App\Core\Entity\Gallery;
use App\Core\Entity\GalleryPictures;
use App\Core\Entity\Picture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageService
{
    public function __construct(
        private readonly ParameterBagInterface $parameterBag,
        private readonly EntityManagerInterface $em
    ) {}

    public function uploadImage(UploadedFile $file, string $relativePath, ?string $customName    = null): string
    {
        $projectDir = $this->parameterBag->get('kernel.project_dir');
        $uploadPath = $projectDir . '/public/' . trim($relativePath, '/');

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $fileName = $customName ?? uniqid() . '.' . $file->guessExtension();
        $file->move($uploadPath, $fileName);

        return '/' . trim($relativePath, '/') . '/' . $fileName;
    }

    public function saveImageToGallery(string $imagePath, string $title, Gallery $gallery): void
    {
        if (empty($imagePath)) {
            throw new \InvalidArgumentException('Image path cannot be empty.');
        }

        $picture = new Picture();
        $picture->setImage($imagePath);
        $picture->setTitle($title);
        $this->em->persist($picture);

        $galleryPicture = new GalleryPictures();
        $galleryPicture->setGallery($gallery);
        $galleryPicture->setPicture($picture);
        $galleryPicture->setImage('null');

        $this->em->persist($galleryPicture);
        $this->em->flush();
    }
}
