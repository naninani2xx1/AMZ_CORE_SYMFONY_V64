<?php

namespace App\Twig\Components;

use App\Core\DataType\ArchivedDataType;
use App\Core\DataType\LanguageDataType;
use App\Core\Entity\Post;
use App\Core\Repository\PostRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;


#[AsLiveComponent(template: 'components/TablePostLiveComponent.html.twig')]
final class TablePostLiveComponent extends BaseTableLiveComponent
{
    protected function getQueryBuilder(): QueryBuilder
    {
        return $this->findAllPaginated();
    }
    private function findAllPaginated(): QueryBuilder
    {
        $qb = $this->entityManager->getRepository(Post::class)->createQueryBuilder(PostRepository::ALIAS);
        $expr = $qb->expr();
        // TODO: common
        $qb->where(
            $expr->eq(PostRepository::ALIAS.'.isArchived', $expr->literal(ArchivedDataType::UN_ARCHIVED)),
        )->orderBy(PostRepository::ALIAS .'.createdAt', 'DESC');

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
            PostRepository::ALIAS.'.title',
        );
    }
}
