<?php

namespace App\Service;

use App\Error\AbstractFilterException;
use App\Query\Condition\Factory\ConditionFactory;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

trait FilterConditionBuilder
{
    private ConditionFactory $conditionFactory;

    private function buildFromFilters(array $filters, string $entityName): array
    {
        $conditions = [];

        try {
            foreach ($filters as $key => $filter) {
                $conditions[] = $this->conditionFactory->create($entityName, $filter);
            }
        } catch (AbstractFilterException $exception) {
            throw new BadRequestException();
        }


        return $conditions;
    }
}