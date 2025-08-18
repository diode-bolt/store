<?php

namespace App\Query\Condition\Handlers;

use App\Query\Condition\Conditions\AbstractCondition;
use App\Query\Condition\Conditions\LessThanCondition;
use App\Query\Condition\Interfaces\ConditionHandlerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

class LessThanConditionHandler extends AbstractConditionHandler
{

    public function getName(): string
    {
        return 'lt';
    }

    public function create(ClassMetadata $metadata, string $field, mixed $value, array $options = []): AbstractCondition
    {
        $this->validator->validate($metadata, $field, $value);

        return new LessThanCondition($field, $value);
    }
}