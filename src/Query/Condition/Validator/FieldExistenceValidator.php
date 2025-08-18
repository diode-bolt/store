<?php

namespace App\Query\Condition\Validator;

use App\Query\Condition\Interfaces\ConditionValidatorInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

class FieldExistenceValidator implements ConditionValidatorInterface
{
    public function validate(ClassMetadata $metadata, string $field, mixed $value): void
    {
        if ($metadata->hasField($field) || $metadata->hasAssociation($field)) {
            return;
        }

        throw new \InvalidArgumentException('filter by ' . $field . ' not exist');
    }
}