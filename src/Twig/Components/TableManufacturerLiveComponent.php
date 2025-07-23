<?php

namespace App\Twig\Components;

use App\Core\DataType\ArchivedDataType;
use App\Core\DataType\LanguageDataType;
use App\Entity\Manufacturer;
use App\Repository\ManufacturerRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;


#[AsLiveComponent(template: 'components/TableManufacturerLiveComponent.html.twig')]
final class TableManufacturerLiveComponent extends BaseTableLiveComponent
{
    protected function getQueryBuilder(): QueryBuilder
    {
        return $this->findAllPaginated();
    }
    private function findAllPaginated(): QueryBuilder
    {
        $qb = $this->entityManager->getRepository(Manufacturer::class)->createQueryBuilder(ManufacturerRepository::ALIAS);
        $expr = $qb->expr();
        // TODO: common
        $qb->where(
            $expr->eq(ManufacturerRepository::ALIAS.'.isArchived', $expr->literal(ArchivedDataType::UN_ARCHIVED)),
        )->orderBy(ManufacturerRepository::ALIAS .'.createdAt', 'DESC');

        // TODO: filter
        if(!empty($this->filter)){
            $qb->andWhere(
                $expr->like(ManufacturerRepository::ALIAS.'.language', $expr->literal(LanguageDataType::VIETNAMESE_LANGUAGE_CODE)),
            );
        }

        return $qb;
    }

    protected function getSearchColumns(): array
    {
        return array(
            ManufacturerRepository::ALIAS.'.name',
        );
    }
}
