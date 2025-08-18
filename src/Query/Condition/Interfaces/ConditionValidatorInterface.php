<?php

namespace App\Query\Condition\Interfaces;

use Doctrine\ORM\Mapping\ClassMetadata;

interface ConditionValidatorInterface
{
    public function validate(ClassMetadata $metadata, string $field, mixed $value);
}