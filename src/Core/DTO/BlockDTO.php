<?php

namespace App\Core\DTO;

use App\Core\Entity\Block;

final class BlockDTO
{
    private ?string $title;
    public function __construct(?string $title)
    {
        $this->title = ucwords($title);
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
            if (method_exists($entity, $methodGetter) && !is_null($value)) {
                $methodSetter =  'set' . ucfirst($prop);
                call_user_func([$entity, $methodSetter], $value);
            }
        }
    }
}