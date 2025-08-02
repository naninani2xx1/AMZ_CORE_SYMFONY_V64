<?php

namespace App\Core\Repository;

use App\Core\DataType\ArchivedDataType;
use App\Core\DataType\CategoryDataType;
use App\Core\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    const ALIAS = 'category';
    private PaginatorInterface $paginator;
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator, EntityManagerInterface $entityManager)
    {
        $this->paginator = $paginator;
        $this->entityManager = $entityManager;
        parent::__construct($registry, Category::class);
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * Lấy tất cả category với phân trang
     * @param $page
     * @param $limit
     * @return PaginationInterface
     */
    public function findAllPaginated($page = 1, $limit = 10): PaginationInterface
    {
        $queryBuilder = $this->createQueryBuilder('category');
        $expr = $queryBuilder->expr();
        $queryBuilder->where(
            $expr->eq(self::ALIAS . '.isArchived', $expr->literal(ArchivedDataType::UN_ARCHIVED)),
        )
            ->orderBy(self::ALIAS . '.createdAt', 'DESC');

        return $this->paginator->paginate(
            $queryBuilder,
            $page,
            $limit
        );
    }

    /**
     * Tìm levelNumber lớn nhất của các danh mục gốc (parent là null)
     * @return string|null
     * @throws NonUniqueResultException
     */
    public function findMaxRootLevelNumber(): ?string
    {
        $result = $this->createQueryBuilder('category')
            ->select("category.levelNumber")
            ->where(
                'category.parent is null'
            )
            ->orderBy("category.levelNumber", 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        return $result['levelNumber'] ?? null;
    }

    public function findSiblingsWithParent(Category $category): ?array
    {
        $qb = $this->createQueryBuilder('category');
        $qb->leftJoin('category.parent', 'parent');
        $qb->where(
            $qb->expr()->eq('parent.id', $qb->expr()->literal($category->getId())),
            $qb->expr()->eq('category.isArchived', $qb->expr()->literal(ArchivedDataType::UN_ARCHIVED)),
        );
        return $qb->getQuery()->getResult();
    }

    public function findAllContactTopic()
    {
        $qb = $this->createQueryBuilder('category');
        $qb->select(['category.id', 'category.title']);
        $qb->where(
            $qb->expr()->eq('category.type', $qb->expr()->literal(CategoryDataType::TYPE_TOPIC_CONTACT)),
            $qb->expr()->eq('category.isArchived', $qb->expr()->literal(ArchivedDataType::UN_ARCHIVED)),
        );
        return $qb->getQuery()->setHint(Query::HINT_READ_ONLY, true)->getResult();
    }
}
