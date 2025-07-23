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

    public function __construct()
    {
        $this->picturies = new ArrayCollection();
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
}