<?php

namespace App\Entity\Dto;

use App\Entity\Product;
use App\Response\Dto\Interfaces\JsonResponseInterface;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;

#[Response(response: 200)]
class ProductItem implements JsonResponseInterface
{
    #[Property(
        type: 'object',
        example: [
            'weight' => 4,
            'height' => 3,
            'width' => 2,
            'length' => 1,
        ],
        additionalProperties: true
    )]
    public readonly array $measurments;

    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $description,
        public readonly int $cost,
        array $measurments
    )
    {
        $this->measurments = $measurments;
    }

    public static function with(Product $product): ProductItem
    {
        return new self(
            $product->getId(),
            $product->getName(),
            $product->getDescription(),
            $product->getCost(),
            $product->getMeasurments()
        );
    }
}