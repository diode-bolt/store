<?php

namespace App\Query\Condition\Handlers;

use App\Query\Condition\Conditions\AbstractCondition;
use App\Query\Condition\Conditions\LikeCondition;
use App\Query\Condition\Interfaces\ConditionHandlerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

class LikeConditionHandler extends AbstractConditionHandler
{

    public function getName(): string
    {
        return 'like';
    }

    public function create(ClassMetadata $metadata, string $field, mixed $value, array $options = []): AbstractCondition
    {
        $this->validator->validate($metadata, $field, $value);

        return new LikeCondition($field, $value, $options['caseSensitive'] ?? true);
    }
}