<?php

namespace App\Core\DTO;

use App\Core\Entity\Block;

final class BlockDTO
{
    private ?string $title;
    private ?string $subTitle;
    private ?string $description;
    private ?string $content;
    private ?int $sortOrder;
    private ?string $background;
    public function __construct(?string $title, ?int $sortOrder, ?string $background, ?string $subTitle, ?string $description,
        ?string $content
    )
    {
        $this->content = $content;
        $this->subTitle = $subTitle;
        $this->description = trim($description);
        $this->background = $background;
        $this->sortOrder = $sortOrder;
        $this->title = ucwords($title);
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
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
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     * @param Block $entity
     */
    public function setContent(?string $content, Block $entity): void
    {
        $data = json_decode($content, true);
        $content = json_decode($entity->getContent(), true);
        foreach ($data as $key => $val) {
            $content[$key] = trim($val);
        }

        $entity->setContent(json_encode($content));
    }

    /**
     * @return string|null
     */
    public function getSubTitle(): ?string
    {
        return $this->subTitle;
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
                if(method_exists($this, $methodSetter))
                    call_user_func([$this, $methodSetter], $value, $entity);
                else
                    call_user_func([$entity, $methodSetter], $value);
            }
        }
    }
}