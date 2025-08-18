<?php

namespace App\Query\Condition\Conditions;

use Doctrine\ORM\QueryBuilder;

class InCondition extends AbstractCondition
{
    public function __construct(string $field, array $value)
    {
        parent::__construct($field, $value);
    }

    public function apply(QueryBuilder $qb, string $alias): void
    {
        $qb->andWhere($qb->expr()->in("{$alias}.{$this->field}", ":{$this->field}"))
            ->setParameter($this->field, $this->value);
    }
}