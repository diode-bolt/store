<?php

namespace App\Entity\Enums;

enum ManagerType: int
{
    case ADMIN = 0;

    public function getRoles(): array
    {
        return match ($this) {
            self::ADMIN => ['ROLE_ADMIN'],
        };
    }
}
