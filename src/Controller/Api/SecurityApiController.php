<?php

namespace App\Controller\Api;

use App\Entity\Dto\User\UserLoginRequest;
use App\Entity\Dto\User\UserRequest;
use App\Response\Dto\UserRegisterResponse;
use App\Service\UserService;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use OpenApi\Attributes\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class SecurityApiController extends AbstractController
{
    #[Route('/api/login', name: 'app_user_login', methods: ['POST'])]
    #[Tag(name: 'Login')]
    public function userLogin(#[MapRequestPayload] UserLoginRequest $userDto): UserRegisterResponse
    {
        return new UserRegisterResponse('');
    }

    #[Route('/admin/login', name: 'app_admin_login', methods: ['POST'])]
    #[Tag(name: 'Login')]
    public function adminLogin(#[MapRequestPayload] UserLoginRequest $userDto): UserRegisterResponse
    {
        return new UserRegisterResponse('');
    }

    #[Route('/api/register', name: 'app_user_register', methods: ['POST'])]
    #[Tag(name: 'register')]
    public function register(
        #[MapRequestPayload] UserRequest $userDto,
        UserService                      $registerService,
        JWTTokenManagerInterface         $jwtManager

    ): UserRegisterResponse
    {
        $user = $registerService->register($userDto);

        return new UserRegisterResponse($jwtManager->create($user));
    }
}