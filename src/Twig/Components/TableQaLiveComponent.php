<?php

namespace App\Twig\Components;

use App\Core\DataType\ArchivedDataType;
use App\Core\DataType\LanguageDataType;
use App\Core\Entity\Page;
use App\Core\Entity\Qa;
use App\Core\Entity\Setting;
use App\Core\Repository\PageRepository;
use App\Core\Repository\PostRepository;
use App\Core\Services\SettingService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;


#[AsLiveComponent(template: 'components/TableQaLiveComponent.html.twig')]
final class TableQaLiveComponent extends BaseTableLiveComponent
{
    protected function getQueryBuilder(): QueryBuilder
    {
        return $this->findAllPaginated();
    }

    private function findAllPaginated(): QueryBuilder
    {
        $qb = $this->entityManager->getRepository(Qa::class)->createQueryBuilder('qa');

        $expr = $qb->expr();
        // TODO: common
        $qb->where(
            $expr->eq('qa.isArchived', $expr->literal(ArchivedDataType::UN_ARCHIVED)),
        )->orderBy('qa.createdAt', 'DESC');

        // TODO: filter
        if(!empty($this->filter)){

        }

        return $qb;
    }

    protected function getSearchColumns(): array
    {
        return array(
            'qa.question',
            'qa.answer',
        );
    }
}
