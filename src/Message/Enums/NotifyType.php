<?php

namespace App\Message\Enums;

use App\Entity\Users\User;

enum NotifyType: string
{
    case EMAIL = 'email';
    case SMS = 'sms';

    public function getLabel(): string
    {
        return match ($this) {
            self::EMAIL => 'Электронная почта',
            self::SMS => 'SMS сообщение'
        };
    }

    public function getContact(User $user): ?string
    {
        return match ($this) {
            self::EMAIL => $user->getEmail(),
            self::SMS => $user->getPhone()
        };
    }

    public static function getUserNotifyType(User $user): self
    {
        if ($user->getPhone()) {
            return NotifyType::SMS;
        }

        return NotifyType::EMAIL;
    }
}
