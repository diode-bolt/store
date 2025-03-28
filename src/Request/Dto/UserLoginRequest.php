<?php

namespace App\Request\Dto;

use OpenApi\Attributes\Property;
use Symfony\Component\Validator\Constraints as Assert;

class UserLoginRequest
{
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Property(
        type: 'string',
        example: 'vasya@gmail.com'
    )]
    protected readonly string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 8)]
    #[Property(
        type: 'string',
        minLength: 8,
        example: 'you_best_pass',
    )]
    protected readonly string $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }
}