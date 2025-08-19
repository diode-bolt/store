<?php

namespace App\Service;

use App\Error\Filter\FilterValidationException;
use App\Query\Condition\Factory\ConditionFactory;
use App\Request\Dto\ListRequest;

trait FilterConditionBuilder
{
    private ConditionFactory $conditionFactory;

    private function buildFromFilters(ListRequest $request, string $entityName): array
    {
        $conditions = [];

        try {
            foreach ($request->filters as $key => $filter) {
                $conditions[] = $this->conditionFactory->create($entityName, $filter);
            }
        } catch (FilterValidationException $exception) {
            $exception->path = "filters[$key].$exception->path";
            throw $exception;
        }

        return $conditions;
    }
}