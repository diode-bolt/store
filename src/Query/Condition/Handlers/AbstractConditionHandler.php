<?php

namespace App\Query\Condition\Handlers;

use App\Query\Condition\Interfaces\ConditionHandlerInterface;
use App\Query\Condition\Validator\ChainConditionValidator;

abstract class AbstractConditionHandler implements ConditionHandlerInterface
{
    public function __construct(protected ChainConditionValidator $validator)
    {
    }
}