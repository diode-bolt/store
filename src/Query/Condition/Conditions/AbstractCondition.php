<?php

namespace App\Query\Condition\Conditions;

use Doctrine\ORM\QueryBuilder;

abstract class AbstractCondition
{
    public function __construct(
        protected string $field,
        protected mixed $value
    ) {}

    abstract public function apply(QueryBuilder $qb, string $alias): void;
}