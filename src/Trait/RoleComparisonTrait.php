<?php

namespace App\Trait;


use App\Core\DataType\RoleDataType;
use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\QueryBuilder;

trait RoleComparisonTrait
{
    public function comparisonExcludeRoleRoot(QueryBuilder $qb): Comparison
    {
        $qb->setParameter('role', RoleDataType::ROLE_ROOT);
        return $qb->expr()->eq('FIND_IN_SET(:role, user.roles)', $qb->expr()->literal(0));
    }
}