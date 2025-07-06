<?php

namespace App\Core\Repository;

use App\Core\DataType\ArchivedDataType;
use App\Core\Entity\Page;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use function Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Page>
 */
class PageRepository extends ServiceEntityRepository
{
    const ALIAS = 'page';

    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Page::class);
        $this->paginator = $paginator;
    }


    /**
     * Lấy tất cả bài post với phân trang
     * @param $page
     * @param $limit
     * @return PaginationInterface
     */
    public function findAllPaginated($page = 1, $limit = 10): PaginationInterface
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS);
        $expr = $queryBuilder->expr();
        $queryBuilder->where(
            $expr->eq(self::ALIAS.'.isArchived', $expr->literal(ArchivedDataType::UN_ARCHIVED)),
        )
        ->orderBy(self::ALIAS.'.createdAt', 'DESC');

        return $this->paginator->paginate(
            $queryBuilder,
            $page,
            $limit
        );
    }
}
