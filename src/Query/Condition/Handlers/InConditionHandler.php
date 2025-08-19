<?php

namespace App\Query\Condition\Handlers;

use App\Error\Filter\WrongFilterValueException;
use App\Query\Condition\Conditions\AbstractCondition;
use App\Query\Condition\Conditions\InCondition;
use App\Query\Condition\Validator\ChainConditionValidator;
use Doctrine\ORM\Mapping\ClassMetadata;

class InConditionHandler extends AbstractConditionHandler
{
    public function __construct(ChainConditionValidator $validator)
    {
        parent::__construct($validator);
    }

    public function getName(): string
    {
        return 'in';
    }

    public function create(ClassMetadata $metadata, string $field, mixed $value, array $options = []): AbstractCondition
    {
        $this->validator->validate($metadata, $field, $value);

        if (is_array($value) && !array_all($value, 'is_scalar')) {
            throw new WrongFilterValueException('incorrect values');
        }

        return new InCondition($field, $value);
    }
}