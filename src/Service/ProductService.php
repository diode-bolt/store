<?php

namespace App\Service;

use App\Entity\Dto\ProductListItem;
use App\Entity\Product;
use App\Request\Dto\ListRequest;
use App\Response\Dto\ProductListResponse;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ProductService
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function getList(ListRequest $request): ProductListResponse
    {
        $query = $this->entityManager
            ->createQuery('SELECT NEW ' . ProductListItem::class .  '(p.id, p.mame, p.description, p.cost) FROM ' . Product::class . ' p')
            ->setFirstResult($request->start);

        if ($request->limit > 0) {
            $query->setMaxResults($request->limit);
        }

        $paginator = new Paginator($query);

        return new ProductListResponse(count($paginator), $paginator->getIterator());
    }
}