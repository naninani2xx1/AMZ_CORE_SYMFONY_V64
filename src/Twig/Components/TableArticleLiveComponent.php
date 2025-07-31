<?php

namespace App\Twig\Components;

use App\Core\DataType\ArchivedDataType;
use App\Core\DataType\LanguageDataType;
use App\Core\Entity\Article;
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
use Symfony\UX\LiveComponent\Attribute\LiveProp;


#[AsLiveComponent(template: 'components/TableArticleLiveComponent.html.twig')]
final class TableArticleLiveComponent extends BaseTableLiveComponent
{
    #[LiveProp(writable: false)]
    public string $type;
    protected function getQueryBuilder(): QueryBuilder
    {
        return $this->findAllPaginated();
    }

    private function findAllPaginated(): QueryBuilder
    {
        $qb = $this->entityManager->getRepository(Article::class)->createQueryBuilder('article');
        $qb->join('article.post', 'post');

        $expr = $qb->expr();
        // TODO: common
        $qb->where(
            $expr->eq('post.isArchived', $expr->literal(ArchivedDataType::UN_ARCHIVED)),
            $expr->eq('post.postType', $expr->literal($this->type)),
        )->orderBy('post.createdAt', 'DESC');

        // TODO: filter
        if(!empty($this->filter)){

        }

        return $qb;
    }

    protected function getSearchColumns(): array
    {
        return array(
            'post.title',
        );
    }
}
