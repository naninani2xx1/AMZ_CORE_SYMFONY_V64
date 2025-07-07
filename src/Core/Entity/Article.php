<?php

namespace App\Core\Entity;
use App\Core\ValueObject\LifecycleEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Core\Repository\ArticleRepository")
 * @ORM\Table(name="core_article")
 * @ORM\HasLifecycleCallbacks
 */
class Article extends LifecycleEntity
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private ?int $id = null;


    /**
     * @ORM\ManyToOne(targetEntity="App\Core\Entity\User", inversedBy="articles")
     */
    private $author = null;

    /**
     * @ORM\OneToOne(targetEntity="App\Core\Entity\Post", inversedBy="article")
     */
    private $post;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

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