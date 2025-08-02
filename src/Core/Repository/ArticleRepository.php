<?php

namespace App\Core\Repository;

use App\Core\Entity\Article;
use App\Core\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository
{
    const ALIAS='article';
    private $paginator;
    public function __construct(ManagerRegistry $registry,  PaginatorInterface $paginator)
    {
        parent::__construct($registry, Article::class);
        $this->paginator = $paginator;
    }

    /***
     * @param int $page
     * @param int $limit
     */
    public function findAllArticle(int $page=1 , int $limit=10): \Knp\Component\Pager\Pagination\PaginationInterface|array
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS);
        $queryBuilder->orderBy(self::ALIAS . '.createdAt', 'ASC');
        return $this->paginator->paginate($queryBuilder, $page, $limit);
    }
    public function findRelateArticle(int $id,EntityManagerInterface $em)
    {
        $currentArticle = $em->getRepository(Article::class)->find($id);
        $post = $currentArticle->getPost();
        $postType = $post->getPostType();
        $qb = $em->createQueryBuilder()
            ->select('a')
            ->from(Article::class, 'a')
            ->join('a.post', 'p')
            ->where('p.postType = :postType')
            ->andWhere('a.id != :currentId')
            ->setParameter('postType', $postType)
            ->setParameter('currentId', $id)
            ->setMaxResults(3);

        return $qb->getQuery()->getResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findBySlug(string $slug, int $id): ?Article
    {
        return $this->createQueryBuilder('a')
            ->join('a.post', 'p')
            ->andWhere('p.slug = :slug')
            ->andWhere('a.id = :id')
            ->setParameters([
                'slug' => $slug,
                'id' => $id
            ])
            ->getQuery()
            ->getOneOrNullResult();
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
