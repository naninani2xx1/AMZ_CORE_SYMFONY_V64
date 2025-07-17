<?php

namespace App\Core\Repository;

use App\Core\DataType\ArchivedDataType;
use App\Core\Entity\Menu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Menu>
 */
class MenuRepository extends ServiceEntityRepository
{
    const ALIAS = 'menu';

    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Menu::class);
        $this->paginator = $paginator;
    }


    /**
     * @param int $page
     * @param int $limit
     * @return PaginationInterface
     */
    public function findAllPaginated(int $page = 1,int $limit = 10): PaginationInterface
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS);
        $expr = $queryBuilder->expr();
        $queryBuilder->where(
            $expr->eq(self::ALIAS.'.isArchived', $expr->literal(ArchivedDataType::UN_ARCHIVED)),
        )
            ->orderBy(self::ALIAS.'.createdAt', 'DESC');

        return $this->paginator->paginate($queryBuilder, $page, $limit);
    }
}
