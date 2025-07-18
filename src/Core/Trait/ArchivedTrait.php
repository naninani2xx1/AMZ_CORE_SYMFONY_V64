<?php

namespace App\Core\Trait;
use App\Core\DataType\ArchivedDataType;
use Doctrine\ORM\Mapping as ORM;

trait ArchivedTrait
{
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected ?bool $isArchived = ArchivedDataType::UN_ARCHIVED;

    public function isArchived(): bool
    {
        return $this->isArchived;
    }

    public function setArchived(bool $isArchived): void
    {
        $this->isArchived = $isArchived;
    }
}