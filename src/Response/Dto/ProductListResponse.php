<?php

namespace App\Response\Dto;

use App\Entity\Dto\Product\ProductListItem;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;

#[Response(response: 200)]
class ProductListResponse extends ListResponse
{

    /**
     * @var ProductListItem[]
     */
    #[Property(
        type: 'array',
        items: new Items(ref: new Model(type: ProductListItem::class))
    )]
    public readonly array $data;
}