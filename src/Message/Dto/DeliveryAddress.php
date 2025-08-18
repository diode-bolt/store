<?php

namespace App\Message\Dto;

class DeliveryAddress
{
    public function __construct(
        public readonly ?int $kladrId = null,
        public readonly ?string $fullAddress = null,
    )
    {
    }
}