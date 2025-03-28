<?php

namespace App\Describer;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Generator;

class PropertyDescriber
{
    public const BUILTIN_TYPE_INT = 'int';
    public const BUILTIN_TYPE_FLOAT = 'float';
    public const BUILTIN_TYPE_STRING = 'string';
    public const BUILTIN_TYPE_BOOL = 'bool';
    public const BUILTIN_TYPE_RESOURCE = 'resource';
    public const BUILTIN_TYPE_OBJECT = 'object';
    public const BUILTIN_TYPE_ARRAY = 'array';
    public const BUILTIN_TYPE_ITERABLE = 'iterable';

    public function describe(\ReflectionProperty $property, Property $attr): Property
    {
        $type = $property->getType();
        $attr->property = $property->getName();

        if ($type->isBuiltin()) {
            $this->setType((string) $type, $attr);
        } else {
            $attr->type = 'object';
            $attr->ref = new Model(type: $type->getName());
        }

        return $attr;
    }

    private function setType(string $type, Property $attr): void
    {
        switch ($type) {
            case self::BUILTIN_TYPE_RESOURCE:
                $attr->type = 'string';

                if ($attr->format === Generator::UNDEFINED) {
                    $attr->format = 'binary';
                }
                break;
            case self::BUILTIN_TYPE_ARRAY:
            case self::BUILTIN_TYPE_ITERABLE:
                $attr->type = 'array';
                if ($attr->items === Generator::UNDEFINED) {
                    $attr->items = new Items();
                }
                break;
            case self::BUILTIN_TYPE_INT:
                $attr->type = 'integer';
                break;
            case self::BUILTIN_TYPE_FLOAT:
                $attr->type = 'number';
                break;
            case self::BUILTIN_TYPE_BOOL:
                $attr->type = 'bool';
                break;
            default:
                $attr->type = 'string';
                break;
        }
    }
}