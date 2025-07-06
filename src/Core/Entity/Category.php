<?php

namespace App\Core\Entity;

use App\Core\ValueObject\LifecycleEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Core\Repository\CategoryRepository")
 * @ORM\Table(name="core_category")
 * @ORM\HasLifecycleCallbacks
 */
class Category extends LifecycleEntity
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", name="title", nullable=true)
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity="App\Core\Entity\Post", mappedBy="category")
     */
    private $posts;

    /**
     * @ORM\Column(type="string", nullable=true, unique=true)
     * @Gedmo\Slug(fields={"title"}, updatable=false)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $icon;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $thumbnail;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sortOrder = 1;

    /**
     * @ORM\ManyToOne(targetEntity="App\Core\Entity\Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent", referencedColumnName="id",nullable=true, onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="App\Core\Entity\Category", mappedBy="parent")
     */
    private $children;


    /**
     * @ORM\Column(type="string", length="50" ,nullable=true)
     */
    private $level;

    /**
     * @ORM\Column(type="string", name="custom_path", nullable=true)
     */
    private $customPath;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

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

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(?int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getHot(): ?string
    {
        return $this->hot;
    }

    public function setHot(?string $hot): self
    {
        $this->hot = $hot;

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

    public function addChild(Category $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(Category $child): self
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    public function getSortOrder(): ?int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(?int $sortOrder): static
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    public function getCustomPath(): ?string
    {
        return $this->customPath;
    }

    public function setCustomPath(?string $customPath): static
    {
        $this->customPath = $customPath;

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setCategory($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getCategory() === $this) {
                $post->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }
}
