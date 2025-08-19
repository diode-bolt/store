<?php

namespace App\Entity\Dto\Order;

use App\Entity\Order;
use App\Response\Dto\Interfaces\JsonResponseInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;

#[Response(response: 200)]
class OrderDto implements JsonResponseInterface
{
    #[Property(
        type: 'array',
        items: new Items(ref: new Model(type: OrderItemDto::class))
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

    public static function with(Order $order): self
    {
        $items = [];

        foreach ($order->getOrderItems() as $orderItem) {
            $items[] = OrderItemDto::with($orderItem);
        }

        return new self(
            $order->getId(),
            $order->getDelivery()->value,
            $order->getStatus()->getLabel(),
            $order->getTotalCost(),
            $items,
        );
    }
}