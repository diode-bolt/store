<?php

namespace App\Controller\Api;

use App\Entity\Dto\UserRequest;
use App\Request\Dto\ListRequest;
use App\Response\Dto\UserListResponse;
use App\Service\UserService;
use OpenApi\Attributes\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/user')]
#[Tag('users')]
final class UserController extends AbstractController
{
    #[Route(name: 'api_user_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(#[MapRequestPayload] ListRequest $request, UserService $listService): UserListResponse
    {
        return $listService->getList($request);
    }

    #[Route('/show', name: 'app_api_user_show', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function show(): Response
    {
        return $this->render('api/user/show.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/edit', name: 'app_api_user_edit', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(#[MapRequestPayload] UserRequest $userDto, UserService $service): Response
    {
        $user = $this->getUser();
        $user
            ->setEmail($userDto->email)
            ->setPhone($userDto->phone)
            ->setName($userDto->name)
            ->setPromoId($userDto->promoId);

        $service->changePass($user, $userDto->password);

        return $this->json(['success'=>true]);
    }
}
