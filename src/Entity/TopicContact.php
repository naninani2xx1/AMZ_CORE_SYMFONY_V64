<?php


namespace App\Entity;

use App\Core\Trait\DoctrineIdentifierTrait;
use App\Core\ValueObject\LifecycleEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TopicContactRepository")
 * @ORM\Table(name="amz_topic_contact")
 * @ORM\HasLifecycleCallbacks
 *
 */
class TopicContact extends  LifecycleEntity
{
    use DoctrineIdentifierTrait;

    /**
     * @ORM\Column(type="string")
     */
    private $topic;

    /**
     * @return mixed
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * @param mixed $topic
     */
    public function setTopic($topic): void
    {
        $this->topic = $topic;
    }

}