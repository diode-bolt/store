<?php

namespace App\Response\Dto;

use OpenApi\Attributes\Property;

class ListResponse implements Interfaces\JsonPropertyProviderResponse
{
    #[Property]
    public readonly int $total;

    public readonly array $data;

    public function __construct(int $total, array $data)
    {
        $this->total = $total;
        $this->data = $data;
    }

    public function getPropertiesData(): array
    {
        return [
            'total' => $this->total,
            'data' => $this->data
        ];
    }
}