<?php

namespace App\Core\Repository;

use App\Core\DataType\ArchivedDataType;
use App\Core\DataType\MenuDataType;
use App\Core\Entity\Menu;
use App\Core\Entity\Qa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Qa>
 */
class QaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Qa::class);
    }

}
