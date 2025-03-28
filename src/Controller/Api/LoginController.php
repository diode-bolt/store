<?php

namespace App\Controller\Api;

use App\Entity\Users\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/login')]
class LoginController extends AbstractController
{
    public function index(#[CurrentUser] ?User $user): Response
    {
         if (null === $user) {
             return $this->json([
                    'success' => false,
                     'message' => 'missing credentials',
                 ], Response::HTTP_UNAUTHORIZED);
         }

         $token = '';

          return $this->json([
              'success' => true,
               'token' => $token,
          ]);
      }

}