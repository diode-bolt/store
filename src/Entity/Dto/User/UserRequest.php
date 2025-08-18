<?php

namespace App\Entity\Dto\User;

use App\Validator\UniqueUserEmailAndPhone;
use OpenApi\Attributes\Property;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueUserEmailAndPhone]
class UserRequest extends UserLoginRequest
{
    #[Assert\NotBlank]
    #[Assert\Regex('/^\+7?\(([0-9]{3})\)?[-]?([0-9]{3})[-]?([0-9]{2})[-]?([0-9]{2})$/')]
    #[Property(
        type: 'string',
        example: '+7(995)-990-52-23',
    )]
    public readonly string $phone;

    #[Assert\NotBlank]
    #[Property(
        type: 'string',
        example: 'Петр',
    )]
    public readonly string $name;

    #[Assert\Uuid]
    public readonly ?string $promoId;

    public function __construct(
        string $email,
        #[\SensitiveParameter] string $password,
        string $name,
        string $phone,
        ?string $promoId = null
    )
    {
        parent::__construct($email, $password);

        $this->name = $name;
        $this->phone = $phone;
        $this->promoId = $promoId;
    }
}