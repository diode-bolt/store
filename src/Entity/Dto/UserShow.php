<?php

namespace App\Entity\Dto;

use App\Response\Dto\Interfaces\JsonResponseInterface;
use OpenApi\Attributes\Response;

#[Response(response: 200)]
class UserShow implements JsonResponseInterface
{
    public function __construct(
        public readonly string $id,

    )
    {
    }
}