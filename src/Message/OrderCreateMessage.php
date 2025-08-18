<?php

namespace App\Message;

use App\Entity\Enums\OrderDeliveryType;
use App\Message\Dto\DeliveryAddress;
use App\Message\Enums\NotifyType;

class OrderCreateMessage
{
    public function __construct(
        public readonly NotifyType $type,
        public readonly string $userContact,
        public readonly string $notificationType,
        public readonly string $orderNum,
        public readonly array $orderItems,
        public readonly OrderDeliveryType $deliveryType,
        public readonly DeliveryAddress $deliveryAddress,
    )
    {
    }
}