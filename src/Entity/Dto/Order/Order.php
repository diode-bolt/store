<?php

namespace App\Entity\Dto\Order;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;

class Order
{
    #[Property(
        type: 'array',
        items: new Items(ref: new Model(type: OrderItem::class))
    )]
    public readonly array $items;

    public function __construct(
        public readonly int $id,
        public readonly int $delivery,
        public readonly string $status,
        public readonly int $totalCost,
        array $items = []
    )
    {
        $this->items = $items;
    }
}