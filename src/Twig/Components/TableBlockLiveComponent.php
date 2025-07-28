<?php

namespace App\Twig\Components;

use App\Core\DataType\ArchivedDataType;
use App\Core\DataType\LanguageDataType;
use App\Core\Entity\Block;
use App\Core\Entity\Page;
use App\Core\Entity\Setting;
use App\Core\Repository\PageRepository;
use App\Core\Repository\PostRepository;
use App\Core\Services\SettingService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;


#[AsLiveComponent(template: 'components/TableBlockLiveComponent.html.twig')]
final class TableBlockLiveComponent extends BaseTableLiveComponent
{
    #[LiveProp(writable: false)]
    public ?Page $pageEntity = null;

    protected function getQueryBuilder(): QueryBuilder
    {
        return $this->findAllPaginated();
    }

    private function findAllPaginated(): QueryBuilder
    {
        $qb = $this->entityManager->getRepository(Block::class)->createQueryBuilder('block');
        $qb->join('block.post', 'post');

        $expr = $qb->expr();
        // TODO: common
        $qb->where(
            $expr->eq('post.id', $expr->literal($this->pageEntity->getPost()->getId())),
            $expr->eq('block.isArchived', $expr->literal(ArchivedDataType::UN_ARCHIVED)),
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
