<?php

namespace App\Twig\Components;

use App\Core\DataType\ArchivedDataType;
use App\Core\DataType\LanguageDataType;
use App\Core\Entity\Page;
use App\Core\Repository\MenuRepository;
use App\Core\Repository\PostRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;


#[AsLiveComponent(template: 'components/TablePageLiveComponent.html.twig')]
final class TablePageLiveComponent extends BaseTableLiveComponent
{
    protected function getQueryBuilder(): QueryBuilder
    {
        return $this->findAllPaginated();
    }

    private function findAllPaginated(): QueryBuilder
    {
        $qb = $this->entityManager->getRepository(Page::class)->createQueryBuilder(MenuRepository::ALIAS);
        $qb->leftJoin(MenuRepository::ALIAS.'.post', PostRepository::ALIAS);

        $expr = $qb->expr();
        // TODO: common
        $qb->where(
            $expr->eq(MenuRepository::ALIAS.'.isArchived', $expr->literal(ArchivedDataType::UN_ARCHIVED)),
        )->orderBy(MenuRepository::ALIAS .'.createdAt', 'DESC');

        // TODO: filter
        if(!empty($this->filter)){
            $qb->andWhere(
                $expr->like(PostRepository::ALIAS.'.language', $expr->literal(LanguageDataType::VIETNAMESE_LANGUAGE_CODE)),
            );
        }

        return $qb;
    }

    protected function getSearchColumns(): array
    {
        return array(
            MenuRepository::ALIAS.'.name',
            PostRepository::ALIAS.'.title'
        );
    }
}
