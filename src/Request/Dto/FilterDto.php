<?php

namespace App\Request\Dto;

class FilterDto
{
    public function __construct(
        public readonly string $field,
        public readonly string $type,
        public readonly string|int|bool|float|null $value,
        public readonly array $options = [],
    )
    {
    }
}