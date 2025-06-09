<?php

namespace App\Entity\Dto;

class ProductListItem
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $description,
        public readonly int $cost,
    )
    {
    }
}