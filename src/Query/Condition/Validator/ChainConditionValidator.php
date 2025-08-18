<?php

namespace App\Query\Condition\Validator;

use App\Query\Condition\Interfaces\ConditionValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

class ChainConditionValidator implements ConditionValidatorInterface
{
    private array $validators;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->validators = [
            new FieldExistenceValidator(),
            new FieldTypeValidator(),
            new FieldAssocValidator($manager),
        ];
    }

    public function validate(ClassMetadata $metadata, string $field, mixed $value): void
    {
        foreach ($this->validators as $validator) {
            $validator->validate($metadata, $field, $value);
        }
    }
}