<?php

namespace App\Service;

use App\Entity\Dto\Product\ProductListItem;
use App\Entity\Product;
use App\Query\Condition\Factory\ConditionFactory;
use App\Request\Dto\ListRequest;
use App\Response\Dto\ProductListResponse;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ProductService
{
    use QueryListTrait, FilterConditionBuilder;
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ConditionFactory $conditionFactory,
    )
    {
    }

    public function getList(ListRequest $request): ProductListResponse
    {
        $conditions = $this->buildFromFilters($request, Product::class);

        $paginator = $this->getEntityList(
            entityClass: Product::class,
            dtoClass: ProductListItem::class,
            start: $request->start,
            limit: $request->limit,
            conditions: $conditions,
            orderBy: $request->orderBy,
        );

        return new ProductListResponse(count($paginator), iterator_to_array($paginator->getIterator()));
    }
}