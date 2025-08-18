<?php

namespace App\Query\Condition\Handlers;

use App\Query\Condition\Conditions\AbstractCondition;
use App\Query\Condition\Conditions\EqualsCondition;
use App\Query\Condition\Interfaces\ConditionHandlerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

class EqualsConditionHandler extends AbstractConditionHandler
{
    public function create(ClassMetadata $metadata, string $field, mixed $value, array $options = []): AbstractCondition
    {
        $this->validator->validate($metadata, $field, $value);

        return new EqualsCondition($field, $value);
    }

    public function getName(): string
    {
        return 'eq';
    }
}