<?php

namespace App\Core\Repository;


use App\Core\Entity\Gallery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Gallery>
 */
class GalleryRepository extends ServiceEntityRepository
{
    const ALIAS = 'gallery';
    private $paginator;
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Gallery::class);
        $this->paginator = $paginator;
    }

    /**
     * @param $page
     * @param $limit
     *
     * */

    public function findGallery(int $page=10 , int $limit=10): \Knp\Component\Pager\Pagination\PaginationInterface|array
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS);
        $queryBuilder->orderBy(self::ALIAS . '.createdAt', 'ASC');

        return $this->paginator->paginate($queryBuilder, $page, $limit);
    }
    public function findGalleriesWithoutPost(): array
    {
        return $this->createQueryBuilder('g')
            ->leftJoin('App\Core\Entity\Post', 'p', 'WITH', 'p.gallery = g')
            ->where('p.id IS NULL')
            ->getQuery()
            ->getResult();
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
