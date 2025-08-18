<?php

namespace App\Service;

use App\Collection\CartItemCollection;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Entity\Users\User;
use App\Repository\ProductRepository;
use App\Request\Dto\CartItem as CartItemDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CartService
{
    public function __construct(
        private readonly EntityManagerInterface        $manager,
        private readonly ProductRepository             $productRepository,
        #[Autowire('maxCartSize')]private readonly int $maxTotal
    )
    {
    }

    public function addToCart(CartItemDto $item, User $user): CartItemCollection
    {
        $product = $this->productRepository->find($item->productId);

        if (!$product) {
            throw new NotFoundHttpException("product with id {$item->productId} not found");
        }

        $cart = $user->getCart();
        $totalCount = $cart->getTotalCount();
        $cartItem = $cart->findFirst(
            fn(int $key, CartItem $item) => $item->getProduct()->getId() === $product->getId()
        );

        if ($totalCount + $item->count > $this->maxTotal) {
            throw new BadRequestException(
                sprintf(
                    "Cart limit exceeded (current: %d, max: %d)",
                    $totalCount + $item->count,
                    $this->maxTotal
                )
            );
        }

        if ($cartItem) {
            $cartItem->addCount($item->count);
        } else {
            $cartItem = new CartItem();

            $cartItem
                ->setUser($user)
                ->setCount($item->count)
                ->setProduct($product);

            $cart->add($cartItem);
            $this->manager->persist($cartItem);
        }

        $this->manager->flush();

        return $cart;
    }

    public function removeItem(CartItemDto $item, User $user): CartItemCollection
    {
        /**
         * @var ?Product $product
         **/
        $product = $this->productRepository->find($item->productId);

        if (!$product) {
            throw new NotFoundHttpException("product with id {$item->productId} not found");
        }

        $cart = $user->getCart();
        /**
         * @var ?CartItem $cartItem
         **/
        $cartItem = $cart->findFirst(
            fn(int $key, CartItem $item) => $item->getProduct()->getId() === $product->getId()
        );

        if (!$cartItem) {
            throw new BadRequestException(
                sprintf(
                    "Product with id %d not in cart",
                    $product->getId(),
                )
            );
        }

        $cartItem->addCount(-$item->count);

        if ($cartItem->getCount() <= 0) {
            $cart->removeElement($cartItem);
            $this->manager->remove($cartItem);
        }

        $this->manager->flush();

        return $cart;
    }

    public function clearCart(User $user): CartItemCollection
    {
        $cart = $user->getCart();

        if ($cart->isEmpty()) {
            return $cart;
        }

        try {
            foreach ($cart as $cartItem) {
                $this->manager->remove($cartItem);
            }

            $cart->clear();
            $this->manager->flush();

            return $cart;
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to clear cart', 0, $e);
        }
    }
}