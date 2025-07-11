<?php

namespace App\Core\Entity;


use App\Core\ValueObject\LifecycleEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\LegacyPasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @UniqueEntity("username")
 * @ORM\Entity(repositoryClass="App\Core\Repository\UserRepository")
 * @ORM\Table(name="core_user")
 * @ORM\HasLifecycleCallbacks
 */
class User extends LifecycleEntity implements UserInterface , LegacyPasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=100, unique=true, nullable="true")
     */
    private ?string $username = null;

    /**
     * @ORM\Column(type="string", length=255, nullable="true")
     */
    private ?string $password = null;

    /**
     * @ORM\Column(type="text", nullable="true")
     */
    private ?string $salt = null;

    /**
     * @ORM\Column(type="simple_array", nullable="true")
     */
    private ?array $roles = null;

    /**
     * @ORM\OneToMany(targetEntity="App\Core\Entity\Article", mappedBy="author")
     */
    private ?Collection $articles = null;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->salt = $this->generateSalt();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt(): ?string
    {
        return $this->salt;
    }

    public function setSalt(string $salt): self
    {
        $this->salt = $salt;
        return $this;
    }
    private function generateSalt(): string
    {
        return md5(time());
    }
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    /**
     * @return Collection|null
     */
    public function getArticles(): ?Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if(!$this->articles->contains($article)) {
            $this->articles->add($article);
        }
        return $this;
    }

    public function removeArticle(Article $article): static
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getAuthor() === $this) {
                $article->setAuthor(null);
            }
        }

        return $this;
    }
}
