<?php

namespace App\Service;

use App\Query\Condition\Factory\ConditionFactory;

trait FilterConditionBuilder
{
    private ConditionFactory $conditionFactory;

    private function buildFromFilters(array $filters, string $entityName): array
    {
        $conditions = [];

        foreach ($filters as $filter) {
            $conditions[] = $this->conditionFactory->create($entityName, $filter);
        }

        return $conditions;
    }
}