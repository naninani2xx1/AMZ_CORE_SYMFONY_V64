<?php

namespace App\Core\Trait;

use Doctrine\ORM\Mapping as ORM;

trait DoctrineThumbnailTrait
{
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $thumbnail;


    /**
     * @return mixed
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * @param mixed $thumbnail
     */
    public function setThumbnail($thumbnail): void
    {
        $this->thumbnail = $thumbnail;
    }
}