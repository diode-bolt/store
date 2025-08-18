<?php

namespace App\Entity\Dto\Order;

use App\Entity\Order;

class OrderListItem
{
    public function __construct(
        public readonly int $id,
        public readonly int $delivery,
        public readonly string $status,
        public readonly int $totalCost,
    )
    {
    }

    public static function with(Order $order): OrderListItem
    {
        return new self(
            $order->getId(),
            $order->getDelivery()->value,
            $order->getStatus()->getLabel(),
            $order->getTotalCost()
        );
    }
}