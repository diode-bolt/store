<?php

namespace App\Query\Condition\Validator;

use App\Query\Condition\Interfaces\ConditionValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

class FieldAssocValidator implements ConditionValidatorInterface
{
    use ValidatorScalarFieldTrait;

    public function __construct(private EntityManagerInterface $manager)
    {
    }

    public function validate(ClassMetadata $metadata, string $field, mixed $value)
    {
        if (!$metadata->hasAssociation($field)) {
            return;
        }

        $targetClass = $metadata->getAssociationTargetClass($field);

        $associationMapping = $metadata->getAssociationMapping($field);
        $targetIdFieldType = $this->manager->getClassMetadata($targetClass)
            ->getTypeOfField($associationMapping->fieldName);

        if (isset($associationMapping->joinColumns)) {
            foreach ($associationMapping->joinColumns as $joinColumn) {
                if (isset($joinColumn->nullable) && $joinColumn->nullable && is_null($value)) {
                    return;
                }
            }
        }

        $this->validateScalarType($value, $targetIdFieldType, $field);
    }
}