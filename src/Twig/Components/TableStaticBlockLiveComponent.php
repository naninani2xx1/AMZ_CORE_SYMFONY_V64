<?php

namespace App\Twig\Components;

use App\Core\DataType\ArchivedDataType;
use App\Core\DataType\BlockDataType;
use App\Core\Entity\Block;
use Doctrine\ORM\QueryBuilder;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;


#[AsLiveComponent(template: 'components/TableStaticBlockLiveComponent.html.twig')]
final class TableStaticBlockLiveComponent extends BaseTableLiveComponent
{
    protected function getQueryBuilder(): QueryBuilder
    {
        return $this->findAllPaginated();
    }

    private function findAllPaginated(): QueryBuilder
    {
        $qb = $this->entityManager->getRepository(Block::class)->createQueryBuilder('block');
        $expr = $qb->expr();
        // TODO: common
        $qb->where(
            $expr->eq('block.isArchived', $expr->literal(ArchivedDataType::UN_ARCHIVED)),
            $expr->eq('block.kind', $expr->literal(BlockDataType::KIND_STATIC)),
        )->orderBy('block.sortOrder', 'ASC');

        // TODO: filter
        if(!empty($this->filter)){

        }

        return $qb;
    }

    protected function getSearchColumns(): array
    {
        return array();
    }
}
