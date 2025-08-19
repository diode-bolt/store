<?php

namespace App\Controller\Api\Admin;

use App\Entity\Dto\Order\OrderDto;
use App\Entity\Order;
use App\Request\Dto\ListRequest;
use App\Response\Dto\OrderListResponse;
use App\Service\OrderService;
use OpenApi\Attributes\Tag;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/admin/oder')]
#[Tag('Order')]
#[IsGranted('ROLE_ADMIN')]
class OrderAdminController extends AbstractController
{
    #[Route('/list' , name: 'api_admin_order_index', methods: ['GET'])]
    public function index(#[MapRequestPayload] ListRequest $request, OrderService $service): OrderListResponse
    {
        return $service->getList($request);
    }

    #[Route('/show/{id<\d+>}' , name: 'api_admin_order_show', methods: ['GET'])]
    public function show(#[MapEntity] Order $order): OrderDto
    {
        return OrderDto::with($order);
    }
}