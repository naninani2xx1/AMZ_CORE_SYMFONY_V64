<?php

namespace App\Twig\Components;

use App\Core\DataType\ArchivedDataType;
use App\Core\DataType\LanguageDataType;
use App\Core\Entity\Setting;
use App\Core\Repository\MenuRepository;
use App\Core\Repository\PostRepository;
use App\Core\Repository\SettingRepository;
use Doctrine\ORM\QueryBuilder;
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
        $qb = $this->entityManager->getRepository(Setting::class)->createQueryBuilder(SettingRepository::ALIAS);
        $expr = $qb->expr();
        // TODO: common
        $qb->where(
            $expr->eq(SettingRepository::ALIAS.'.isArchived', $expr->literal(ArchivedDataType::UN_ARCHIVED)),
        )->orderBy(SettingRepository::ALIAS .'.createdAt', 'DESC');

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
            SettingRepository::ALIAS.'.settingKey',
        );
    }
}
