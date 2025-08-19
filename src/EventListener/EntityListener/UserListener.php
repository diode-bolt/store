<?php

namespace App\EventListener\EntityListener;

use App\Entity\Users\User;
use App\Message\Enums\NotifyType;
use App\Message\UserRegisterMessage;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsEntityListener(event: Events::postPersist, entity: User::class)]
final class UserListener
{
    public function __construct(private MessageBusInterface $bus)
    {
    }

    public function postPersist(User $user): void
    {
        $notifyType = NotifyType::getUserNotifyType($user);

        $this->bus->dispatch(new UserRegisterMessage($notifyType, $notifyType->getContact($user), $user->getPromoId()));
    }
}