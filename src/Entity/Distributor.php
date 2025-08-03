<?php

namespace App\Entity;

use App\Core\Trait\DoctrineIdentifierTrait;
use App\Core\ValueObject\LifecycleEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DistributorRepository")
 * @ORM\Table(name="amz_distributors")
 * @ORM\HasLifecycleCallbacks
 */
class Distributor extends LifecycleEntity
{
    /**
     * @return mixed
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * @param mixed $companyName
     */
    public function setCompanyName($companyName): void
    {
        $this->companyName = $companyName;
    }

    /**
     * @return mixed
     */
    public function getCompanyAddress()
    {
        return $this->companyAddress;
    }

    /**
     * @param mixed $companyAddress
     */
    public function setCompanyAddress($companyAddress): void
    {
        $this->companyAddress = $companyAddress;
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
    public function getProducts()
    {
        return implode(',', $this->products);
    }

    public function getProductsArr()
    {
        return  $this->products;
    }

    /**
     * @param mixed $products
     */
    public function setProducts($products): void
    {
        $this->products = array_column(json_decode($products, true),'value');
    }
    use DoctrineIdentifierTrait;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $companyName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $companyAddress;


    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $email;


    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $webLink;

    /**
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private $products;

    /**
     * @return mixed
     */
    public function getWebLink()
    {
        return $this->webLink;
    }

    /**
     * @param mixed $webLink
     */
    public function setWebLink($webLink): void
    {
        $this->webLink = $webLink;
    }
}