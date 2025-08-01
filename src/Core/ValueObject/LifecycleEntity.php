<?php

namespace App\Core\ValueObject;

use App\Core\DataType\ArchivedDataType;
use App\Core\Trait\ArchivedTrait;
use App\Core\Trait\PreRemoveCycleTrait;
use App\Core\Trait\TimeStampTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\MappedSuperclass
 */
abstract class LifecycleEntity
{
    use TimeStampTrait, ArchivedTrait,  PreRemoveCycleTrait;

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

        if(method_exists($this, 'getSlug') && method_exists($this, 'getIsArchived')
            && $this->getIsArchived())
            $this->setSlug($this->getSlug(). '-'.Uuid::v4()->toBase32());
    }
}