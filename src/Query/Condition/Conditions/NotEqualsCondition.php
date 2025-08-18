<?php

namespace App\Query\Condition\Conditions;

use Doctrine\ORM\QueryBuilder;

class NotEqualsCondition extends AbstractCondition
{

    public function apply(QueryBuilder $qb, string $alias): void
    {
        if (is_null($this->value)) {
            $qb->andWhere("{$alias}.{$this->field} IS NOT NULL");
            return;
        }

        $qb->andWhere("{$alias}.{$this->field} != :{$this->field}")
            ->setParameter($this->field, $this->value);
    }
}