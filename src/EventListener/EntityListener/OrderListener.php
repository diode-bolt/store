<?php

namespace App\EventListener\EntityListener;

use App\Entity\Order;
use App\Message\Enums\NotifyType;
use App\Message\UserRegisterMessage;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsEntityListener(event: Events::postPersist, entity: Order::class)]
final class OrderListener
{
    public function __construct(private MessageBusInterface $bus)
    {
    }

    public function postPersist(Order $order): void
    {
        $notifyType = NotifyType::getUserNotifyType($order->getUser());

        $this->bus->dispatch(new UserRegisterMessage($notifyType, $notifyType->getContact($user), $user->getPromoId()));
    }
}