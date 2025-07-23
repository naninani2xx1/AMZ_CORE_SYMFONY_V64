<?php

namespace App\Repository;

use App\Entity\Manufacturer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Manufacturer>
 */
class ManufacturerRepository extends ServiceEntityRepository
{
    const ALIAS='manufacturer';
    private $paginator;
    public function __construct(ManagerRegistry $registry,  PaginatorInterface $paginator)
    {
        parent::__construct($registry, Manufacturer::class);
        $this->paginator = $paginator;
    }

    public function findAllManufacturer(int $page=1 , int $limit=10): \Knp\Component\Pager\Pagination\PaginationInterface|array
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS);
        $queryBuilder->orderBy(self::ALIAS . '.createdAt', 'ASC');
        return $this->paginator->paginate($queryBuilder, $page, $limit);
    }


    //    /**
    //     * @return Manufacturer[] Returns an array of Manufacturer objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Manufacturer
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
