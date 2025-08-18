<?php

namespace App\Controller\Api;

use App\Entity\Dto\Product\ProductItem;
use App\Entity\Product;
use App\Request\Dto\ListRequest;
use App\Response\Dto\ProductListResponse;
use App\Service\ProductService;
use OpenApi\Attributes\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/product')]
#[Tag('Product')]
final class ProductController extends AbstractController
{
    #[Route('/list', name: 'app_product_list', methods: ["post"])]
    public function index(#[MapRequestPayload] ListRequest $request, ProductService $service): ProductListResponse
    {
        return $service->getList($request);
    }

    #[Route('/show/{id<\d+>}', name: 'app_product_read', methods: ["GET"])]
    public function show(Product $product): ProductItem
    {
        return ProductItem::with($product);
    }
}
