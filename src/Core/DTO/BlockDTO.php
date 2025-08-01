<?php

namespace App\Core\DTO;

use App\Core\Entity\Block;

final class BlockDTO
{
    private ?string $title;
    private ?int $sortOrder;
    private ?string $background;
    public function __construct(?string $title, ?int $sortOrder, ?string $background)
    {
        $this->background = $background;
        $this->sortOrder = $sortOrder;
        $this->title = ucwords($title);
    }

    /**
     * @return string|null
     */
    public function getBackground(): ?string
    {
        return $this->background;
    }

    /**
     * @return int|null
     */
    public function getSortOrder(): ?int
    {
        return $this->sortOrder;
    }

    /**
     * @param int|null $sortOrder
     */
    public function setSortOrder(?int $sortOrder): void
    {
        $this->sortOrder = $sortOrder;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }


    public function run(Block $entity): void
    {
        $props = get_object_vars($this);

        foreach ($props as $prop => $value) {
            $methodGetter =  'get' . ucfirst($prop);
            if (method_exists($entity, $methodGetter) && !empty($value)) {
                $methodSetter =  'set' . ucfirst($prop);
                call_user_func([$entity, $methodSetter], $value);
            }
        }
    }
}