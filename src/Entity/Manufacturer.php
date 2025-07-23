<?php

namespace App\Entity;

use App\Core\Trait\DoctrineIdentifierTrait;
use App\Core\ValueObject\LifecycleEntity;
use App\Repository\ManufacturerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ManufacturerRepository")
 * @ORM\Table(name="amz_manufacturer")
 * @ORM\HasLifecycleCallbacks
 */
class Manufacturer extends LifecycleEntity
{
    use DoctrineIdentifierTrait;

    /**
     * @ORM\Column(nullable=true,type="string")
     */
    private $name;
    /**
     * @ORM\Column(nullable=true,type="string")
     */
    private $phone;
    /**
     * @ORM\Column(nullable=true,type="string")
     */
    private $email;
    /**
     * @ORM\Column(nullable=true,type="string")
     */
    private $address;
    /**
     * @ORM\Column(nullable=true,type="simple_array")
     */
    private $products;

    /**
     * @ORM\Column(nullable=true,type="string")
     */
    private $urlWeb;

    /**
     * @ORM\Column(nullable=true,type="integer")
     */
    private $sortOrder;

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

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address): void
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getProducts(): ?array
    {
        return $this->products;
    }
    /**
     * @param mixed $products
     */
    public function setProducts($products): void
    {
        $this->products = $products;
    }

    /**
     * @return mixed
     */
    public function getUrlWeb()
    {
        return $this->urlWeb;
    }

    /**
     * @param mixed $urlWeb
     */
    public function setUrlWeb($urlWeb): void
    {
        $this->urlWeb = $urlWeb;
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

}
