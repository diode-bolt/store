<?php

namespace App\Entity\Enums;

enum OrderDeliveryType: int
{
    case PICKUP = 0;
    case COURIER = 1;

    public function getLabel(): string
    {
        return match ($this) {
            self::PICKUP => 'Pickup',
            self::COURIER => 'Courier',
        };
    }
}
