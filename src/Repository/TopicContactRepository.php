<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Contact>
 */
class TopicContactRepository extends ServiceEntityRepository
{
    const ALIAS = 'topic';
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
    public function findAllTopicContact(int $page=1, int $limit=10): \Knp\Component\Pager\Pagination\PaginationInterface|array
    {
        $qb = $this->createQueryBuilder(self::ALIAS)->orderBy(self::ALIAS . '.createdAt', 'ASC');
        return $this->paginator->paginate($qb, $page, $limit);
    }


}
