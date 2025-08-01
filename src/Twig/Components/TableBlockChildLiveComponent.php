<?php

namespace App\Twig\Components;

use App\Core\DataType\ArchivedDataType;
use App\Core\DataType\BlockDataType;
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


#[AsLiveComponent(template: 'components/TableBlockChildLiveComponent.html.twig')]
final class TableBlockChildLiveComponent extends BaseTableLiveComponent
{
    #[LiveProp(writable: false)]
    public ?int $parentId = null;

    protected function getQueryBuilder(): QueryBuilder
    {
        return $this->findAllPaginated();
    }

    private function findAllPaginated(): QueryBuilder
    {
        $qb = $this->entityManager->getRepository(Block::class)->createQueryBuilder('block');

        $expr = $qb->expr();

        if (!empty($this->parentId)) {
            $qb->andWhere('block.parent = :parentId')
                ->setParameter('parentId', $this->parentId);
        } else {
            $qb->andWhere('block.parent IS NULL');
        }

        $qb->andWhere($expr->eq('block.isArchived', $expr->literal(ArchivedDataType::UN_ARCHIVED)));

        if (!empty($this->pageEntity)) {
            $qb->join('block.post', 'post');
            $qb->andWhere('post.id = :postId')
                ->setParameter('postId', $this->pageEntity->getPost()->getId());
        }

        if (!empty($this->kind)) {
            $qb->andWhere('block.kind = :kind')
                ->setParameter('kind', $this->kind);
        }

        $qb->orderBy('block.sortOrder', 'ASC');

        return $qb;
    }

    protected function getSearchColumns(): array
    {
        return array();
    }
}
