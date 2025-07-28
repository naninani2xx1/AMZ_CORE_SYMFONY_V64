<?php

namespace App\Entity;

use App\Core\Trait\DoctrineIdentifierTrait;
use App\Core\ValueObject\LifecycleEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TopicContactRepository")
 * @ORM\Table(name="amz_topic_contact")
 * @ORM\HasLifecycleCallbacks
 */
class TopicContact extends LifecycleEntity
{
    use DoctrineIdentifierTrait;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column (type="string")
     */
    private $type;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    public function __toString(){
        return $this->name;
    }

}
