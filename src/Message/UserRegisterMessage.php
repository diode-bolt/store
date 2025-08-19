<?php

namespace App\Message;

use App\Message\Enums\NotifyType;

class UserRegisterMessage
{
    public function __construct(
        public readonly NotifyType $type,
        public readonly string $contact,
        public readonly ?string $promoId
    )
    {
    }
}