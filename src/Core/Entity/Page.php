<?php

namespace App\Core\Entity;

use App\Core\ValueObject\LifecycleEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Core\Repository\PageRepository")
 * @ORM\Table(name="core_page")
 * @ORM\HasLifecycleCallbacks
 *
 */
class Page extends LifecycleEntity
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private  $id;

    /**
     * @ORM\Column(type="string", name="name", nullable=true)
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity="App\Core\Entity\Post", inversedBy="page")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id",nullable=true)
     */
    private $post;

    /**
     * @ORM\ManyToOne(targetEntity="App\Core\Entity\Page", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id",nullable=true)
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="App\Core\Entity\Page", mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\Column(type="string", name="type", nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="string", name="seo_url", nullable=true)
     */
    private $seoUrl;

    /**
     * @ORM\Column(type="string", name="css", nullable=true)
     */
    private  $css;

    /**
     * @ORM\Column(type="text", name="custom_css", nullable=true)
     */
    private $customCss;


    public function __construct()
    {
        $this->children = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
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

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getSeoUrl(): ?string
    {
        return $this->seoUrl;
    }

    public function setSeoUrl(?string $seoUrl): static
    {
        $this->seoUrl = $seoUrl;

        return $this;
    }

    public function getCss(): ?string
    {
        return $this->css;
    }

    public function setCss(?string $css): static
    {
        $this->css = $css;

        return $this;
    }

    public function getCustomCss(): ?string
    {
        return $this->customCss;
    }

    public function setCustomCss(?string $customCss): static
    {
        $this->customCss = $customCss;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, Page>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(Page $child): static
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(Page $child): static
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

}