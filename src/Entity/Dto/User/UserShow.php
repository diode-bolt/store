<?php

namespace App\Entity\Dto\User;

use App\Entity\Users\User;
use App\Response\Dto\Interfaces\JsonResponseInterface;
use OpenApi\Attributes\Response;

#[Response(response: 200)]
class UserShow implements JsonResponseInterface
{
    public function __construct(
        public readonly string $id,
        public readonly string $email,
        public readonly string $name,
        public readonly string $phone,
        public readonly string $promoId,
    )
    {
    }

    public static function with(User $user): self
    {
        return new self(
            $user->getId(),
            $user->getEmail(),
            $user->getName(),
            $user->getPhone(),
            $user->getPromoId()
        );
    }
}