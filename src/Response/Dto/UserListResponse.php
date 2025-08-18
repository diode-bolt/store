<?php

namespace App\Response\Dto;

use App\Entity\Dto\User\UserListItem;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;

#[Response(response: 200)]
class UserListResponse extends ListResponse
{
    /**
     * @var UserListItem[]
     */
    #[Property(
        type: 'array',
        items: new Items(ref: new Model(type: UserListItem::class))
    )]
    public readonly array $data;
}