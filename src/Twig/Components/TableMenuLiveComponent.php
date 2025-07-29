<?php

namespace App\Twig\Components;

use App\Core\DataType\ArchivedDataType;
use App\Core\DataType\MenuDataType;
use App\Core\Entity\Menu;
use Doctrine\ORM\QueryBuilder;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;


#[AsLiveComponent(template: 'components/TableMenuLiveComponent.html.twig')]
final class TableMenuLiveComponent extends BaseTableLiveComponent
{
    #[LiveProp(writable: false)]
    public string $type = "";

    #[LiveProp(writable: false)]
    public ?Menu $menuParent = null;

    protected function getQueryBuilder(): QueryBuilder
    {
        return $this->findAllPaginated();
    }

    private function findAllPaginated(): QueryBuilder
    {
        $qb = $this->entityManager->getRepository(Menu::class)->createQueryBuilder('menu');

        $expr = $qb->expr();
        // TODO: common
        $qb->where(
            $expr->eq('menu.isArchived', $expr->literal(ArchivedDataType::UN_ARCHIVED)),
            $expr->eq('menu.display', $expr->literal(MenuDataType::DISPLAY_SHOW)),
            $expr->eq('menu.isRoot', $expr->literal($this->type)),
        )->orderBy('menu.createdAt', 'ASC');
        if($this->menuParent instanceof Menu){
            $qb->andWhere(
                'menu.parent = :parent'
            )->setParameter('parent', $this->menuParent);
        }
        // TODO: filter
        if(!empty($this->filter)){

        }

        return $qb;
    }

    protected function getSearchColumns(): array
    {
        return array(
            'menu.name',
        );
    }
}
