<?php

namespace App\Controller\Api\Order;

use App\Request\Dto\CartItem;
use App\Response\Dto\CartResponse;
use App\Service\CartService;
use OpenApi\Attributes\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/order')]
#[Tag('Order')]
#[IsGranted('ROLE_USER')]
class CartController extends AbstractController
{
    #[Route('/add_to_cart', name: 'app_cart_add', methods: ["POST"])]
    public function addItem(#[MapRequestPayload] CartItem $item, CartService $cartService): CartResponse
    {
        $cart = $cartService->addToCart($item, $this->getUser());

        return $cart->getAsDto();
    }

    #[Route('/remove_from_cart', name: 'app_cart_remove', methods: ["DELETE"])]
    public function removeItem(#[MapRequestPayload] CartItem $item, CartService $cartService): CartResponse
    {
        $cart = $cartService->removeItem($item, $this->getUser());

        return $cart->getAsDto();
    }

    #[Route('/clear_cart', name: 'app_cart_clear', methods: ["DELETE"])]
    public function clearCart(CartService $cartService): CartResponse
    {
        $cart = $cartService->clearCart($this->getUser());

        return $cart->getAsDto();
    }
}