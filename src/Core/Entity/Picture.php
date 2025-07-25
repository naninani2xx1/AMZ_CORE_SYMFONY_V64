<?php

namespace App\Core\Entity;

use App\Core\Trait\DoctrineIdentifierTrait;
use App\Core\Trait\DoctrinePropPictureTrait;
use App\Core\Trait\DoctrineTitleSubtitleTrait;
use App\Core\ValueObject\LifecycleEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Core\Repository\PictureRepository")
 * @ORM\Table(name="core_picture")
 * @ORM\HasLifecycleCallbacks
 *
 */
class Picture extends LifecycleEntity
{
    use DoctrineTitleSubtitleTrait, DoctrineIdentifierTrait, DoctrinePropPictureTrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\Core\Entity\Gallery", inversedBy="picturies")
     * @ORM\JoinColumn(name="gallery_id", referencedColumnName="id", nullable=true)
     */
    private $gallery;

    /**
     * @ORM\OneToMany(targetEntity="App\Core\Entity\GalleryPictures", mappedBy="picture")
     */
    private $galleryPictures;

    public function __construct()
    {
        $this->galleryPictures = new ArrayCollection();
    }

    public function getSortOrder(): ?int
    {
        return $this->sortOrder;
    }

    public function getImageMobile():?string
    {
        return $this->imageMobile;
    }

    public function setImageMobile($imageMobile): self
    {
        $this->imageMobile = $imageMobile;

        return $this;
    }

    public function setSortOrder(?int $sortOrder): self
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    public function getGallery(): ?Gallery
    {
        return $this->gallery;
    }

    public function setGallery(?Gallery $gallery): static
    {
        $this->gallery = $gallery;

        return $this;
    }

    /**
     * @return Collection<int, GalleryPictures>
     */
    public function getGalleryPictures(): Collection
    {
        return $this->galleryPictures;
    }

    public function addGalleryPicture(GalleryPictures $galleryPicture): static
    {
        if (!$this->galleryPictures->contains($galleryPicture)) {
            $this->galleryPictures->add($galleryPicture);
            $galleryPicture->setPicture($this);
        }

        return $this;
    }

    public function removeGalleryPicture(GalleryPictures $galleryPicture): static
    {
        if ($this->galleryPictures->removeElement($galleryPicture)) {
            // set the owning side to null (unless already changed)
            if ($galleryPicture->getPicture() === $this) {
                $galleryPicture->setPicture(null);
            }
        }

        return $this;
    }

}