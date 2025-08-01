<?php

namespace App\Core\Trait;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

trait PreRemoveCycleTrait
{
    /**
     * @ORM\PreRemove
     */
    public function preRemove(): void
    {

    }
}