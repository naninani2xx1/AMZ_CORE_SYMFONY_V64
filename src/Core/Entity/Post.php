<?php

namespace App\Core\Entity;


use App\Core\DataType\ArchivedDataType;
use App\Core\DataType\PostStatusType;
use App\Core\Trait\DoctrineContentTrait;
use App\Core\Trait\DoctrineDescriptionTrait;
use App\Core\Trait\DoctrineIdentifierTrait;
use App\Core\Trait\DoctrineThumbnailTrait;
use App\Core\Trait\DoctrineTitleSubtitleTrait;
use App\Core\Trait\PreRemoveCycleTrait;
use App\Core\ValueObject\LifecycleEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Core\Repository\PostRepository")
 * @ORM\Table(name="core_post")
 * @ORM\HasLifecycleCallbacks
 */
class Post extends LifecycleEntity
{
    use DoctrineTitleSubtitleTrait, DoctrineThumbnailTrait,
        DoctrineDescriptionTrait, DoctrineContentTrait,  DoctrineIdentifierTrait;

    /**
     * @ORM\OneToOne(targetEntity="App\Core\Entity\Article", mappedBy="post")
     */
    private ?Article $article;

    /**
     * @ORM\OneToOne(targetEntity="App\Core\Entity\SocialSharing", mappedBy="post", cascade={"persist", "remove"})
     */
    private ?SocialSharing $socialSharing;

    /**
     * @ORM\OneToOne(targetEntity="App\Core\Entity\Page", mappedBy="post",cascade={"persist"}  )
     */
    private $page;

    /**
     * @ORM\ManyToOne (targetEntity="App\Core\Entity\Category", inversedBy="posts" )
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id",nullable=true)
     */
    private $category;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Slug(fields={"title"}, updatable=false)
     */

    private $slug;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sortOrder = 1;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $isHot = PostStatusType::HOT_TYPE_NORMAL;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $isNew = PostStatusType::NEW_TYPE_NORMAL;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $postType;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $published = PostStatusType::PUBLISH_TYPE_PUBLISHED;

    /**
     * @ORM\OneToOne(targetEntity="App\Core\Entity\Gallery",inversedBy ="post")
     * @ORM\JoinColumn(name="gallery_id", referencedColumnName="id",nullable=true)
     */
    private $gallery;

    /**
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity="App\Core\Entity\Block", mappedBy="post", cascade={"persist"})
     * @ORM\OrderBy({"sortOrder" = "ASC"})
     */
    private $blocks;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private string $config;

    public function __construct()
    {
        $this->blocks = new ArrayCollection();
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

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
    public function getIsHot()
    {
        return $this->isHot;
    }

    public function setIsHot(?int $isHot): self
    {
        $this->isHot = $isHot;

        return $this;
    }

    public function getIsNew()
    {
        return $this->isNew;
    }

    public function setIsNew(?int $isNew): self
    {
        $this->isNew = $isNew;

        return $this;
    }

    public function getPostType()
    {
        return $this->postType;
    }

    public function setPostType(?string $postType): self
    {
        $this->postType = $postType;

        return $this;
    }

    public function getPublished(): int
    {
        return $this->published;
    }

    public function setPublished(?string $published): self
    {
        $this->published = $published;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags): void
    {
        $this->tags = $tags;
    }

    public function getArrTags(): string
    {
        return is_null($this->tags) ? '' : implode(',', ($this->tags));
    }

    /**
     * @param mixed $tags
     */
    public function setArrTags(?string $tags): void
    {
        if(is_null($tags))
            return;
        $arr = json_decode($tags, true);

        $this->tags = array_column($arr, 'value');
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

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): static
    {
        // unset the owning side of the relation if necessary
        if ($article === null && $this->article !== null) {
            $this->article->setPost(null);
        }

        // set the owning side of the relation if necessary
        if ($article !== null && $article->getPost() !== $this) {
            $article->setPost($this);
        }

        $this->article = $article;

        return $this;
    }

    public function getSocialSharing(): ?SocialSharing
    {
        return $this->socialSharing;
    }

    public function setSocialSharing(?SocialSharing $socialSharing): static
    {
        // unset the owning side of the relation if necessary
        if ($socialSharing === null && $this->socialSharing !== null) {
            $this->socialSharing->setPost(null);
        }

        // set the owning side of the relation if necessary
        if ($socialSharing !== null && $socialSharing->getPost() !== $this) {
            $socialSharing->setPost($this);
        }

        $this->socialSharing = $socialSharing;

        return $this;
    }

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function setPage(?Page $page): static
    {
        // unset the owning side of the relation if necessary
        if ($page === null && $this->page !== null) {
            $this->page->setPost(null);
        }

        // set the owning side of the relation if necessary
        if ($page !== null && $page->getPost() !== $this) {
            $page->setPost($this);
        }

        $this->page = $page;

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
     * @return Collection<int, Block>
     */
    public function getBlocks(): Collection
    {
        $criteria = Criteria::create();
        $criteria->andWhere(Criteria::expr()->eq('isArchived', ArchivedDataType::UN_ARCHIVED));
        return $this->blocks->matching($criteria);
    }

    public function addBlock(Block $block): static
    {
        if (!$this->blocks->contains($block)) {
            $this->blocks->add($block);
            $block->setPost($this);
        }

        return $this;
    }

    public function removeBlock(Block $block): static
    {
        if ($this->blocks->removeElement($block)) {
            // set the owning side to null (unless already changed)
            if ($block->getPost() === $this) {
                $block->setPost(null);
            }
        }

        return $this;
    }
}
