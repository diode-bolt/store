<?php

namespace App\Query\Condition\Validator;

use App\Error\Filter\FilterValidationException;
use Doctrine\DBAL\Types\Types;

trait ValidatorScalarFieldTrait
{
    private function validateScalarType(mixed $value, string $doctrineType, string $fieldName): void
    {
        switch ($doctrineType) {
            case Types::INTEGER:
            case Types::BIGINT:
                if (is_int($value)) {
                    return;
                }
                $neededType = 'integer';
                break;

            case Types::STRING:
            case Types::TEXT:
                if (is_string($value)) {
                    return;
                }
                $neededType = 'string';
                break;

            case Types::BOOLEAN:
                if (is_bool($value)) {
                    return;
                }
                $neededType = 'boolean';
                break;

            case Types::FLOAT:
            case Types::DECIMAL:
                if (is_float($value) && !is_int($value)) {
                    return;
                }
                $neededType = 'float';
                break;
            case Types::DATETIME_MUTABLE:
            case Types::DATETIME_IMMUTABLE:
                if (strtotime($value)) {
                    return;
                }
                $neededType = 'DateTime';
                break;

            default:
                return;
        }

        throw new FilterValidationException(
            sprintf('Value for field "%s" must be %s, %s given.', $fieldName, $neededType, gettype($value)),
            'value',
            $value
        );
    }
}