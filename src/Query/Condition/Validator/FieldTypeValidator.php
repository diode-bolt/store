<?php

namespace App\Query\Condition\Validator;

use App\Query\Condition\Interfaces\ConditionValidatorInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

class FieldTypeValidator implements ConditionValidatorInterface
{
    use ValidatorScalarFieldTrait;

    public function validate(ClassMetadata $metadata, string $field, mixed $value): void
    {
        if (!$metadata->hasField($field)) {
            return;
        }

        $this->validateScalarType($value, $metadata->getTypeOfField($field), $field);
    }
}