<?php

namespace App\Request\Dto;

use Symfony\Component\Validator\Constraints\Positive;

class CartItem
{
    public readonly int $productId;
    #[Positive]
    public readonly int $count;

    public function __construct(int $productId, int $count)
    {
        $this->productId = $productId;
        $this->count = $count;
    }
}