<?php

namespace App\Controller\Api\Admin;

use App\Entity\Dto\User\UserShow;
use App\Entity\Users\User;
use App\Request\Dto\ListRequest;
use App\Response\Dto\UserListResponse;
use App\Service\UserService;
use OpenApi\Attributes\Tag;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/admin/user')]
#[Tag('users')]
#[IsGranted('ROLE_ADMIN')]
class UserAdminController extends AbstractController
{
    #[Route('/list' , name: 'api_user_index', methods: ['GET'])]
    public function index(#[MapRequestPayload] ListRequest $request, UserService $userService): UserListResponse
    {
        return $userService->getList($request);
    }

    #[Route('/show/{id<\d+>}' , name: 'api_user_show', methods: ['GET'])]
    public function show(#[MapEntity] User $user): UserShow
    {
        return UserShow::with($user);
    }
}