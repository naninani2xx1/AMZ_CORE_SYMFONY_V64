<?php

namespace App\Core\ValueObject;

use App\Core\DataType\ArchivedDataType;
use App\Core\Trait\ArchivedTrait;
use App\Core\Trait\TimeStampTrait;
use Doctrine\ORM\Mapping as ORM;

abstract class LifecycleEntity
{
    use TimeStampTrait, ArchivedTrait;

    /**
     * @ORM\PrePersist
     */
    public function prePersist(): void
    {
        $now = new \DateTime();
        $this->setCreatedAt($now);
        $this->setUpdatedAt($now);
        $this->setArchived(ArchivedDataType::UN_ARCHIVED);
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