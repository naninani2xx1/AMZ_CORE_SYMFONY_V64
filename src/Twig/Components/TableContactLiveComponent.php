<?php

namespace App\Twig\Components;

use App\Core\DataType\ArchivedDataType;
use App\Core\DataType\LanguageDataType;
use App\Entity\Contact;
use App\Repository\ContactRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;


#[AsLiveComponent(template: 'components/TableContactLiveComponent.html.twig')]
final class TableContactLiveComponent extends BaseTableLiveComponent
{
    protected function getQueryBuilder(): QueryBuilder
    {
        return $this->findAllPaginated();
    }
    private function findAllPaginated(): QueryBuilder
    {
        $qb = $this->entityManager->getRepository(Contact::class)->createQueryBuilder(ContactRepository::ALIAS);
        $expr = $qb->expr();
        // TODO: common
        $qb->where(
            $expr->eq(ContactRepository::ALIAS.'.isArchived', $expr->literal(ArchivedDataType::UN_ARCHIVED)),
        )->orderBy(ContactRepository::ALIAS .'.createdAt', 'ASC');

        if (!empty($this->filtersContact['from_date'])) {
            $from = \DateTime::createFromFormat('Y-m-d', $this->filtersContact['from_date']);
            $from->setTime(0, 0, 0);
            $qb->andWhere($expr->gte(ContactRepository::ALIAS . '.createdAt', ':fromDate'))
                ->setParameter('fromDate', $from);

        }

        if (!empty($this->filtersContact['to_date'])) {
            $to = \DateTime::createFromFormat('Y-m-d', $this->filtersContact['to_date']);
            $to->setTime(23, 59, 59);
            $qb->andWhere($expr->lte(ContactRepository::ALIAS . '.createdAt', ':toDate'))
                ->setParameter('toDate', $to);
        }

        if (isset($this->filtersContact['status']) && $this->filtersContact['status'] !== '') {
            $qb->andWhere($expr->eq(ContactRepository::ALIAS . '.status', ':status'))
                ->setParameter('status', $this->filtersContact['status']);
        }

        if (!empty($this->filtersContact['topic'])) {
            $qb->andWhere($expr->eq(ContactRepository::ALIAS . '.topic', ':topic'))
                ->setParameter('topic', $this->filtersContact['topic']);
        }

        // TODO: filter
        if(!empty($this->filter)){
            $qb->andWhere(
                $expr->like(ContactRepository::ALIAS.'.language', $expr->literal(LanguageDataType::VIETNAMESE_LANGUAGE_CODE)),
            );
        }

        return $qb;
    }

    protected function getSearchColumns(): array
    {
        return array(
            ContactRepository::ALIAS.'.name',
        );
    }
}
