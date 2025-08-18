<?php

namespace App\Query\Condition\Interfaces;

use App\Query\Condition\Conditions\AbstractCondition;
use Doctrine\ORM\Mapping\ClassMetadata;

interface ConditionHandlerInterface
{
    public function getName(): string;
    public function create(ClassMetadata $metadata, string $field, mixed $value, array $options = []): AbstractCondition;
}