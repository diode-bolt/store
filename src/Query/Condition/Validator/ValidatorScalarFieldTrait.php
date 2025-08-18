<?php

namespace App\Query\Condition\Validator;

use Doctrine\DBAL\Types\Types;

trait ValidatorScalarFieldTrait
{
    private function validateScalarType(mixed $value, string $doctrineType, string $fieldName): void
    {
        $errorTemplate = 'Value for field "%s" must be %s, %s given.';

        switch ($doctrineType) {
            case Types::INTEGER:
            case Types::BIGINT:
                if (!is_int($value)) {
                    throw new \InvalidArgumentException(
                        sprintf($errorTemplate, $fieldName, 'integer', gettype($value))
                    );
                }
                break;

            case Types::STRING:
            case Types::TEXT:
                if (!is_string($value)) {
                    throw new \InvalidArgumentException(
                        sprintf($errorTemplate, $fieldName, 'string', gettype($value))
                    );
                }
                break;

            case Types::BOOLEAN:
                if (!is_bool($value)) {
                    throw new \InvalidArgumentException(
                        sprintf($errorTemplate, $fieldName, 'boolean', gettype($value))
                    );
                }
                break;

            case Types::FLOAT:
            case Types::DECIMAL:
                if (!is_float($value) && !is_int($value)) {
                    throw new \InvalidArgumentException(
                        sprintf($errorTemplate, $fieldName, 'float', gettype($value))
                    );
                }
                break;
            case Types::DATETIME_MUTABLE:
            case Types::DATETIME_IMMUTABLE:
                if (!strtotime($value)) {
                    throw new \InvalidArgumentException(
                        sprintf($errorTemplate, $fieldName, 'DateTime', $value)
                    );
                }
                break;

            default:
                break;
        }
    }
}