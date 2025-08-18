<?php

namespace App\Entity\Dto\User;

class UserListItem
{
    public function __construct(public readonly int $id, public readonly string $email)
    {
    }
}