<?php

namespace App\Core\ValueObject;

use App\Core\Trait\TimeStampTrait;
use Doctrine\ORM\Mapping as ORM;

abstract class BaseLifecycleEntity
{
    use TimeStampTrait;

    /**
     * @ORM\PrePersist
     */
    public function prePersist(): void
    {
        $now = new \DateTime();
        $this->setCreatedAt($now);
        $this->setUpdatedAt($now);
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate(): void
    {
        $now = new \DateTime();
        $this->setUpdatedAt($now);
    }
}