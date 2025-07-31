<?php

namespace App\Core\Entity;
use App\Core\Trait\DoctrineIdentifierTrait;
use App\Core\ValueObject\LifecycleEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Core\Repository\ArticleRepository")
 * @ORM\Table(name="core_article")
 * @ORM\HasLifecycleCallbacks
 */
class Article extends LifecycleEntity
{
    use DoctrineIdentifierTrait;


    /**
     * @ORM\ManyToOne(targetEntity="App\Core\Entity\User", inversedBy="articles")
     */
    private $author = null;

    /**
     * @ORM\OneToOne(targetEntity="App\Core\Entity\Post", inversedBy="article", cascade={"persist", "remove"})
     */
    private $post;

    /**
     * @param User|null $author
     */
    public function setAuthor(?User $author): void
    {
        $this->author = $author;
    }

    /**
     * @return User|null
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @return Post|null
     */
    public function getPost(): ?Post
    {
        return $this->post;
    }

    /**
     * @param Post|null $post
     */
    public function setPost(?Post $post): void
    {
        $this->post = $post;
    }
}