<?php

namespace App\Response\Dto;

class UserListDto
{
    public readonly int $id;

    public readonly string $email;

    public function __construct(int $id, string $email)
    {
        $this->id = $id;
        $this->email = $email;
    }
}