<?php

namespace App\Response\Dto;

use App\Entity\Dto\UserListItem;
use App\Response\Dto\Interfaces\JsonPropertyProviderResponse;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes\Items;
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