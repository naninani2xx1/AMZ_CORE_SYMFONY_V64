<?php

namespace App\Core\Entity;

use App\Core\DataType\TicketRequestDataType;
use App\Core\Trait\DoctrineDescriptionTrait;
use App\Core\Trait\DoctrineIdentifierTrait;
use App\Core\ValueObject\LifecycleEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Core\Repository\TicketRequestRepository")
 * @ORM\Table(name="core_ticket_request")
 * @ORM\HasLifecycleCallbacks
 *
 */
class TicketRequest extends LifecycleEntity
{
    use DoctrineIdentifierTrait, DoctrineDescriptionTrait;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $fullname;

    /**
     * @return mixed
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * @param mixed $fullname
     */
    public function setFullname($fullname): void
    {
        $this->fullname = $fullname;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $status = TicketRequestDataType::STATUS_NEW_TICKET_REQUEST;
    /**
     * @ORM\Column (type="string")
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
