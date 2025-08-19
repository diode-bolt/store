<?php

namespace App\Query\Condition\Validator;

use App\Error\WrongFilterTypeException;
use App\Query\Condition\Interfaces\ConditionValidatorInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

class FieldExistenceValidator implements ConditionValidatorInterface
{
    public function validate(ClassMetadata $metadata, string $field, mixed $value): void
    {
        if ($metadata->hasField($field) || $metadata->hasAssociation($field)) {
            return;
        }

        throw new WrongFilterTypeException('filter by ' . $field . ' not exist');
    }
}