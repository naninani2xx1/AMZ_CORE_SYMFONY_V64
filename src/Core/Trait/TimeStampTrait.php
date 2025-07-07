<?php

namespace App\Core\Trait;
use Doctrine\ORM\Mapping as ORM;

trait TimeStampTrait
{
    /**
     * @ORM\Column(type="datetime", nullable="true")
     */
    protected ?\DateTime $createdAt = null;

    /**
     * @ORM\Column(type="datetime", nullable="true")
     */
    protected ?\DateTime $updatedAt = null;

    /**
     * @return \DateTime|null
     */
    protected function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime|null
     */
    protected function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime|null $createdAt
     */
    protected function setCreatedAt(?\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @param \DateTime|null $updatedAt
     */
    protected function setUpdatedAt(?\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}