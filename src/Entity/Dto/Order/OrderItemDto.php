<?php

namespace App\Entity\Dto\Order;

use App\Entity\Product;

class OrderItemDto
{
    public function __construct(
        public readonly int $productId,
        public readonly string $name,
        public readonly int $cost,
        public readonly int $count,
    )
    {
    }

    public static function with(\App\Entity\OrderItem $item): OrderItemDto
    {
        $product = $item->getProduct();
        $count = $item->getCount();

        return new self($product->getId(), $product->getName(), $product->getCost() * $count, $count);
    }
}