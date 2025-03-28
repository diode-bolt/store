<?php

namespace App\Request\Dto;

use OpenApi\Attributes\Property;
use Symfony\Component\Validator\Constraints as Assert;


class ListRequest
{
    #[Property]
    #[Assert\NotBlank]
    readonly int $start;

    #[Property]
    readonly int $limit;

    public function __construct(int $start, int $limit)
    {
        $this->start = $start;
        $this->limit = $limit;
    }
}