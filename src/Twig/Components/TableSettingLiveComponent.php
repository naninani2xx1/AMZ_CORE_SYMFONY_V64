<?php

namespace App\Twig\Components;

use App\Core\DataType\ArchivedDataType;
use App\Core\DataType\LanguageDataType;
use App\Core\Entity\Page;
use App\Core\Entity\Setting;
use App\Core\Repository\PageRepository;
use App\Core\Repository\PostRepository;
use App\Core\Services\SettingService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;


#[AsLiveComponent(template: 'components/TableSettingLiveComponent.html.twig')]
final class TableSettingLiveComponent extends BaseTableLiveComponent
{
    protected function getQueryBuilder(): QueryBuilder
    {
        return $this->findAllPaginated();
    }

    private function findAllPaginated(): QueryBuilder
    {
        $qb = $this->entityManager->getRepository(Setting::class)->createQueryBuilder('setting');

        $expr = $qb->expr();
        // TODO: common
        $qb->where(
            $expr->eq('setting.isArchived', $expr->literal(ArchivedDataType::UN_ARCHIVED)),
        )->orderBy('setting.createdAt', 'DESC');

        // TODO: filter
        if(!empty($this->filter)){
            $qb->andWhere(
                $expr->like('setting.settingType', $expr->literal($this->filter['settingType'])),
            );
        }

        return $qb;
    }

    protected function getSearchColumns(): array
    {
        return array(
            'setting.settingKey',
        );
    }
}
