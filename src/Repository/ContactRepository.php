<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Contact>
 */
class ContactRepository extends ServiceEntityRepository
{
    const ALIAS = 'contact';
    private $paginator;
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Contact::class);
        $this->paginator=$paginator;
    }
    /**
     * @param $page
     * @param $limit
     *
     * */
    public function findAllContact($filters, int $page=1, int $limit=10): \Knp\Component\Pager\Pagination\PaginationInterface|array
    {
        $qb = $this->createQueryBuilder(self::ALIAS)->orderBy(self::ALIAS . '.createdAt', 'ASC');

        if (!empty($filters['from_date'])) {
            $fromDate = \DateTime::createFromFormat('Y-m-d', $filters['from_date']);
            if ($fromDate) {
                $qb->andWhere(self::ALIAS . '.createdAt >= :from_date')
                    ->setParameter('from_date', $fromDate->format('Y-m-d') . ' 00:00:00');
            }
        }

        if (!empty($filters['to_date'])) {
            $toDate = \DateTime::createFromFormat('Y-m-d', $filters['to_date']);
            if ($toDate) {
                $qb->andWhere(self::ALIAS . '.createdAt < :to_date')
                    ->setParameter('to_date', $toDate->format('Y-m-d') . ' 23:59:59');
            }
        }

        if (!empty($filters['topic'])) {
            $qb->andWhere(self::ALIAS . '.topic = :topic')
                ->setParameter('topic', $filters['topic']);
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $qb->andWhere(self::ALIAS . '.status = :status')
                ->setParameter('status', $filters['status']);
        }

        return $this->paginator->paginate($qb, $page, $limit);
    }

}
