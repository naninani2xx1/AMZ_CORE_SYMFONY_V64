<?php

namespace App\Core\Repository;

use App\Core\DataType\ArchivedDataType;
use App\Core\DataType\PostStatusType;
use App\Core\DataType\PostTypeDataType;
use App\Core\Entity\Page;
use App\Form\Common\PublishedChoiceType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

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

    public function findOneBySlug(string $slug): ?Page
    {
        $qb = $this->createQueryBuilder('page');
        $qb->join('page.post', 'post');
        $qb->where(
            $qb->expr()->eq('post.slug', $qb->expr()->literal($slug)),
            $qb->expr()->eq('post.isArchived', $qb->expr()->literal(ArchivedDataType::UN_ARCHIVED)),
            $qb->expr()->eq('post.published', $qb->expr()->literal(PostStatusType::PUBLISH_TYPE_PUBLISHED)),
        );
        return $qb->getQuery()->getOneOrNullResult();
    }
}
