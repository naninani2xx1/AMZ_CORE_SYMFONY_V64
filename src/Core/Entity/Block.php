<?php


namespace App\Core\Entity;

use App\Core\DataType\BlockType;
use App\Core\Trait\ArchivedTrait;
use App\Core\Trait\DoctrineContentTrait;
use App\Core\Trait\DoctrineDescriptionTrait;
use App\Core\Trait\DoctrineIdentifierTrait;
use App\Core\Trait\DoctrineThumbnailTrait;
use App\Core\Trait\DoctrineTitleSubtitleTrait;
use App\Core\ValueObject\LifecycleEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Core\Repository\BlockRepository")
 * @ORM\Table(name="core_block_content")
 * @ORM\HasLifecycleCallbacks
 *
 */
class Block extends LifecycleEntity
{
    use DoctrineTitleSubtitleTrait, DoctrineDescriptionTrait, DoctrineContentTrait, DoctrineThumbnailTrait, DoctrineIdentifierTrait;


    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sortOrder;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $config;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Core\Entity\Post", inversedBy="blocks")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id",nullable=true)
     */
    private $post;

    /**
     * @ORM\ManyToOne(targetEntity="App\Core\Entity\Category", inversedBy="block" )
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id",nullable=true)
     */
    private $category;

    /**
     * @ORM\Column(name="slug", type="string", unique=true, nullable=true)
     * @Gedmo\Slug(fields={"title"}, updatable=false)
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $imageIcon;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $imageMobile;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $background;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $mobileBackground;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $textIcon;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $location;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $videoUrl;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $kind = BlockType::BLOCK_KIND_DYNAMIC;

    public function getSortOrder(): ?int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(?int $sortOrder): static
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    public function getConfig(): ?string
    {
        return $this->config;
    }

    public function setConfig(?string $config): static
    {
        $this->config = $config;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getImageIcon(): ?string
    {
        return $this->imageIcon;
    }

    public function setImageIcon(?string $imageIcon): static
    {
        $this->imageIcon = $imageIcon;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getImageMobile(): ?string
    {
        return $this->imageMobile;
    }

    public function setImageMobile(?string $imageMobile): static
    {
        $this->imageMobile = $imageMobile;

        return $this;
    }

    public function getBackground(): ?string
    {
        return $this->background;
    }

    public function setBackground(?string $background): static
    {
        $this->background = $background;

        return $this;
    }

    public function getMobileBackground(): ?string
    {
        return $this->mobileBackground;
    }

    public function setMobileBackground(?string $mobileBackground): static
    {
        $this->mobileBackground = $mobileBackground;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getTextIcon(): ?string
    {
        return $this->textIcon;
    }

    public function setTextIcon(?string $textIcon): static
    {
        $this->textIcon = $textIcon;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getVideoUrl(): ?string
    {
        return $this->videoUrl;
    }

    public function setVideoUrl(?string $videoUrl): static
    {
        $this->videoUrl = $videoUrl;

        return $this;
    }

    public function getKind(): ?string
    {
        return $this->kind;
    }

    public function setKind(?string $kind): static
    {
        $this->kind = $kind;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): static
    {
        $this->post = $post;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }
}