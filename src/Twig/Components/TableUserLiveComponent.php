<?php

namespace App\Twig\Components;

use App\Core\DataType\ArchivedDataType;
use App\Core\Entity\User;
use App\Trait\RoleComparisonTrait;
use Doctrine\ORM\QueryBuilder;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent(template: 'components/TableUserLiveComponent.html.twig')]
final class TableUserLiveComponent extends BaseTableLiveComponent
{
    use RoleComparisonTrait;

    protected function getQueryBuilder(): QueryBuilder
    {
        return $this->findAllPaginated();
    }

    private function findAllPaginated(): QueryBuilder
    {
        $qb = $this->entityManager->getRepository(User::class)->createQueryBuilder('user');

        $expr = $qb->expr();
        // TODO: common
        $qb->where(
            $expr->eq('user.isArchived', $expr->literal(ArchivedDataType::UN_ARCHIVED)),
            $this->comparisonExcludeRoleRoot($qb),
        )->orderBy('user.createdAt', 'ASC');

        // TODO: filter
        if(!empty($this->filter)){

        }

        return $qb;
    }

    protected function getSearchColumns(): array
    {
        return array('user.username');
    }
}
