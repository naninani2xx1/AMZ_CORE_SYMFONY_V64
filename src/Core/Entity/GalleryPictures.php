<?php

namespace App\Core\Entity;

use App\Core\Trait\DoctrineIdentifierTrait;
use App\Core\Trait\DoctrinePropPictureTrait;
use App\Core\ValueObject\LifecycleEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Core\Repository\GalleryPicturesRepository")
 * @ORM\Table(name="core_gallery_pictures")
 * @ORM\HasLifecycleCallbacks
 */
class GalleryPictures extends LifecycleEntity
{
    use DoctrinePropPictureTrait, DoctrineIdentifierTrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\Core\Entity\Gallery", inversedBy="galleryPicturies")
     * @ORM\JoinColumn(name="gallery_id", referencedColumnName="id", nullable=true)
     */
    private $gallery;

    /**
     * @ORM\ManyToOne(targetEntity="App\Core\Entity\Picture", inversedBy="galleryPicturies")
     * @ORM\JoinColumn(name="picture_id", referencedColumnName="id", nullable=true)
     */
    private $picture;

    /**
     * @return mixed
     */
    public function getGallery()
    {
        return $this->gallery;
    }

    /**
     * @param mixed $gallery
     */
    public function setGallery($gallery): void
    {
        $this->gallery = $gallery;
    }

    public function getPicture(): ?Picture
    {
        return $this->picture;
    }

    public function setPicture(?Picture $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

}