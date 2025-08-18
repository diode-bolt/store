<?php

namespace App\Collection;

use App\Entity\CartItem;
use App\Entity\Dto\Cart\CartItemDto;
use App\Response\Dto\CartResponse;
use Doctrine\Common\Collections\ArrayCollection;

class CartItemCollection extends ArrayCollection
{
    public function getTotalCount(): int
    {
        return $this->reduce(
            fn(int $carry, CartItem $item) => $carry + $item->getCount(),
            0
        );
    }

    public function getAsDto(): CartResponse
    {
        $totalCost = 0;
        $totalCount = 0;
        $items = [];

        /**
         * @var CartItem $cartItem
         **/
        foreach ($this->toArray() as $cartItem) {
            $count = $cartItem->getCount();
            $cost = $count * $cartItem->getProduct()->getCost();
            $totalCost += $cost;
            $totalCount += $count;

            $items[] = new CartItemDto(
                $cartItem->getProduct()->getId(),
                $count,
                $cost,
                $cartItem->getProduct()->getName(),
            );
        }

        return new CartResponse($totalCount, $totalCost, $items);
    }
}