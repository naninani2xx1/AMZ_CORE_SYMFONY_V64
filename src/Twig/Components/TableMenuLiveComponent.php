<?php

namespace App\Twig\Components;

use App\Core\DataType\ArchivedDataType;
use App\Core\DataType\LanguageDataType;
use App\Core\Entity\Menu;
use App\Core\Repository\MenuRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;


#[AsLiveComponent(template: 'components/TableMenuLiveComponent.html.twig')]
final class TableMenuLiveComponent extends BaseTableLiveComponent
{
    protected function getQueryBuilder(): QueryBuilder
    {
        return $this->findAllPaginated();
    }
    private function findAllPaginated(): QueryBuilder
    {
        $qb = $this->entityManager->getRepository(Menu::class)->createQueryBuilder(MenuRepository::ALIAS);
        $expr = $qb->expr();
        // TODO: common
        $qb->where(
            $expr->eq(MenuRepository::ALIAS.'.isArchived', $expr->literal(ArchivedDataType::UN_ARCHIVED)),
        )->orderBy(MenuRepository::ALIAS .'.parent', 'ASC');

        // TODO: filter
        if(!empty($this->filter)){
            $qb->andWhere(
                $expr->like(MenuRepository::ALIAS.'.language', $expr->literal(LanguageDataType::VIETNAMESE_LANGUAGE_CODE)),
            );
        }

        return $qb;
    }

    protected function getSearchColumns(): array
    {
        return array(
            MenuRepository::ALIAS.'.title',
        );
    }
}
