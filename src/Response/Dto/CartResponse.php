<?php

namespace App\Response\Dto;

use App\Entity\Dto\Cart\CartItemDto;
use App\Response\Dto\Interfaces\JsonPropertyProviderResponse;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;

#[Response(response: 200)]
class CartResponse implements JsonPropertyProviderResponse
{
    #[Property]
    public readonly int $totalCount;
    #[Property]
    public readonly float $totalCost;
    #[Property(
        type: 'array',
        items: new Items(ref: new Model(type: CartItemDto::class))
    )]
    public readonly array $items;

    public function __construct(
        int $totalCount,
        float $totalCost,
        array $items,
    )
    {
        $this->totalCount = $totalCount;
        $this->totalCost = $totalCost;
        $this->items = $items;
    }

    public function getPropertiesData(): array
    {
        return [
            'totalCount' => $this->totalCount,
            'totalCost' => $this->totalCost,
            'items' => $this->items,
        ];
    }
}