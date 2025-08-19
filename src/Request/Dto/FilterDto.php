<?php

namespace App\Request\Dto;

use OpenApi\Attributes as OA;

class FilterDto
{
    #[OA\Property(
        type: 'string',
        example: 'example_value',
        oneOf: [
            new OA\Schema(type: 'string'),
            new OA\Schema(type: 'integer'),
            new OA\Schema(type: 'boolean'),
            new OA\Schema(type: 'array', items: new OA\Items(type: 'string')),
        ]
    )]
    public readonly mixed $value;

    public function __construct(
        public readonly string $field,
        public readonly string $type,
        mixed $value,
        public readonly array $options = [],
    )
    {
        $this->value = $value;
    }
}