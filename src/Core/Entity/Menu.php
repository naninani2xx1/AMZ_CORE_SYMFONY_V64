<?php

namespace App\Core\Entity;

use App\Core\DataType\MenuDataType;
use App\Core\Trait\DoctrineIdentifierTrait;
use App\Core\ValueObject\LifecycleEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Core\Repository\MenuRepository")
 * @ORM\Table(name="core_menu")
 * @ORM\HasLifecycleCallbacks
 */
class Menu extends LifecycleEntity
{
    use DoctrineIdentifierTrait;

    /**
     * @ORM\Column(type="string", name="name", nullable=true)
     */
    private $name;


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
     * @ORM\Column(type="string", nullable=true)
     */
    private $display = MenuDataType::DISPLAY_SHOW;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $isRoot = MenuDataType::ROOT_LEVEL;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sortOrder = MenuDataType::MENU_DEFAULT_SORT;


    /**
     * @ORM\ManyToOne(targetEntity="App\Core\Entity\Menu", inversedBy="children")
     * @ORM\JoinColumn(name="parent", referencedColumnName="id",nullable=true, onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="App\Core\Entity\Menu", mappedBy="parent",cascade={"remove"})
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="App\Core\Entity\User", inversedBy="menus")
     */
    private $author;


    public function __construct()
    {
        $this->children = new ArrayCollection();
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

    public function getDisplay(): ?string
    {
        return $this->display;
    }

    public function setDisplay(?string $display): self
    {
        $this->display = $display;

        return $this;
    }

    public function getSortOrder(): ?int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(?int $sortOrder): self
    {
        $this->sortOrder = $sortOrder;

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
     * @return Collection<int, Menu>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChildren(Menu $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChildren(Menu $child): self
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

    public function getIsRoot(): ?string
    {
        return $this->isRoot;
    }

    public function setIsRoot(?string $isRoot): self
    {
        $this->isRoot = $isRoot;

        return $this;
    }
}