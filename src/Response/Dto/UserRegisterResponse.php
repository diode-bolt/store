<?php

namespace App\Response\Dto;

use App\Response\Dto\Interfaces\JsonPropertyProviderResponse;
use OpenApi\Attributes\Property;

class UserRegisterResponse implements JsonPropertyProviderResponse
{
    #[Property(
        type: 'string',
        example: 'jfdjm9srt8s0sgbs00gfh9s0gf09h...'
    )]
    public readonly string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function getPropertiesData(): array
    {
        return ['token' => $this->token];
    }
}