<?php

namespace App\Query\Condition\Conditions;

use Doctrine\ORM\QueryBuilder;

class LikeCondition extends AbstractCondition
{
    public function __construct(
        string $field,
        string $value,
        protected bool $caseSensitive = true
    ) {
        parent::__construct($field, $value);
    }

    public function apply(QueryBuilder $qb, string $alias): void
    {
        $value = $this->caseSensitive
            ? $qb->expr()->literal('%'.$this->value.'%')
            : $qb->expr()->literal('%'.mb_strtolower($this->value).'%');

        $comparison = $this->caseSensitive
            ? $qb->expr()->like("{$alias}.{$this->field}", $value)
            : $qb->expr()->like(
                $qb->expr()->lower("{$alias}.{$this->field}"),
                $value
            );

        $qb->andWhere($comparison);
    }
}