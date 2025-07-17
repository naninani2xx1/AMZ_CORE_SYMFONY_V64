<?php

namespace App\Core\Entity;

use App\Core\DataType\MenuType;
use App\Core\ValueObject\LifecycleEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Core\Repository\MenuRepository")
 * @ORM\Table(name="core_menu")
 * @ORM\HasLifecycleCallbacks
 */
class Menu extends LifecycleEntity
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $position;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $icon;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sortOrder;

    /**
     * @ORM\ManyToOne(targetEntity="App\Core\Entity\Menu", inversedBy="children")
     * @ORM\JoinColumn(name="parent", referencedColumnName="id",nullable=true, onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="App\Core\Entity\Menu", mappedBy="parent" )
     */
    private $children;


    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $language;


    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }



    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(?string $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * @param mixed $sortOrder
     */
    public function setSortOrder($sortOrder): void
    {
        $this->sortOrder = $sortOrder;
    }


    /**
     * @return Collection<int, Menu>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(Menu $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(Menu $child): self
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): self
    {
        $this->language = $language;

        return $this;
    }

}