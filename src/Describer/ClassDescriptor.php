<?php

namespace App\Describer;

class ClassDescriptor
{
    public function __construct(
        public readonly string $class,
        public readonly array $responseProps,
        public readonly array $attributes,
        public readonly bool $isList
    )
    {
    }
}