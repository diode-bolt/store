<?php

namespace App\Query\Condition\Conditions;

use Doctrine\ORM\QueryBuilder;

class GreaterThanCondition extends AbstractCondition
{
    public function apply(QueryBuilder $qb, string $alias): void
    {
        $qb->andWhere("{$alias}.{$this->field} > :{$this->field}")
            ->setParameter($this->field, $this->value);
    }
}