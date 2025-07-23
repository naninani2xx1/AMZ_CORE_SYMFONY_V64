<?php

namespace App\Twig\Components;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

abstract class BaseTableLiveComponent extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp(writable: true, onUpdated: 'changeKeyword')]
    public string $keyword = '';

    #[LiveProp(writable: true)]
    public array $filter = [];

    #[LiveProp(writable: true)]
    public int $page = 1;

    #[LiveProp(writable: true)]
    public int $limit = 10;

    public function __construct(
        protected readonly EntityManagerInterface $entityManager,
        private readonly PaginatorInterface $paginator,
    )
    {
    }

    #[LiveAction]
    public function changePage(#[LiveArg] $page): void
    {
        $this->page = $page;
    }

    public function changeKeyword(): void
    {
        $this->filter = [];
        $this->page = 1;
    }
    #[LiveAction]
    public function changeLimit(#[LiveArg] $limit): void
    {
        $this->limit = $limit;
    }

    /**
     * Get items for table
     * @return PaginationInterface
     */
    public function getData(): PaginationInterface
    {
        $qb = $this->getQueryBuilder();
        $expr = $qb->expr();
        $orx = $expr->orX();

        $columns = $this->getSearchColumns();
        $literal = $expr->literal('%'. trim($this->keyword) .'%');
        // TODO: search
        if(!empty($this->keyword)){
            foreach($columns as $column){
                $orx->add($expr->like($column, $literal));
            }
            $qb->andWhere($orx);
        }
        return $this->paginator->paginate($qb->getQuery(), $this->page, $this->limit);
    }

    abstract protected function getQueryBuilder(): ?QueryBuilder;

    abstract protected function getSearchColumns(): array;
}
