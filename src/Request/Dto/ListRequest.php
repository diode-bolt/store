<?php

namespace App\Request\Dto;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes\AdditionalProperties;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use Symfony\Component\Validator\Constraints as Assert;

class ListRequest
{
    #[Property]
    #[Assert\NotBlank]
    readonly int $start;

    #[Property]
    readonly int $limit;

    #[Property(
        type: 'object',
        nullable: true,
        additionalProperties: new AdditionalProperties(enum: ['ASC', 'DESC'])
    )]
    #[Assert\All([
        new Assert\AtLeastOneOf([
            new Assert\IdenticalTo('ASC'),
            new Assert\IdenticalTo('DESC'),
        ]),
    ])]
    readonly ?array $orderBy;

    #[Property(
        type: 'array',
        items: new Items(ref: new Model(type: FilterDto::class))
    )]
    /** @var FilterDto[] */
    readonly array $filters;

    public function __construct(int $start, int $limit, array $filters = [], ?array $orderBy = null)
    {
        $this->start = $start;
        $this->limit = $limit;
        $this->filters = $filters;
        $this->orderBy = $orderBy;
    }
}