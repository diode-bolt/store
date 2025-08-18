<?php

namespace App\Controller\Api;

use App\Entity\Dto\User\UserRequest;
use App\Entity\Dto\User\UserShow;
use App\Entity\Users\User;
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
    #[Route('/show', name: 'app_api_user_show', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function show(): UserShow
    {
        /**
         * @var User $user
         **/
        $user = $this->getUser();

        return UserShow::with($user);
    }

    #[Route('/edit', name: 'app_api_user_edit', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(#[MapRequestPayload] UserRequest $userDto, UserService $service): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $user
            ->setEmail($userDto->email)
            ->setPhone($userDto->phone)
            ->setName($userDto->name)
            ->setPromoId($userDto->promoId);

        $service->changePass($user, $userDto->password);

        return $this->json(['success' => true]);
    }
}
