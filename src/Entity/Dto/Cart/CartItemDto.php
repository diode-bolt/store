<?php

namespace App\Entity\Dto\Cart;

class CartItemDto
{
    public function __construct(
        public readonly int $productId,
        public readonly int $count,
        public readonly float $cost,
        public readonly string $name,
    )
    {
    }
}