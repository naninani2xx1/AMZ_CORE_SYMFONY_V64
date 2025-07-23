<?php

namespace App\Core\Trait;

use Doctrine\ORM\Mapping as ORM;

trait DoctrineContentTrait
{
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }
}