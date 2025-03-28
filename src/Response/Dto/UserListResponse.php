<?php

namespace App\Response\Dto;

use App\Entity\Users\User;
use App\Response\Dto\Interfaces\JsonListResponse;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;

#[Response]
class UserListResponse implements JsonListResponse
{
    #[Property]
    public readonly int $total;

    /**
     * @var UserListDto[]
     */
    #[Property(
        type: 'array',
        items: new Items(ref: new Model(type: UserListDto::class))
    )]
    public readonly array $data;

    /**
     * @param UserListDto[] $data
     */
    public function __construct(int $total, array $data)
    {
        $this->total = $total;
        $this->data = $data;
    }
}