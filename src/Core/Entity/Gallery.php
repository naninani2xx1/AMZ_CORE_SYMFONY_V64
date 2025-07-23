<?php

namespace App\Core\Entity;

use App\Core\Trait\DoctrineIdentifierTrait;
use App\Core\ValueObject\LifecycleEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Core\Repository\GalleryRepository")
 * @ORM\Table(name="core_gallery")
 * @ORM\HasLifecycleCallbacks
 *
 */
class Gallery extends LifecycleEntity
{
    use DoctrineIdentifierTrait;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Core\Entity\Picture", mappedBy="gallery")
     */
    private $picturies;

    /**
     * @ORM\OneToOne(targetEntity="App\Core\Entity\Post",mappedBy="gallery")
     */
    private $post;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="App\Core\Entity\GalleryPictures", mappedBy="gallery")
     */
    private $galleryPictures;

    public function __construct()
    {
        $this->picturies = new ArrayCollection();
        $this->galleryPictures = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Picture>
     */
    public function getPicturies(): Collection
    {
        return $this->picturies;
    }

    public function addPictury(Picture $pictury): static
    {
        if (!$this->picturies->contains($pictury)) {
            $this->picturies->add($pictury);
            $pictury->setGallery($this);
        }

        return $this;
    }

    public function removePictury(Picture $pictury): static
    {
        if ($this->picturies->removeElement($pictury)) {
            // set the owning side to null (unless already changed)
            if ($pictury->getGallery() === $this) {
                $pictury->setGallery(null);
            }
        }

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): static
    {
        // unset the owning side of the relation if necessary
        if ($post === null && $this->post !== null) {
            $this->post->setGallery(null);
        }

        // set the owning side of the relation if necessary
        if ($post !== null && $post->getGallery() !== $this) {
            $post->setGallery($this);
        }

        $this->post = $post;

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
            $galleryPicture->setGallery($this);
        }

        return $this;
    }

    public function removeGalleryPicture(GalleryPictures $galleryPicture): static
    {
        if ($this->galleryPictures->removeElement($galleryPicture)) {
            // set the owning side to null (unless already changed)
            if ($galleryPicture->getGallery() === $this) {
                $galleryPicture->setGallery(null);
            }
        }

        return $this;
    }

    public function countPictures(): int
    {
        return $this->picturies->count();
    }
}