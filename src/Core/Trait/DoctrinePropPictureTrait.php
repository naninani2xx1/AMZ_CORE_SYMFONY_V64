<?php

namespace App\Core\Trait;

use Doctrine\ORM\Mapping as ORM;

trait DoctrinePropPictureTrait
{
    /**
     * @ORM\Column(type="text")
     */
    private $image;

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image): void
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getImageMobile()
    {
        return $this->imageMobile;
    }

    /**
     * @param mixed $imageMobile
     */
    public function setImageMobile($imageMobile): void
    {
        $this->imageMobile = $imageMobile;
    }

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param mixed $link
     */
    public function setLink($link): void
    {
        $this->link = $link;
    }

    /**
     * @return mixed
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * @param mixed $sortOrder
     */
    public function setSortOrder($sortOrder): void
    {
        $this->sortOrder = $sortOrder;
    }

    /**
     * @ORM\Column(type="text",name="image_mobile", nullable=true)
     */
    private $imageMobile;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $link;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sortOrder;

}