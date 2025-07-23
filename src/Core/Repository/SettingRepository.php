<?php

namespace App\Core\Repository;

use App\Core\Entity\Setting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;


class SettingRepository extends ServiceEntityRepository
{
    const ALIAS = 'setting';
    private $paginator;
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Setting::class);
        $this->paginator = $paginator;
    }
    /**
     * @param $page
     * @param $limit
     *
     * */
    public function findAllSetting(int $page=1, int $limit=10): \Knp\Component\Pager\Pagination\PaginationInterface|array
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS);
        $queryBuilder->orderBy(self::ALIAS . '.createdAt', 'ASC');

        return $this->paginator->paginate($queryBuilder, $page, $limit);
    }
    public function findTopics(): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.settingType = :type')
            ->setParameter('type', 'topic')
            ->getQuery()
            ->getResult();

    }
}