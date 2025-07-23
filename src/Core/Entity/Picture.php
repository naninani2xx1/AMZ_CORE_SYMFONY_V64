<?php

namespace App\Core\Entity;

use App\Core\Trait\DoctrineIdentifierTrait;
use App\Core\Trait\DoctrineTitleSubtitleTrait;
use App\Core\ValueObject\LifecycleEntity;
use Doctrine\DBAL\Types\Types;
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
    use DoctrineTitleSubtitleTrait, DoctrineIdentifierTrait;

    /**
     * @ORM\Column(type="text")
     */
    private $image;

    /**
     * @ORM\Column(type="text",name="image_mobile", nullable=true)
     */
    private $imageMobile;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $link;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sortOrder;

    /**
     * @ORM\ManyToOne(targetEntity="App\Core\Entity\Gallery", inversedBy="picturies")
     * @ORM\JoinColumn(name="gallery_id", referencedColumnName="id", nullable=true)
     */
    private $gallery;

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
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


}