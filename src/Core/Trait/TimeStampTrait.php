<?php

namespace App\Core\Trait;
use Doctrine\ORM\Mapping as ORM;
trait TimeStampTrait
{
    /**
     * @ORM\Column(type="datetime",, nullable="true")
     */
    private ?\DateTime $createdAt = null;

    /**
     * @ORM\Column(type="datetime", nullable="true")
     */
    private ?\DateTime $updatedAt = null;

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime|null $createdAt
     */
    public function setCreatedAt(?\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @param \DateTime|null $updatedAt
     */
    public function setUpdatedAt(?\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}