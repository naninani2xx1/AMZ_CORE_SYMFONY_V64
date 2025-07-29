<?php

namespace App\Core\Repository;

use App\Core\DataType\ArchivedDataType;
use App\Core\DataType\MenuDataType;
use App\Core\Entity\Menu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Menu>
 */
class MenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menu::class);
    }

    public function findOneByPosition(string $position): ?Menu
    {
        $qb = $this->createQueryBuilder('menu');
        $qb->where('menu.position = :position')->setParameter('position', $position);
        $qb->andWhere('menu.isArchived = :isArchived')->setParameter('isArchived', ArchivedDataType::UN_ARCHIVED);
        $qb->andWhere('menu.isRoot = :root')->setParameter('root', MenuDataType::ROOT_LEVEL);
        $qb->andWhere('menu.parent is null');
        return $qb->getQuery()
            ->setHint(Query::HINT_READ_ONLY, true)
            ->setMaxResults(1)
            ->setFirstResult(0)->getOneOrNullResult();
    }

    public function findMenusByParent(Menu $parent): ?array
    {
        $qb = $this->createQueryBuilder('menu');
        $qb->where('menu.isArchived = :isArchived')->setParameter('isArchived', ArchivedDataType::UN_ARCHIVED);
        $qb->andWhere('menu.isRoot = :root')->setParameter('root', MenuDataType::SUB_LEVEL);
        $qb->andWhere('menu.parent = :parent')->setParameter('parent', $parent);
        return $qb->getQuery()->setHint(Query::HINT_READ_ONLY, true)->getResult();
    }
    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
