<?php

namespace App\Service;

use App\Entity\CartItem;
use App\Entity\Dto\Order\OrderListItem;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Users\User;
use App\Query\Condition\Conditions\EqualsCondition;
use App\Query\Condition\Factory\ConditionFactory;
use App\Request\Dto\ListRequest;
use App\Response\Dto\OrderListResponse;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class OrderService
{
    use QueryListTrait;
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ConditionFactory $conditionFactory,
    )
    {
    }

    public function getListByUser(ListRequest $request, User $user): OrderListResponse
    {
        $conditions = [
            new EqualsCondition('user', $user->getId()),
        ];

        return $this->getList($request, $conditions);
    }

    public function getList(ListRequest $request, array $conditions = []): OrderListResponse
    {
        foreach ($request->filters as $filter) {
            $conditions[] = $this->conditionFactory->create(Order::class, $filter);
        }

        $paginator = $this->getEntityList(
            entityClass: Order::class,
            dtoClass: OrderListItem::class,
            start: $request->start,
            limit: $request->limit,
            conditions: $conditions,
        );

        return new OrderListResponse($paginator->count(), $paginator->getIterator());
    }

    public function createOrder(User $user): void
    {
        $order = new Order();

        /**
         * @var ArrayCollection<int, CartItem> $cart
         **/
        $cart = $user->getCart();

        $order->setUser($user);

        foreach ($cart as $item) {
            $orderItem = new OrderItem();
            $orderItem
                ->setProduct($item->getProduct())
                ->setCount($item->getCount())
                ->setOrder($order);

            $this->entityManager->persist($orderItem);
        }

        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }
}