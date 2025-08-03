<?php

namespace App\Twig\Components;

use App\Core\DataType\ArchivedDataType;
use App\Entity\Distributor;
use Doctrine\ORM\QueryBuilder;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;


#[AsLiveComponent(template: 'components/TableDistributorComponent.html.twig')]
final class TableDistributorLiveComponent extends BaseTableLiveComponent
{
    protected function getQueryBuilder(): QueryBuilder
    {
        return $this->findAllPaginated();
    }

    private function findAllPaginated(): QueryBuilder
    {

        $qb = $this->entityManager->getRepository(Distributor::class)->createQueryBuilder('distributor');
        $expr = $qb->expr();
        // TODO: common
        $qb->where(
            $expr->eq('distributor.isArchived', $expr->literal(ArchivedDataType::UN_ARCHIVED)),
        );
        // TODO: filter
        if(!empty($this->filter)){

        }
        return $qb;
    }

    protected function getSearchColumns(): array
    {
        return array(
            'distributor.companyName',
            'distributor.phone',
            'distributor.email',
        );
    }
}
